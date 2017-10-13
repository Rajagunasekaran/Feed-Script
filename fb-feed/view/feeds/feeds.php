<!-- <div class="feeddiv">
	<div class="parentdiv">
		<?php //$this->load->view('admin/feeds/activities'); ?>
	</div>
</div> -->
<!-- feed content -->

<!-- <a href="javascript:void(0);" id="get_post_istantlly" onclick="get_post_istantlly('internal',1,447);">comments</a>-->



<div class="feeddiv">
	<div class="parentdiv">
		<?php echo form_open_multipart(admin_url('feeds/feed_upload'),array('id'=>'feed_form','class'=>'hide')); ?>
		<div class="feed-dzone hide">
			<button type="button" class="btn feed-attach">
				<i class="fa fa-paperclip"></i>
			</button>
		</div>
		<input type="hidden" id="f_type" name="f_type" value=""/>
		<input type="hidden" id="noteid" name="noteid" value=""/>
		<input type="hidden" id="parentid" name="parentid" value=""/>
		<input type="hidden" id="commentid" name="commentid" value=""/>
		<input type="hidden" id="content" name="content" value=""/>

		<input type="hidden" id="action" value=""/>
		<?php echo form_close(); ?>
		<div id="feed-activity" class="feed-activity"></div>
	</div>
</div>
<div id="feed-lightbox" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<img src="" alt=""/>
			</div>
			<div class="modal-footer">
				<a class="btn btn-info download-attachments btn-icon" href="" target="_blank"><?php echo _l('internal_download_file'); ?></a>
			</div>
		</div>
	</div>
</div>