<?php
$contents .="
<section id='feuture-posts' class='mjr-pt-20 animate-in' data-anim-type='fade-in-up' data-anim-delay='400'>
	<div class='row'>";
	if(isset($articles[1]))
	{
	foreach($articles[1] as $feuture_articles)
	{
	$contents .= "
		<div class='col-sm-4 text-right mjr-mb-20'>
			<div class='card w-100 h-100 bg-gray-8  mjr-hover-item rounded-lg position-relative overflow-hidden text-white  vc_custom_1600440707546  text-right w-100 d-inline-block'>
				<img src='".$feuture_articles['post_image']."' class='card-img mjr-bg-image mjr-img-scale h-100 mjr-opacity-3 mjr-hover-opacity-6' alt='".$feuture_articles['title']."'>
				<a href='".$feuture_articles['link']."' target='_blank' class='card-img-overlay2 d-inline-block w-100 mjr-img-overlay mjr-p-20 d-flex align-items-end' style='min-height:400px;'>
					<div>
						<h4 class='card-title font-weight-bold text-white mjr-my-10'>".$feuture_articles['title']."</h4>
						<div class='text-body-default'>
							<i class='fa fa-calendar'></i>&nbsp;".$feuture_articles['time']."
						</div>
					</div>
				</a>
			</div>
		</div> ";
	}
	}
$contents .= "</div>
</section>";
