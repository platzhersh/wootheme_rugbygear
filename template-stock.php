<?php
/*
Template Name: Stock Report
Source: http://mikejolley.com/2011/12/woocommerce-output-a-simple-printable-stockinventory-report/
modified by @platzh1rsch, 07-28-2014
*/
if (!is_user_logged_in() || !current_user_can('manage_options')) wp_die('This page is private.');
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php _e('Stock Report'); ?></title>
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<!--  Mobile viewport scale | Disable user zooming as the layout is optimised -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	
	<link rel="stylesheet" type="text/css" href="/wp-content/themes/mystile/includes/bootstrap-3.2.0-dist/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="/wp-content/themes/mystile/includes/css/footable.core.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="/wp-content/themes/mystile/includes/js/footable.all.min.js"></script>



</head>
<body>
<div class="container">
		<header>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<h1 class="title"><?php the_title(); ?></h1>
			<?php the_content(); ?>
		<?php endwhile; endif; ?>
	</header>
	<section>
		<?php
		
		global $woocommerce;
		?>

		<div class="input-group"><span class="input-group-addon">Search:</span> <input id="filter" type="text" class="form-control"></input></div>
		<table class=" table footable" data-page-size=100 data-filter=#filter>
			<thead>
				<tr>
					<th data-sort-initial="true"><?php _e('Product', 'woothemes'); ?></th>
					<th data-hide="all"><?php _e('Image', 'woothemes'); ?></th>
					<!--<th scope="col" style="text-align:left;"><?php _e('Attr.', 'woothemes'); ?></th>-->
					<th data-hide="phone,tablet"><?php _e('SKU', 'woothemes'); ?></th>
					<th data-hide="phone,tablet" data-type="numeric"><?php _e('Price', 'woothemes'); ?> (CHF)</th>
					<th data-type="numeric"><?php _e('Stock', 'woothemes'); ?></th>
				</tr>
			</thead>
			<tbody>
				<!-- Simple Products -->
				<?php
				
				$args = array(
					'post_type'	=> 'product',
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'orderby'	=> 'title',
					'order'	=> 'ASC',
					'meta_query' => array(
						array(
							'key' => '_manage_stock',
							'value' => 'yes'
							)
						),
					'tax_query' => array(
						array(
							'taxonomy' => 'product_type',
							'field' => 'slug',
							'terms' => array('simple'),
							'operator' => 'IN'
							)
						)
					);
				$loop = new WP_Query( $args );
				while ( $loop->have_posts() ) : $loop->the_post();
				global $product;
				?>
				<tr>
					
					<td><?php echo $product->get_title(); ?></td>
					<td><?php echo $product->get_image(); ?></td>
					<!--<td></td>-->
					<td><?php echo $product->sku; ?></td>
					<td><?php echo $product->price; ?></td>
					<td><?php 
						$stock = $product->stock; 
						if ($stock > 2) print "<span class=\"label label-success\">$stock</span>";
						else if ($stock > 0) print "<span class=\"label label-warning\">$stock</span>";
						else if ($stock <= 0) print "<span class=\"label label-error\">$stock</span>";
					?></td>
				</tr>
				<?php
				endwhile;
				?>
				
				<!-- Variations -->
				<?php
				$args = array(
					'post_type'	=> 'product_variation',
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'orderby'	=> 'title',
					'order'	=> 'ASC',
					'meta_query' => array(
						array(
							'key' => '_stock',
							'value' => array('', false, null),
							'compare' => 'NOT IN'
							)
						)
					);
				$loop = new WP_Query( $args );
				while ( $loop->have_posts() ) : $loop->the_post();
				$product = new WC_Product_Variation( $loop->post->ID );
				?>
				<tr>
					<td><?php 
						echo $product->get_title(); 
						
						$attr = $product->get_variation_attributes();
						$attrlist = " (";
						foreach ($attr as $valuekey => $value) {
							$attrlist .= "$value,";
							
							//echo $value+",";
						} 
						$attrlist = substr($attrlist,0,-1);
						$attrlist .= ")";
						
						echo $attrlist;

					?></td>
					<td><?php echo $product->get_image(); ?></td>
					<!--<td><?php echo get_the_title( $loop->post->post_parent ); ?></td>-->
					<!--<td><?php 
						$attr = $product->get_variation_attributes();
						foreach ($attr as $valuekey => $value) {
							print "$valuekey : $value<br/>";
						} ?>
					</td>-->
					<td><?php echo $product->sku; ?></td>
					<td><?php echo $product->price; ?></td>
					<td><?php 
					$stock = $product->stock; 
						if ($stock > 2) print "<span class=\"label label-success\">$stock</span>";
						else if ($stock > 0) print "<span class=\"label label-warning\">$stock</span>";
						else if ($stock <= 0) print "<span class=\"label label-danger\">$stock</span>";
						?></td>
				</tr>
				<?php
				endwhile;
				?>
			</tbody>
		</table>

</div>

<!-- Activate Footable -->
<script type="text/javascript">
	$(function () {
		$('.footable').footable();
	});
</script>

	</body>
	</html>