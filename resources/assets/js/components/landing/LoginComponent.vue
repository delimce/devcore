<template>
	<div class="modal" id="loginModal">
			<div class="modal-background"></div>
			<div class="modal-content">
					<div class="box">
							<p class="subtitle is-4 has-text-grey">Please login to proceed.</p><br>
							<form>
							  <div class="field">
								<p class="control has-icons-left has-icons-right">
								  <input class="input is-medium" type="email" placeholder="Email">
								  <span class="icon is-medium is-left">
									<svg class="svg-inline--fa fa-envelope fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="envelope" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"></path></svg><!-- <i class="fas fa-envelope"></i> -->
								  </span>
								  <span class="icon is-medium is-right">
									<svg class="svg-inline--fa fa-check fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg><!-- <i class="fas fa-check"></i> -->
								  </span>
								</p>
							  </div>
				
							  <div class="field">
								<p class="control has-icons-left">
								  <input class="input is-medium" type="password" placeholder="Password">
								  <span class="icon is-small is-left">
									<svg class="svg-inline--fa fa-lock fa-w-14" aria-hidden="true" data-prefix="fas" data-icon="lock" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M400 224h-24v-72C376 68.2 307.8 0 224 0S72 68.2 72 152v72H48c-26.5 0-48 21.5-48 48v192c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V272c0-26.5-21.5-48-48-48zm-104 0H152v-72c0-39.7 32.3-72 72-72s72 32.3 72 72v72z"></path></svg><!-- <i class="fas fa-lock"></i> -->
								  </span>
								</p>
							  </div>
							  <div class="field">
								<label class="checkbox">
								  <input type="checkbox">
								  Remember me
								</label>
							  </div>
							  <button class="button is-block is-info is-large is-fullwidth">Login</button><br>
							  </form>
						  </div>
			</div>
			<button class="modal-close is-large" aria-label="close"></button>
	</div>
</template>