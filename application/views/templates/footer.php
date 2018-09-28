<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.
	<?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
</p>

<!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
<?= script_tag('/assets/js/jQuery-3.3.1.js'); ?>
<?= script_tag('/assets/js/bootstrap.min.js'); ?>
<?= script_tag('/assets/js/main.js'); ?>

</body>

</html>
