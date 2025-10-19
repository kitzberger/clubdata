<?php

namespace Medpzl\Clubdata\Controller;

use Medpzl\Clubdata\Domain\Model\ProgramServiceUser;
use Medpzl\Clubdata\Domain\Repository\CategoryRepository;
use Medpzl\Clubdata\Domain\Repository\FrontendUserRepository;
use Medpzl\Clubdata\Domain\Repository\ProgramRepository;
use Medpzl\Clubdata\Domain\Repository\ProgramServiceUserRepository;
use Medpzl\Clubdata\Domain\Repository\ServiceRepository;
use Medpzl\Clubdata\Domain\Service\SessionHandler;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class ClubController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    public function __construct(
        protected PersistenceManager $persistenceManager,
        protected ProgramRepository $programRepository,
        protected CategoryRepository $categoryRepository,
        protected ProgramServiceUserRepository $programServiceUserRepository,
        protected ServiceRepository $serviceRepository,
        protected FrontendUserRepository $userRepository,
        protected SessionHandler $sessionHandler
    ) {
    }

    public function listAction(): ResponseInterface
    {
        $fromdate = date('Ym' . '01');
        $todate = date('Ymt') . ' 23:59';

        if ($this->settings['filter']) {
            $filter = $this->settings['filter'];
        }

        if ($this->request->hasArgument('catUid') && $this->request->getArgument('catUid')) {
            $filter['categories'] = $this->request->getArgument('catUid');
        }

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
        if ($this->settings['list']['tillmidnight']) {
            $now = 'today';
        }

        $allprograms = $this->programRepository->findSorted($filter, $this->settings['list']['greaternow'], $now);
        $programs = $this->programRepository->findWithinMonth($filter, strtotime($fromdate), strtotime($todate), $this->settings['list']['greaternow'], $now);

        $uidlist = [];
        foreach ($allprograms as $program) {
            $uidlist[] = $program->getUid();
        }

        $this->sessionHandler->writeToSession(['uidlist' => $uidlist]);

        $latest = $this->programRepository->findLatest();
        $oldest = $this->programRepository->findOldest($this->settings['list']['greaternow'], $now);

        if ($this->settings['list']['pageyears'] && count($programs)) {
            $d1 = $latest[0]->getDatetime();
            $d2 = $oldest[0]->getDatetime();
            $diff = $d2->diff($d1);
            $diff = $diff->format('%a');
            if ($this->settings['list']['show'] == 'year') {
                $elements =  floor($diff / 30 / 12);
            }
            $elements += 1;
            $nav = [];
            for ($i = 0; $i < $elements; $i++) {
                $nav[] = [
                    'title' => $d2->format('Y') + $i,
                    'date' => $d2->format('Y') + $i . '01',
                    'year' => $d2->format('Y') + $i
                ];
            }
        }

        $this->view->assign('uidlist', $uidlist);
        $this->view->assign('nav', $nav);
        $this->view->assign('currmonth', $currmonth);
        $this->view->assign('thismonth', $thismonth);
        $this->view->assign('showmonth', strtotime($showmonth));
        $this->view->assign('prevmonth', $prevmonth);
        $this->view->assign('nextmonth', $nextmonth);
        $this->view->assign('programs', $programs);
        $this->view->assign('latest', $latest[0]);
        $this->view->assign('oldest', $oldest[0]);
        $this->view->assign('todate', $todate);
        $this->view->assign('now', time());
        $this->view->assign('categories', $this->categoryRepository->findChildrenByParent(1));
        $this->view->assign('allprograms', $allprograms);

        return $this->htmlResponse();
    }

    public function listHelpersAction(): ResponseInterface
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);

        if (method_exists($this->userRepository, 'setDefaultQuerySettings')) {
            $this->userRepository->setDefaultQuerySettings($querySettings);
        }

        $fromdate = date('Ym' . '01');
        $todate = date('Ymt') . ' 23:59';
        if ($this->settings['filter']) {
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

        $feuser = $this->request->getAttribute('frontend.user')->user['uid'];
        $user = $feuser ? $this->userRepository->findByUid($feuser) : null;

        $permissionGranted = false;

        if ($user === null) {
            $this->addFlashMessage(
                $this->getLL('listhelpers.error.not-logged-in'),
                $this->getLL('listhelpers.error'),
                ContextualFeedbackSeverity::ERROR
            );
            return $this->htmlResponse();
        }

        foreach ($user->getUsergroup() as $group) {
            if ($group->getUid() == intval($this->settings['service']['groupId'])) {
                $permissionGranted = true;
            }
        }

        if (!$permissionGranted) {
            $this->addFlashMessage(
                $this->getLL('listhelpers.error.wrong-group'),
                $this->getLL('listhelpers.error'),
                ContextualFeedbackSeverity::ERROR
            );
            return $this->htmlResponse();
        }

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

        $latest = $this->programRepository->findLatest();
        $oldest = $this->programRepository->findOldest($this->settings['list']['greaternow'], $now);

        if ($this->settings['list']['pageyears']) {
            $d1 = $latest[0]->getDatetime();
            $d2 = $oldest[0]->getDatetime();
            $diff = $d2->diff($d1);
            $diff = $diff->format('%a');
            if ($this->settings['service']['showmonth']) {
                $diff = intval($this->settings['service']['showmonth']) * 30 - 1;
            }
            if ($this->settings['list']['show'] == 'year') {
                $elements =  floor($diff / 30 / 12);
                $format = 'Y';
            } else {
                $elements =  floor($diff / 30);
                $format = 'n';
            };
            $elements += 1;
            $nav = [];
            for ($i = 0; $i < $elements; $i++) {
                $date = $d2->format($format) + $i;
                $year = $d2->format('Y') + $i;
                $refdate =  $d2->format('Y') + $i . '01';
                if ($format == 'n') {
                    $month = intval($d2->format('n') + $i);
                    $mod = intval($month / 12);
                    $year = $d2->format('Y');
                    if ($mod) {
                        if ($month % 12) {
                            $month = $month -  $mod * 12;
                        }
                        if ($month % 12) {
                            $year += $mod;
                        }
                    }

                    $date = strftime('%b', strtotime($year . sprintf('%02d', $month) . '01'));
                    $refdate = date('Ym', strtotime($year . sprintf('%02d', $month) . '01'));
                };
                $nav[] = ['title' => $date,
                    'date' => $refdate,
                    'year' => $year
                ];
            }
        }

        $this->view->assign('nav', $nav);
        $this->view->assign('currmonth', $currmonth);
        $this->view->assign('thismonth', $thismonth);
        $this->view->assign('showmonth', strtotime($showmonth));
        $this->view->assign('prevmonth', $prevmonth);
        $this->view->assign('nextmonth', $nextmonth);
        $this->view->assign('programs', $programs);
        $this->view->assign('latest', $latest[0]);
        $this->view->assign('oldest', $oldest[0]);
        $this->view->assign('todate', $todate);
        $this->view->assign('now', time());
        $this->view->assign('services', $services);
        $this->view->assign('users', $users);
        $this->view->assign('user', $user);

        return $this->htmlResponse();
    }

    public function saveHelpersAction()
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
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

        foreach ($items as $item) {
            if ($item['changed']) {
                if ($item['changed'] == ProgramServiceUser::OPERATION_DELETE) {
                    $programServiceUser = $this->programServiceUserRepository->findEntry($item['user'], $item['program'], $item['service']);
                    if (count($programServiceUser)) {
                        $this->programServiceUserRepository->remove($programServiceUser[0]);
                    }
                    $this->addFlashMessage(
                        $this->getLL('listhelpers.success.removed-yourself', [
                            $programServiceUser[0]->getService()->getTitle(),
                            $programServiceUser[0]->getProgram()->getTitle(),
                        ]),
                        $this->getLL('listhelpers.success'),
                        ContextualFeedbackSeverity::OK
                    );
                } else {
                    $programServiceUser = $this->programServiceUserRepository->findEntry(0, $item['program'], $item['service']);
                    if (count($programServiceUser)) {
                        // already booked
                        $this->addFlashMessage(
                            $this->getLL('listhelpers.warning.somebody-else-was-faster', [
                                $programServiceUser[0]->getService()->getTitle(),
                                $programServiceUser[0]->getProgram()->getTitle(),
                            ]),
                            $this->getLL('listhelpers.warning'),
                            ContextualFeedbackSeverity::WARNING
                        );
                    } else {
                        $user = $this->userRepository->findByUid($item['user']);
                        $program = $this->programRepository->findByUid($item['program']);
                        $service = $this->serviceRepository->findByUid($item['service']);
                        $newProgramServiceUser = GeneralUtility::makeInstance(ProgramServiceUser::class);
                        $newProgramServiceUser->setUser($user);
                        $newProgramServiceUser->setProgram($program);
                        $newProgramServiceUser->setService($service);
                        $this->programServiceUserRepository->add($newProgramServiceUser);

                        $this->addFlashMessage(
                            $this->getLL('listhelpers.success.added-yourself', [
                                $service->getTitle(),
                                $program->getTitle(),
                            ]),
                            $this->getLL('listhelpers.success'),
                            ContextualFeedbackSeverity::OK
                        );
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

    public function detailAction(): ResponseInterface
    {
        $programUid = $this->request->getArgument('showUid');

        // Load program
        $program = $this->programRepository->findByUid($programUid);

        if ($this->settings['detail']['showPrevNext'] ?? false) {
            // Determine prev/next programs from list in session
            $uidlist = $this->sessionHandler->restoreFromSession()['uidlist'] ?? [];
            $key = array_search($programUid, $uidlist);
            if ($key !== false) {
                if ($uidlist[$key - 1]) {
                    $this->view->assign('prevUid', $this->programRepository->findByUid($uidlist[$key - 1]));
                }
                if ($uidlist[$key + 1]) {
                    $this->view->assign('nextUid', $this->programRepository->findByUid($uidlist[$key + 1]));
                }
            }
            $this->view->assign('uidlist', $uidlist);
        }

        $this->view->assign('detailItem', $program);

        return $this->htmlResponse();
    }

    public function listArchiveAction(): ResponseInterface
    {
        $fromdate = date('Ym' . '01');
        $todate = date('Ymt') . ' 23:59';
        if ($this->request->hasArgument('adate')) {
            $todate = $this->request->getArgument('adate');
            $fromdate = $todate . '01';
            $todate = date('YmtHi', strtotime($fromdate . ' 23:59'));
        }
        if ($this->settings['filter']) {
            $filter = $this->settings['filter'];
        }
        if ($this->request->hasArgument('catUid')) {
            $category = $this->request->getArgument('catUid');
            $filter['categories'] = $category;
        }
        if ($this->settings['list']['show'] == 'year') {
            $fromdate = date('Y', strtotime($fromdate)) . '0101';
            $todate = date('Y', strtotime($fromdate)) . '1231 23:59';
            $nextmonth = date('Ym', strtotime($fromdate . ' + 1 year'));
            $prevmonth = date('Ym', strtotime($fromdate . ' - 1 year'));
            $currmonth = date('Y', strtotime($fromdate));
            $thismonth = date('Ym', strtotime($fromdate));
        } else {
            $thismonth = date('Ym', strtotime($fromdate));
            $currmonth = date('Ym', strtotime($fromdate));
            $showmonth = date('Ymd', strtotime($fromdate));
            $nextmonth = date('Ym', strtotime($fromdate . ' + 1 month'));
            $prevmonth = date('Ym', strtotime($fromdate . ' - 1 month'));
        }
        $showmonth = date('Ymd', strtotime($fromdate));

        $programs = $this->programRepository->findWithinMonth(
            $filter,
            strtotime($fromdate),
            strtotime($todate),
            $this->settings['list']['greaternow']
        );
        $latest = $this->programRepository->findLatest();
        $oldest = $this->programRepository->findOldest();

        $this->view->assign('acurrmonth', $currmonth);
        $this->view->assign('athismonth', $thismonth);
        $this->view->assign('ashowmonth', strtotime($showmonth));
        $this->view->assign('aprevmonth', $prevmonth);
        $this->view->assign('anextmonth', $nextmonth);
        $this->view->assign('programs', $programs);
        $this->view->assign('alatest', $latest[0]);
        $this->view->assign('aoldest', $oldest[0]);
        $this->view->assign('atodate', $todate);
        $this->view->assign('now', time());
        $this->view->assign('categories', $this->categoryRepository->findAll());
        $this->view->assign('selectedCategory', $category ?? null);

        return $this->htmlResponse();
    }

    // TODO isn't that a lesser version of listArchiveAction?
    public function servicesAction(): ResponseInterface
    {
        $fromdate = date('Ym' . '01');
        $todate = date('Ymt') . ' 23:59';
        if ($this->request->hasArgument('date')) {
            $todate = $this->request->getArgument('date');
            $fromdate = $todate . '01';
            $todate = date('YmtHi', strtotime($fromdate . ' 23:59'));
        }
        if ($this->settings['filter']) {
            $filter = $this->settings['filter'];
        }
        if ($this->request->hasArgument('catUid')) {
            $category = $this->request->getArgument('catUid');
            $filter['categories'] = $category;
        }

        $thismonth =  date('Ym');
        $currmonth =  date('Ym', strtotime($fromdate));
        $showmonth =  date('Ymd', strtotime($fromdate));
        $nextmonth =  date('Ym', strtotime($fromdate . ' + 1 month'));
        $prevmonth =  date('Ym', strtotime($fromdate . ' - 1 month'));

        $programs = $this->programRepository->findWithinMonth(
            $filter,
            strtotime($fromdate),
            strtotime($todate)
        );
        $latest = $this->programRepository->findLatest();
        $oldest = $this->programRepository->findOldest();

        $this->view->assign('currmonth', $currmonth);
        $this->view->assign('thismonth', $thismonth);
        $this->view->assign('showmonth', strtotime($showmonth));
        $this->view->assign('prevmonth', $prevmonth);
        $this->view->assign('nextmonth', $nextmonth);
        $this->view->assign('programs', $programs);
        $this->view->assign('latest', $latest[0]);
        $this->view->assign('oldest', $oldest[0]);
        $this->view->assign('todate', $todate);
        $this->view->assign('now', time());
        $this->view->assign('categories', $this->categoryRepository->findAll());
        $this->view->assign('selectedCategory', $category ?? null);

        return $this->htmlResponse();
    }

    public function carouselAction(): ResponseInterface
    {
        $now = date('c');

        if ($this->settings['list']['tillmidnight']) {
            $now = 'today';
        }

        if ($this->settings['filter']) {
            $filter = $this->settings['filter'];
        }

        // Carousel is always with images!
        $filter['image'] = 1;

        $programs = $this->programRepository->findWithinMonth(
            $filter,
            0,
            0,
            $this->settings['list']['greaternow'],
            $now
        );

        $this->view->assign('programs', $programs);

        $template = $this->settings['carousel']['template'] ?? 'Bootstrap';
        $this->view->getRenderingContext()->setControllerAction('Club/Carousel' . $template);

        return $this->htmlResponse();
    }

    // TODO what's this action for?
    public function upcomingAction(): ResponseInterface
    {
        if ($this->settings['filter']) {
            $filter = $this->settings['filter'];
        }
        $programs = $this->programRepository->findUpcoming($filter);
        $this->view->assign('detailItem', $programs[0]);
        return $this->htmlResponse();
    }

    public function listHighlightsAction(): ResponseInterface
    {
        if ($this->settings['filter']) {
            $filter = $this->settings['filter'];
        }
        $programs = $this->programRepository->findUpcoming($filter);
        $this->view->assign('programs', $programs);
        return $this->htmlResponse();
    }

    public function previewAction(): ResponseInterface
    {
        $now = date('c');
        if ($this->settings['list']['tillmidnight']) {
            $now = 'today';
        }
        if ($this->settings['filter']) {
            $filter = $this->settings['filter'];
        }

        $filter['hide_avoild_nl'] = true;

        $program = $this->programRepository->findWithinMonth($filter, 0, 0, $this->settings['list']['greaternow'], $now);

        $this->view->assign('programs', $program);

        return $this->htmlResponse();
    }

    public function listPressAction(): ResponseInterface
    {
        $now = date('c');
        if ($this->settings['list']['tillmidnight']) {
            $now = 'today';
        }
        if ($this->settings['filter']) {
            $filter = $this->settings['filter'];
        }
        $fromdate = 0;
        if ($this->settings['list']['fromdate']) {
            $fromdate = $this->settings['list']['fromdate'];
        }
        $todate = 0;
        if ($this->settings['list']['todate']) {
            $todate = $this->settings['list']['todate'];
        }
        if ($todate) {
            $todate += 23 * 3600 + 59 * 60;
        }
        $greaternow = $this->settings['list']['greaternow'];
        if ($fromdate) {
            $greaternow = 0;
        }

        // Press is always with images!
        $filter['image'] = 1;

        $programs = $this->programRepository->findWithinMonth(
            $filter,
            $fromdate,
            $todate,
            $greaternow,
            $now
        );

        $this->view->assign('programs', $programs);

        return $this->htmlResponse();
    }

    private function getLL($key, $arguments = []): string
    {
        $label = LocalizationUtility::translate($key, 'clubdata', $arguments);
        return $label ?? $key;
    }
}
