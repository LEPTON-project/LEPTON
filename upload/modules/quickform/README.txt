HELP for Quickform addon

Captcha
	- See if captcha is enabled in the admintools/captcha & advanced spam protection
	- add following code to your template (for example) before submit button and you are done.

				<div class="{{ CAPTCHA_CLASS }} full">
					<label for="captcha"><span>avoid spam</span>
						<div class="grouping {{ CAPTCHA_ERROR }}">
							{{ CAPTCHA }}
						</div>
					</label>
				</div>			

	- if you want to add Google Recaptcha spam protection please read the documentation: https://doc.lepton-cms.org/docu/english/home/tutorials/how-to-use-google-recaptcha.php


If you have any questions referring to this addon please visit the forum http://forum.lepton-cms.org .
