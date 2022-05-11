<?php
$footer_social = $toptemp_default_configs['footer_social'];
$col_style = ($footer_social == 1) ? "col-sm-9 text-right":"col-sm-12 text-center";
if((isset($toptemp_default_configs['last_aticles'])) && $toptemp_default_configs['last_aticles'] == 1)
{
$contents .= "
<section data-vc-full-width-init='true' class='custom_section custom_section_visible custom_1592351788916 custom_section-has-fill mjr-mt-20'>
	<div class='custom_row custom_row-fluid custom_row_visible custom_1590020409125'>
		<div class='text-right'>
			<div class='custom_column-inner'>
				<div class='mjr-element-div w-100 text-center'>
					<h6 class='d-inline-block mr-1'><span class='badge font-weight-bold bg-primary-light'>
						<span class='text-primary' style=''>"._OLD_ARTICLES."</span></span>
					</h6>
				</div>
				<div class='mb-3 text-center'>
					<h4 class='mb-32 mjr-sliding-headline font-weight-bold secondary-font mjr-ready'>
						<span class='slide-in-container'><span class='d-inline-block text-dark-opacity-8 animated'>
						"._OLD_ARTICLES_DESC."
						</span></span>
					</h4>
				</div>
				<div class='row mjr-m-15'>
					<div class='col bg-white2 rounded-lg2 custom_1600268255444 w-100 m-32 p-42 col-sm-auto d-md-flex align-items-center text-center text-sm-left justify-content-between2' style='z-index:11;'></div>";
					if(isset($articles[3]))
					{
					foreach($articles[3] as $last_articles)
					{
					$contents .= "
					<div class='col-md-6 col-lg-12 col-xl-4 animate-in' data-anim-type='fade-in-up' data-anim-delay='400'>
						<div class='mjr-card mjr-card--small shadow shadow-hover fly-sm overflow-hidden w-100 shadow-hover-sm2 shadow-sm2'>
							<div class='mjr-card__img'>
								<img src='".$last_articles['post_image']."' class='mjr-card__img-thumbnail' alt='Thumbnail'>
							</div>
							<div class='mjr-card__content'>
								<h5 class='mjr-card__content_header'>
								<a href='".$last_articles['link']."' class='text-dark mjr-title'>".$last_articles['title']."</a>
								</h5>
								<p class='mb-0 text-xs'><i class='fa fa-calendar'></i> ".$last_articles['time']."</p>
							</div>
						</div>
					</div>";
					}
					}
					$contents .= "
				</div>
			</div>
		</div>
	</div>
</section>";
}
$contents .= "
<footer id='mjr-page-footer' class='site-footer2 my-0 py-0'>
    <div class='container my-0 py-0'>
        <div class='row my-0 py-0'>
            <div class='col-12 my-0 py-0'>
                <section data-vc-full-width='true' data-vc-full-width-init='true'  style='position: relative; left: -381.5px; box-sizing: border-box; width: 1903px; padding-left: 381.5px; padding-right: 381.5px;'>
                    <div data-vc-full-width='true' data-vc-full-width-init='true' class='custom_row wpb_row custom_row-fluid custom_row_visible custom_1591439755435 custom_row-has-fill custom_row-o-content-middle custom_row-flex' style='position: relative; left: -381.5px; box-sizing: border-box; width: 1903px; padding-left: 381.5px; padding-right: 381.5px;'>
						<div class='wpb_column custom_column_container $col_style'>
							<div class='custom_column-inner'>
								<div class='wpb_wrapper'>

									<div class='slide-in-container w-100 custom_1589668055830'>
										<div class='d-inline-block'>
											<p class='m-10 text-body-default'><span class='d-inline-block pix-waiting animated' data-anim-delay='200' data-anim-type='fade-in-up'><i class='fa fa-code'></i> "._DSN." - ".POWERED_BY."</span></p>
											<p> "._COPYRIGHT_TITLE."</p>
										</div>
									</div>
								</div>

							</div>
						</div>";
						if($footer_social == 1){
						$contents .= "
						<div class='wpb_column custom_column_container col-sm-3 text-left'>
							<div class='custom_column-inner  custom_1589667876780'>
								<div class='wpb_wrapper'>
									<div class='text-body-default text-right pix-social-icons font-weight-bold d-inline-block' style='font-size:30px;'>";
									$instagram_link = (!empty($toptemp_default_configs['instagram'])) ? $toptemp_default_configs['instagram']:"";
									$whatsapp_link = (!empty($toptemp_default_configs['whatsapp'])) ? $toptemp_default_configs['whatsapp']:"";
									$telegram_link = (!empty($toptemp_default_configs['telegram'])) ? $toptemp_default_configs['telegram']:"";
									$facebook_link = (!empty($toptemp_default_configs['facebook'])) ? $toptemp_default_configs['facebook']:"";
									if($instagram_link)
									$contents .="
										<a href='$instagram_link' class='text-body-default d-inline-block fly-sm px-2 pix-waiting animated' data-anim-type='fade-in-up' data-anim-delay='400'> <i class='fa fa-instagram' style=''></i> </a>";
									if($whatsapp_link)
									$contents .="
										<a href='$whatsapp_link' class='text-body-default d-inline-block fly-sm px-2 pix-waiting animated' data-anim-type='fade-in-up' data-anim-delay='500'> <i class='fa fa-whatsapp' style=''></i> </a>";
									if($telegram_link)
									$contents .="
										<a href='$telegram_link' class='text-body-default d-inline-block fly-sm px-2 pix-waiting animated' data-anim-type='fade-in-up' data-anim-delay='600'> <i class='fa fa-telegram' style=''></i> </a> ";
									if($facebook_link)
									$contents .="
										<a href='$facebook_link' class='text-body-default d-inline-block fly-sm px-2 pix-waiting animated' data-anim-type='fade-in-up' data-anim-delay='700'> <i class='fa fa-facebook' style=''></i> </a>";
									$contents .="
									</div>
								</div>
							</div>
						</div>";
						}
					$contents .= "
					</div>
                    <div class='custom_row-full-width custom_clearfix'></div>
                </section>
                <div class='custom_row-full-width custom_clearfix'></div>
            </div>
        </div>
    </div>
</footer>";
?>