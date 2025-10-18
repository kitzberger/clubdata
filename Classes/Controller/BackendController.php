<?php

namespace Medpzl\Clubdata\Controller;

use Medpzl\Clubdata\Domain\Model\FrontendUser;
use Medpzl\Clubdata\Domain\Model\ProgramService;
use Medpzl\Clubdata\Domain\Model\ProgramServiceUser;
use Medpzl\Clubdata\Domain\Repository\CategoryRepository;
use Medpzl\Clubdata\Domain\Repository\FrontendUserRepository;
use Medpzl\Clubdata\Domain\Repository\ProgramRepository;
use Medpzl\Clubdata\Domain\Repository\ProgramServiceUserRepository;
use Medpzl\Clubdata\Domain\Repository\ServiceRepository;
use Medpzl\Clubdata\Domain\Service\SessionHandler;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
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
        protected ProgramServiceUserRepository $programServiceUserRepository,
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

        // Get all them services (Theke, Kasse, ...)
        $services = $this->serviceRepository->findAll();

        // Get all the events of this month
        $programs = $this->programRepository->findWithinMonth(
            $filter,
            strtotime($fromdate),
            strtotime($todate),
            $this->settings['list']['greaternow'],
            $now
        );

        // Get all potential users
        if ($this->settings['service']['groupId'] ?? false) {
            $users = $this->userRepository->findByGroup((int)$this->settings['service']['groupId']);
        } else {
            $users = $this->userRepository->findAll();
        }

        $users = $users->toArray();
        usort($users, function ($a, $b) {
            return $a->getLastname() <=> $b->getLastname();
        });

        $latest = $this->programRepository->findLatest();
        $oldest = $this->programRepository->findOldest($this->settings['list']['greaternow'], $now);

        if ($this->settings['list']['pageyears'] ?? false) {
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
        $moduleTemplate->assign('programs', $programs);
        $moduleTemplate->assign('services', $services);
        $moduleTemplate->assign('users', $users);

        // Add CSS and JS files
        $this->pageRenderer->addCssFile('EXT:clubdata/Resources/Public/Css/Backend.css');
        $this->pageRenderer->addJsFile('EXT:clubdata/Resources/Public/Js/backend.js');

        return $moduleTemplate->renderResponse('Backend/ListHelpers');
    }

    public function saveHelpersAction()
    {
        $querySettings = clone $this->typo3QuerySettings;
        $querySettings->setRespectStoragePage(false);
        if (method_exists($this->userRepository, 'setDefaultQuerySettings')) {
            $this->userRepository->setDefaultQuerySettings($querySettings);
        }

        if ($this->request->hasArgument('psu')) {
            $entries = $this->request->getArgument('psu');
        }

        $items = [];
        foreach ($entries as $entry) {
            $parts = explode('-', $entry);
            $changed = ProgramServiceUser::OPERATION_NONE;
            if ($parts[3] == 'c') {
                $changed = ProgramServiceUser::OPERATION_CHANGE;
            }
            if ($parts[3] == 'd') {
                $changed = ProgramServiceUser::OPERATION_DELETE;
            }
            $items[] = [
                'user' => substr($parts[2], 1),
                'program' => substr($parts[0], 1),
                'service' => substr($parts[1], 1),
                'changed' => $changed
            ];
        }

        $count = 0;
        foreach ($items as $item) {
            if ($item['changed']) {
                $count++;
                if ($item['changed'] == ProgramServiceUser::OPERATION_DELETE) {
                    $programServiceUser = $this->programServiceUserRepository->findEntry($item['user'], $item['program'], $item['service']);
                    if (count($programServiceUser)) {
                        $this->programServiceUserRepository->remove($programServiceUser[0]);
                    }
                } else {
                    $programServiceUser = $this->programServiceUserRepository->findEntry(0, $item['program'], $item['service']);
                    $user = $this->userRepository->findByUid($item['user']);
                    if (count($programServiceUser) === 0) {
                        $program = $this->programRepository->findByUid($item['program']);
                        $service = $this->serviceRepository->findByUid($item['service']);
                        $newProgramService = GeneralUtility::makeInstance(ProgramServiceUser::class);
                        $newProgramService->setUser($user);
                        $newProgramService->setProgram($program);
                        $newProgramService->setService($service);
                        $this->programServiceUserRepository->add($newProgramService);
                    } else {
                        $programServiceUser = $programServiceUser[0];
                        if ($programServiceUser) {
                            $programServiceUser->setUser($user);
                            $this->programServiceUserRepository->update($programServiceUser);
                        }
                    }
                }
            }
        }

        if ($count > 0) {
            $this->addFlashMessages(sprintf('%d relation(s) changed.', $count), 'Helperplan updated', ContextualFeedbackSeverity::OK);
        } else {
            $this->addFlashMessages('Nothing changed.', 'Helperplan not updated', ContextualFeedbackSeverity::WARNING);
        }

        $arguments = [];
        if ($this->request->hasArgument('date')) {
            $arguments = ['date' => $this->request->getArgument('date')];
        }
        return $this->redirect('listHelpers', null, null, $arguments);
    }

    private function addFlashMessages($message, $header, $severity)
    {
        $message = GeneralUtility::makeInstance(FlashMessage::class,
           $message,
           $header,
           $severity,
           true
        );
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
    }
}
