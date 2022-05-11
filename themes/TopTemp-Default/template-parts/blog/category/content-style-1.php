<?php
$content .= "
<article id='post-" . $article_info['sid'] . "' class='pdrow w-100 h-100 align-self-stretch mjr-pb-20 d-block position-relative animate-in' data-anim-type='fade-in-up' data-anim-delay='400'>
    <div class='d-inline-block position-relative w-100'>
        <div class='mjr-content-animate card bg-white fly-sm2 rounded-xl shadow-sm shadow-hover-sm fly-sm mjr-post-meta-element mjr-post-meta-right-img overflow-hidden row no-gutters flex-column flex-md-row flex-md-row-reverse'>
			<div class='flex-column col-md-6'>
				<div class='card rounded-0 rounded-t-xl mjr-hover-item overflow-hidden  text-white2 h-100'>
					<img width='1200' height='800' src='$post_imge' class='img-fluid mjr-img-scale mjr-fit-cover2 rounded-0 card-img-top' alt='' style='max-height: 450px;min-height:100%;width:100%;object-fit:cover;'>
					<div class='card-img-overlay h-100 d-flex flex-column justify-content-end'>
						<div class='overflow-hidden2'>
							<div class='d-flex align-items-end w-100'>
								<div class='entry-meta mjr-fade-in d-flex align-items-center w-100'>
									<div class='flex-fill text-right'>
										<a href='' class='text-heading-default font-weight-bold' data-toggle='tooltip' data-placement='right' data-original-title='"._BY."" . $article_info['aid'] . " '>
											<img class='mjr_blog_md_avatar shadow' src='$user_avatar' alt=''></a>
									</div>
									<div class='flex-fill2 text-right pl-2'>
										<a href='" . $article_info['article_link'] . "' class='d-inline-block2 position-relative bg-white shadow-sm mjr-py-102 mjr-px-15 text-xs rounded-xl mjr-blog-badge-box d-flex align-items-center text-body-default'>
											<span class='mjr-pr-5 comments-icon'><i class='fa fa-comment'></i></span>
											<span class='align-middle font-weight-bold'>" . $article_info['comments'] . "</span>
										</a>
									</div>
									<div class='text-right text-sm'>
										<a href='" . $article_info['article_link'] . "' class='position-relative bg-white shadow-sm mjr-py-102 mjr-px-15 text-xs rounded-xl mjr-blog-badge-box d-flex align-items-center'>
											<span class='d-flex align-items-center justify-content-center text-right text-xs text-body-default'>
												<span class='counter-icon mjr-pl-5'><i class='fa fa-eye'></i></span>
												<span class='align-middle font-weight-bold'>" . $article_info['counter'] . "</span>
											</span>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class='card-body d-flex align-content-between flex-wrap col-md-6 mjr-p-20 py-md-0'>
				<div class='d-flex align-items-start'>
					<div>
						".((!empty($cats_name)) ? implode(" ", $cats_name):"")."
						<h4 class='entry-title mjr-py-10 font-weight-bold '>
							<a class='text-heading-default' href='" . $article_info['article_link'] . "' rel='bookmark'> " . $article_info['title'] . "</a>
						</h4>
						<div class='mjr-pb-20 text-break text-body-default'>
						 " . limit_words($article_info['hometext'], 20) . "
						</div>
					</div>
				</div>
				<div class='d-flex align-items-end w-100'>
					<div class='w-100'>
						<div class='d-inline-block w-100 position-relative mjr-pt-5 mt-md-1'>
							<div class='text-right d-flex w-100' style='line-height:0;'>
								<div class='text-left'>
									<a class='mb-0 d-inline-block2 d-flex align-items-center text-xs text-body-default' href='" . $article_info['article_link'] . "'> <i class='fa fa-calendar'></i> &nbsp; " . $article_info['datetime'] . "</a>
								</div>
								<div class='flex-fill gw-left'>
									<a href='" . $article_info['article_link'] . "' class='btn btn-sm p-0 btn-link text-body-default font-weight-bold mjr-hover-item'>
										<span class='align-bottom'>" . _ARTICLE_MORE . "</span>
										<span class='align-middle mjr-hover-left'><i class='fa fa-chevron-left px-2'></i></span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
</article>";
?>
