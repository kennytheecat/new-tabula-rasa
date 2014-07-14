<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package tabula-rasa
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="inner-footer">
			<div class="site-info">
				<?php printf( __( 'Site Design by %1$s', 'tabula-rasa' ), 'tabula-rasa', '<a href="http://third-law.com/" rel="designer">Third Law Web Design</a>' ); ?>
			</div><!-- .site-info -->
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>