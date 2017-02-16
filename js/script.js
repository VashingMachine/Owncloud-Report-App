/**
 * ownCloud - report
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Dariusz Kwiatkowski <ktoztam@gmail.com>
 * @copyright Dariusz Kwiatkowski 2017
 */

(function ($, OC) {

	$(document).ready(function () {
		
		$('.toggle-triger').click(function() {
			$(this).next('ol.toggle').slideToggle();
		});
	});

})(jQuery, OC);