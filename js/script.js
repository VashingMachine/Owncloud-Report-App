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
		
		
		$('#hello').click(function () {
			alert('Hello from your script file. I must have called a thousand times');
		});
		
		$('.toggle-triger').click(function() {
			$(this).next('ol.toggle').slideToggle();
		});

		$('#echo').click(function () {
			var url = OC.generateUrl('/apps/report/echo');
			var data = {
				echo: $('#echo-content').val()
			};

			$.post(url, data).success(function (response) {
				$('#echo-result').text(response.echo);
			});

		});
	});

})(jQuery, OC);