<?php $search_query = get_search_query(); ?>
<div id="search_block">
	<form method="get" class="search-form" action="<?php echo esc_url(home_url() . '/'); ?>">
		<input type="text" placeholder="<?php echo esc_attr(__('search for')); ?>" value="<?php echo esc_attr($search_query); ?>" name="s" class="text" />
		<?php /*<input type="submit" value="<?php echo esc_attr(__('search')); ?>" class="search-button" /> */ ?>
		<button class="search-button"><?php echo esc_attr(__('search')); ?></button>
	</form>
</div><!-- /search_block -->