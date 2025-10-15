<?php

namespace Medpzl\Clubdata\Controller;

use Medpzl\Clubdata\Domain\Model\FrontendUser;
use Medpzl\Clubdata\Domain\Model\ProgramService;
use Medpzl\Clubdata\Domain\Repository\CategoryRepository;
use Medpzl\Clubdata\Domain\Repository\FrontendUserRepository;
use Medpzl\Clubdata\Domain\Repository\ProgramRepository;
use Medpzl\Clubdata\Domain\Repository\ProgramServiceRepository;
use Medpzl\Clubdata\Domain\Repository\ServiceRepository;
use Medpzl\Clubdata\Domain\Service\SessionHandler;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

class BackendController extends ActionController
{
    public function __construct(
        protected PersistenceManager $persistenceManager,
        protected ProgramRepository $programRepository,
        protected CategoryRepository $categoryRepository,
        protected ProgramServiceRepository $programServiceRepository,
        protected ServiceRepository $serviceRepository,
        protected FrontendUserRepository $userRepository,
        protected SessionHandler $sessionHandler,
        private Typo3QuerySettings $typo3QuerySettings,
        private readonly ModuleTemplateFactory $moduleTemplateFactory,
        private readonly PageRenderer $pageRenderer
    ) {
        setlocale(LC_TIME, 'de_DE.UTF8');
    }

    public function listHelpersAction(): ResponseInterface
    {
        $querySettings = clone $this->typo3QuerySettings;
        $querySettings->setRespectStoragePage(false);
        if (method_exists($this->userRepository, 'setDefaultQuerySettings')) {
            $this->userRepository->setDefaultQuerySettings($querySettings);
        }

        $fromdate = date('Ym' . '01');
        $todate = date('Ymt') . ' 23:59';
        if ($this->settings['filter'] ?? false) {
            $filter = $this->settings['filter'];
        }
        if ($this->request->hasArgument('catUid') && $this->request->getArgument('catUid')) {
            $filter['categories'] = $this->request->getArgument('catUid');
        }

        $filter['noservice'] = 1;

        if ($this->request->hasArgument('date')) {
            $todate = $this->request->getArgument('date');
            $fromdate = $todate . '01';
            $todate = date('YmtHi', strtotime($fromdate . ' 23:59'));
        }

        if ($this->settings['list']['show'] == 'month') {
            $nextmonth = date('Ym', strtotime($fromdate . ' + 1 month'));
            $prevmonth = date('Ym', strtotime($fromdate . ' - 1 month'));
            $currmonth = date('Ym', strtotime($fromdate));
            $thismonth = date('Ym', strtotime($fromdate));
        } elseif ($this->settings['list']['show'] == 'year') {
            $fromdate = date('Y', strtotime($fromdate)) . '0101';
            $todate = date('Y', strtotime($fromdate)) . '1231 23:59';
            $nextmonth = date('Ym', strtotime($fromdate . ' + 1 year'));
            $prevmonth = date('Ym', strtotime($fromdate . ' - 1 year'));
            $currmonth = date('Y', strtotime($fromdate));
            $thismonth = date('Ym', strtotime($fromdate));
        } else {
            $thismonth = date('Ym', strtotime($fromdate));
            $currmonth = date('Ym', strtotime($fromdate));
            unset($todate);
        }
        $showmonth = date('Ymd', strtotime($fromdate));
        $now = date('c');

        $services = $this->serviceRepository->findAll();

        $programs = $this->programRepository->findWithinMonth(
            $filter,
            strtotime($fromdate),
            strtotime($todate),
            $this->settings['list']['greaternow'],
            $now
        );

        $users = $this->userRepository->findAll();

        $filtered_users = [];
        foreach ($users as $usr) {
            foreach ($usr->getUsergroup() as $group) {
                if ($group->getUid() == intval($this->settings['service']['groupId'] ?? 0)) {
                    if ($usr->getFirstName() != '') {
                        $filtered_users[] = $usr;
                    }
                }
            }
        }

        $names = [];
        foreach ($filtered_users as $object) {
            if ($this->settings['service']['sortby'] == 'firstname') {
                $names[] = $object->getFirstName();
            } else {
                $names[] = $object->getLastName();
            }
        }

        array_multisort($names, SORT_ASC, $filtered_users);

        $admin_users = [];
        foreach ($filtered_users as $user) {
            $new = GeneralUtility::makeInstance(FrontendUser::class);
            $new->setUsername($user->getFirstName() . ' ' . $user->getLastName());
            $new->setAddress($user->getUid());
            $admin_users[] = $new;
        }

        $latest = $this->programRepository->findLatest();
        $oldest = $this->programRepository->findOldest($this->settings['list']['greaternow'], $now);

        if ($this->settings['list']['pageyears']) {
            $d1 = $latest[0]->getDatetime();
            $d2 = $oldest[0]->getDatetime();
            $diff = $d2->diff($d1);
            $diff = $diff->format('%a');
            if ($this->settings['list']['show'] == 'year') {
                $elements = floor($diff / 30 / 12);
                $format = 'Y';
            } else {
                $elements = floor($diff / 30);
                $format = 'n';
            };
            $elements += 1;
            $nav = [];
            for ($i = 0; $i < $elements; $i++) {
                $date = $d2->format($format) + $i;
                $year = $d2->format('Y') + $i;
                $refdate = $d2->format('Y') + $i . '01';
                if ($format == 'n') {
                    $month = intval($d2->format('n') + $i);
                    $mod = intval($month / 12);
                    $year = $d2->format('Y');

                    if ($mod) {
                        if ($month % 12) {
                            $month = $month - $mod * 12;
                        }
                        if ($month % 12) {
                            $year += $mod;
                        }
                    }

                    $date = strftime('%b', strtotime($year . sprintf('%02d', $month) . '01'));
                    $refdate = date('Ym', strtotime($year . sprintf('%02d', $month) . '01'));
                };
                if ($format == 'n') {
                    $year = $year . $month;
                }
                $nav[] = ['title' => $date,
                    'date' => $refdate,
                    'year' => $year
                ];
            }
        }

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setTitle('Helfer Liste');

        $moduleTemplate->assign('nav', $nav ?? []);
        $moduleTemplate->assign('currmonth', $currmonth);
        $moduleTemplate->assign('thismonth', $thismonth);
        $moduleTemplate->assign('showmonth', strtotime($showmonth));
        $moduleTemplate->assign('prevmonth', $prevmonth);
        $moduleTemplate->assign('nextmonth', $nextmonth);
        $moduleTemplate->assign('latest', $latest[0]);
        $moduleTemplate->assign('oldest', $oldest[0]);
        $moduleTemplate->assign('todate', $todate);
        $moduleTemplate->assign('now', time());
        $moduleTemplate->assign('Programs', $programs);
        $moduleTemplate->assign('Services', $services);
        $moduleTemplate->assign('Users', $admin_users);
        $moduleTemplate->assign('User', $user);

        // Add CSS and JS files
        $this->pageRenderer->addCssFile('EXT:clubdata/Resources/Public/Css/Backend.css');
        $this->pageRenderer->addJsFile('EXT:clubdata/Resources/Public/Js/clubdata.js');

        return $moduleTemplate->renderResponse('Backend/ListHelpers');
    }

    public function saveHelpersAction()
    {
        $querySettings = clone $this->typo3QuerySettings;
        $querySettings->setRespectStoragePage(false);
        if (method_exists($this->userRepository, 'setDefaultQuerySettings')) {
            $this->userRepository->setDefaultQuerySettings($querySettings);
        }

        $args = $this->request->getArguments();
        if ($this->request->hasArgument('ps')) {
            $entries = $args['ps'];
        }

        $items = [];
        foreach ($entries as $entry) {
            $parts = explode('-', $entry);
            $changed = 0;
            if ($parts[3] == 'c') {
                $changed = 1;
            }
            if ($parts[3] == 'd') {
                $changed = 2;
            } // delete
            $items[] = [
                'user' => substr($parts[2], 1),
                'program' => substr($parts[0], 1),
                'service' => substr($parts[1], 1),
                'changed' => $changed
            ];
        }

        foreach ($items as $item) {
            if ($item['changed']) {
                if ($item['changed'] == 2) {
                    $delete = $this->programServiceRepository->findEntry($item['user'], $item['program'], $item['service']);
                    if (count($delete)) {
                        $this->programServiceRepository->remove($delete[0]);
                        $this->persistenceManager->persistAll();
                    }
                } else {
                    $update = $this->programServiceRepository->findEntry(0, $item['program'], $item['service']);
                    if (count($update)) {
                        $operation = 'update';
                    } else {
                        $operation = 'insert';
                    }
                    if ($operation == 'insert') {
                        if (count($this->programServiceRepository->findEntry(0, $item['program'], $item['service']))) {
                            // already booked
                        } else {
                            $user = $this->userRepository->findByUid($item['user']);
                            $program = $this->programRepository->findByUid($item['program']);
                            $service = $this->serviceRepository->findByUid($item['service']);
                            $newProgramService = GeneralUtility::makeInstance(ProgramService::class);
                            $newProgramService->setUser($user);
                            $newProgramService->setProgram($program);
                            $newProgramService->setService($service);
                            $this->programServiceRepository->add($newProgramService);
                            $this->persistenceManager->persistAll();
                        }
                    } else {
                        $user = $this->userRepository->findByUid($item['user']);
                        $newProgramService = $update[0];
                        $newProgramService->setUser($user);
                        $this->programServiceRepository->update($newProgramService);
                        $this->persistenceManager->persistAll();
                    }
                }
            }
        }

        $arguments = [];
        if ($this->request->hasArgument('date')) {
            $arguments = ['date' => $this->request->getArgument('date')];
        }
        return $this->redirect('listHelpers', null, null, $arguments);
    }
}
