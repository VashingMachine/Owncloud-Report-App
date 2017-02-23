<?php
/**
 * ownCloud - report
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Dariusz Kwiatkowski <ktoztam@gmail.com>
 * @copyright Dariusz Kwiatkowski 2017
 */

namespace OCA\Report\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\AppFramework\Controller;
use OCA\Report\Service\ReportService;
use OC\URLGenerator;


class PageController extends Controller {

	private $userId;
	private $service;
	private $urlGenerator;
	
	public function __construct($AppName, IRequest $request, ReportService $service,
								URLGenerator $urlGenerator, $UserId){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->service = $service;
		$this->urlGenerator = $urlGenerator;
	}

	/**
	 * CAUTION: the @Stuff turns off security checks; for this page no admin is
	 *          required and no CSRF check. If you don't know what CSRF is, read
	 *          it up in the docs or you might create a security hole. This is
	 *          basically the only required method to add this exemption, don't
	 *          add it to any other method if you don't exactly know what it does
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @param int $page
	 */
	public function index() {
		return new RedirectResponse($this->urlGenerator->getAbsoluteURL('/index.php/apps/report/page/1'));
	}
	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @param int $id
	 */
	public function indexPage($id){
		$paging = 100;
		
		$files = $this->service->generateReport();
		$totalFiles = count($files);
		
		if(($id - 1) * $paging > $totalFiles){
			return new TemplateResponse('report', 'notfound');
		}
		
		$totalPages = ceil($totalFiles / $paging);
		$filesByPage = array_slice($files, ($id - 1) * $paging, $paging);
		
		$startIndex = ($id - 1) * $paging + 1;
	
		$endIndex = $startIndex + $paging - 1;
		if($endIndex > $totalFiles){
			$endIndex = $totalFiles;
		}
		$groups = $this->service->getUsersByGroups();
		
		$params = [
				'data' => $filesByPage,
				'groups' => $groups,
				'current_page' => $id,
				'possible_pages' => $totalPages,
				'total_files' => $totalFiles,
				'paging' => $paging,
				'start_index' => $startIndex,
				'end_index' => $endIndex
		];
		
		return new TemplateResponse('report', 'main', $params);  
	}
	
	

}