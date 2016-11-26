<?php
/**
 * The template for displaying heroic knowledgebase single item
 */
global $ht_knowledge_base_options; ?>

<!-- #ht-kb -->
<div id="ht-kb" class="ht-kb-single">

<?php while ( have_posts() ) : the_post(); ?>
	
<?php if ( $ht_knowledge_base_options['breadcrumbs-display'] ): ?>
	<?php ht_kb_breadcrumb_display(); ?>
<?php endif; ?>

<?php if ( $ht_knowledge_base_options['search-display'] ): ?>
	<div id="ht-kb-search" class="clearfix">
		<?php ht_kb_display_search(); ?>
	</div>
<?php endif; ?>

	<?php
	//important - register page view
		ht_kb_set_post_views($post->ID); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemprop="blogPost" itemtype="http://schema.org/BlogPosting" itemscope="itemscope">
		<header class="entry-header">

			<?php //display_is_most_helpful_article(); ?>
			<?php //display_is_most_viewed_article(); ?>
			
			<?php if ( $ht_knowledge_base_options['meta-display'] ): ?>
				<?php //ht_kb_entry_meta_display(); ?>
			<?php endif; ?>

		<?php if ( has_post_thumbnail() ) { ?>
		<div class="entry-thumb">
			<a href="<?php the_permalink(); ?>" rel="nofollow">
				<?php the_post_thumbnail('post'); ?>
		    </a>
		</div>
		<?php } ?>
		    
		</header>

		<div class="entry-content clearfix">
		<?php if ( is_single() ) { ?>
			<?php the_content(); ?>
			
			<?php ht_kb_display_tags(); ?>

			<?php do_action('ht_kb_end_article'); ?>

			<?php include_once( 'ht-knowledge-base-author-template.php' ); ?>

			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Articles:', 'ht-knowledge-base' ), 'after' => '</div>' ) ); ?>
		<?php } else { ?>
			<?php the_excerpt(); ?>
		<?php }?>
		</div>

	</article>

	<?php ht_kb_display_attachments(); ?>

	<?php ht_kb_related_articles(); ?>

<?php endwhile; // end of the loop. ?>

</div>
<!-- /#ht-kb -->