<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

@if(config('laravel-admin.footer.show_copyright', false))
	<!-- BEGIN: Footer-->
	<footer class="footer footer-static footer-light">
	    <p class="clearfix blue-grey lighten-2 mb-0">
	    	<span class="float-md-left d-block d-md-inline-block mt-25">
	    	{!! config('laravel-admin.footer.copyright_text', '') !!}
	    </p>
	</footer>
	<!-- END: Footer-->
@endif