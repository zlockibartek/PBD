<div class="row hidden-md-up postsGridWrap">
	<?php foreach ($content['content'] as $category) :
		$category['url'] = "index.php?" . $content['type'] . "=" . $category['pageId']; ?>
		<div class="col-md-<?= self::PERCOL ?> mb-2 mt-2">
			<div class="posts-card m-auto">
				<a href="<?= $category['url'] ?>">
					<div class="card-body p-2">
						<h5 class="card-title"> <?= $category['title'] ?></h5>
					</div>
				</a>
			</div>
		</div>
	<?php endforeach ?>
</div>