<div id="commentsBox">
	<form action="" method="POST">
		<div id="makeCommentBox">
			<label for="makeCommentText">Add comment</label>
			<input type="text" id="makeCommentText" name="commentText" required minlength="4" size="300">
			<div id="makeCommentFiles">
				<input type="file" id="commentFile1" name="commentFile" accept=".jpg,.jpeg,.png,.gif">
			</div>
			<input type="submit" id="makeCommentSubmit" value="Comment">
		</div>
	</form>
	<div id="commentsListBox">
		<ul id="commentList">
			<?php if ($content) : ?>
				<?php foreach ($content as $comment) : ?>
					<li>
						<div class="commentBox">
							<div class="commentCred">
								<p class="commentUsername"><?= $comment['eamil'] ?> </p>
								<p class="commentDate"><?= $comment['timestamp'] ?></p>
							</div>
							<p class="commentText"><?= $comment['text'] ?></p>
					</li>';
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
	</div>
</div>'