var f_totalAttachments = 0,
	feedReplyDropzone, dz_url, cmt_url = '';
var data_alert = {
	updated: 'Updated successfully!!!',
	removed: 'Removed successfully!!!',
	commented: 'Commented successfully!!!',
	replied: 'Replied to comment successfully!!!',
	attached: 'Attachment upload successfully!!!',
	attachremoved: 'Attachment removed successfully!!!',
	liked: 'You liked this!!!',
	unliked: 'You unliked this!!!'
};
var message = 'updated';

$(document).ready(function() {
	$("html, body").animate({
		scrollTop: 0
	}, "slow");
	if ($('.dashboardtab a.populate_feeds').parent('li').hasClass('active')) {
		setTimeout(function() {
			Load_feeds();
		}, 500);
	}
	initFeedAttachmentDropZone();
});

function check_feeds_section(id){
	
}

function Load_feeds(lastdate, countpage, loadmore = false) {
	var call_method = 'get_feeds',
		call_data = {};
	if (loadmore) {
		var $selector = $('#feed-activity .maindiv.view_more');
		if (!$selector.length) {
			return false;
		}
		$selector.removeAttr('data-total data-fetched data-lastdate').removeClass('view_more');
		call_method = 'get_feeds_showmore';
		call_data = {
			last_feed_date: lastdate,
			countpage: countpage
		}
	}
	$('body').append('<div class="dt-loader"></div>');
	//alert(call_method+"---"+loadmore);
	$.post(admin_url + 'misc/' + call_method, call_data, function(response) {
		if (response != '' && $('#feed-activity').length > 0 && response.feedshtml != '') {
			if (!loadmore) {
				$('#feed-activity').html(response.feedshtml);
			} else {
				$('#feed-activity').append(response.feedshtml);
			}
			textarea_resize();
			$('.commentlisting .reply-comment-body .comment-time').contents().filter(function() {
				return this.nodeType === 3;
			}).remove();
			$('.commentsection [data-toggle="tooltip"]').tooltip({
				html: true,
				placement: 'auto top',
				container: $('#feed-activity')
			});
		}
		$('body').find('div.dt-loader').remove();
		init_popover();
	}, 'json');
	setTimeout(function() {
		set_notificationbarheight();
	}, 500);
}
jQuery(function($) {
	var totalfetchedcnt = loadmore = 1;
	$(document).on('scroll', function() {
		if ($('.dashboardtab a.populate_feeds').parent('li').hasClass('active')) {
			var strr = (parseInt($(window).height()) + parseInt($(window).scrollTop()));
			if (strr == $(document).height()) {
				var $selector = $('#feed-activity .maindiv.view_more'),
					total_notify = parseInt($selector.attr('data-total')),
					total_fetched = parseInt($selector.attr('data-fetched')),
					lastdate = parseInt($selector.attr('data-lastdate'));
					//alert(total_fetched+"<"+total_notify);
				if (total_fetched < total_notify) {
					if ($selector.length) {
						totalfetchedcnt++;
						Load_feeds(lastdate, totalfetchedcnt, loadmore);
					}
				}
			}
		}
	});
});

$('.dashboardtab a.populate_feeds').on('show.bs.tab', function() {
	setTimeout(function() {
		Load_feeds();
	}, 500);
	set_notificationbarheight();
});

function CommentOnPost(selector) {
	$(selector).closest('.maindiv').find('.feed_comment textarea').focus();
}

$(document).on('click', '[data-target="#feed-lightbox"]', function(event) {
	var $lightbox = $('#feed-lightbox');
	var $img = $(this).find('img'),
		src = $img.attr('src'),
		alt = $img.attr('alt'),
		attachment_id = $img.attr('data-attachment-id'),
		folderid = $img.attr('data-folder-id'),
		css = {
			'maxWidth': $(window).width() - 100,
			'maxHeight': $(window).height() - 100
		};
	var d_url = site_url + 'download/file/' + folderid + '/' + attachment_id;
	$lightbox.find('.download-attachments').attr('href', d_url).end()
		.find('img').attr({
			'src': src,
			'alt': alt
		}).css(css);
	var $limg = $lightbox.find('img');
	setTimeout(function() {
		$lightbox.find('.modal-dialog').css({
			'width': $limg.width() < 148 ? 148 : $limg.width() + 50, // $limg.width()+50
			'text-align': 'center'
		}, 300);
	});
	$lightbox.find('.close').removeClass('hidden');
});
$(document).on('click', '.toggle-view', function() {
	if ($(this).hasClass('less')) {
		$(this).removeClass('less').html('See all');
	} else {
		$(this).addClass('less').html('See less');
	}
	$(this).parent('.see-all-less').prev().find(".hide-item").toggle('fast');
	return false;
});

function initFeedAttachmentDropZone() {
	Dropzone.autoDiscover = false;
	if ( $( "#feed_form" ).length ) {
		feedReplyDropzone = new Dropzone("#feed_form", {
			clickable: '.feed-attach',
			autoProcessQueue: false,
			addRemoveLinks: true,
			parallelUploads: newsfeed_maximum_files_upload,
			maxFiles: newsfeed_maximum_files_upload,
			maxFilesize: newsfeed_maximum_file_size,
			acceptedFiles: newsfeed_upload_file_extensions,
		});
		// On post added success
		feedReplyDropzone.on('success', function(files, response) {
			f_totalAttachments--;
			if (f_totalAttachments == 0) {
				response = $.parseJSON(response);
				feedReplyDropzone.removeAllFiles();
			}
			// alert_float('success', data_alert.attached);
			Load_feeds();
		});
		// When drag finished
		feedReplyDropzone.on("dragover", function(file) {
			if ($('#feed_form').length > 0) {
				$('#feed_form').addClass('dropzone-active');
			}
		});
		feedReplyDropzone.on("drop", function(file) {
			if ($('#feed_form').length > 0) {
				$('#feed_form').removeClass('dropzone-active');
			}
		});
		// On error files decrement total files
		feedReplyDropzone.on("error", function(file) {
			console.log('upload error');
			f_totalAttachments--;
		});
		// When user click on remove files decrement total files
		feedReplyDropzone.on('removedfile', function(file) {
			f_totalAttachments--;
		});
		// On added new file increment total files variable
		feedReplyDropzone.on("addedfile", function(file) {
			console.log('file added');
			// Refresh total files to zero if no files are found becuase removedFile goes to --;
			if (this.getQueuedFiles().length == 0) {
				f_totalAttachments = 0;
			}
			f_totalAttachments++;
			$('#feed_form').submit();
		});
		feedReplyDropzone.on("sending", function(file, xhr, formData) {
			console.log('file sending')
				// Will send the filesize along with the file as POST data.
			formData.append("type", 'feed-reply-attach');
			var ftype = $('#feed_form #f_type').val();
			if (ftype == 'lead-attach') {
				formData.append("post_lead", $('#feed_form #parentid').val());
			} else if (ftype == 'client-attach') {
				formData.append("post_client", $('#feed_form #parentid').val());
			} else if (ftype == 'internal-attach') {
				formData.append("post_group", $('#feed_form #parentid').val());
			}
			formData.append("post_id", $('#feed_form #noteid').val());
			formData.append("post_comment", $('#feed_form #commentid').val());
		});
	}
}
$(document).on('click', '.feed-reply-attach', function(ev) {
	setCommentVarsByType($(this));
	$('.feed-dzone .feed-attach').trigger('click');
});

$(document).on('keydown', '.feed_comment textarea.reply-post', function(event) {
	setCommentVarsByType($(this));
	if (event.keyCode == 13 && !event.shiftKey && !event.ctrlKey) {
		if ($(this).val().trim() != '') {
			$(this).attr('disabled', 'disabled').addClass('reply-disabled');
			$('#feed_form').submit();
		}
	} else if (event.keyCode == 27) {
		$('.feed_comment textarea.reply-post').css('height', 'auto').val('');
		$(this).val('').attr('data-feed-edit', 'false');
		$(this).closest('.feed_comment').find('.feed-reply-attach').attr('data-feed-edit', 'false').end()
			.find('.escape-to-cancel').remove();
		var oldreply_val = $(this).closest('.commentsection').find('.comment-content.editing input.post-reply').val();
		$(this).closest('.commentsection').find('.comment-content.editing span').text(oldreply_val).end()
			.find('.comment-content.editing').removeClass('editing');
	}
});
$(document).on('keyup', '.feed_comment textarea.reply-post', function(event) {
	$(this).closest('.commentsection').find('.comment-content.editing span').text($(this).val());
});

function setCommentVarsByType(elem) {
	var to_note_id = $(elem).attr('data-noteid'),
		to_parent_id = $(elem).attr('data-parentid'),
		to_type = $(elem).attr('data-feed-type'),
		reply_edit = $(elem).attr('data-feed-edit'),
		reply_content = '';
	dz_url = admin_url + 'feeds/feed_upload'; // dummy url
	if (reply_edit == 'true') {
		$('#action').val('updated');
	} else {
		$('#action').val('replied');
	}
	if ($(elem).closest('.feed_comment').find('textarea.reply-post').length > 0) {
		reply_content = $(elem).closest('.feed_comment').find('textarea.reply-post').val();
	}
	if (to_type == 'lead-attach' || to_type == 'lead-reply') { //reply + attach
		if (reply_edit == 'true') {
			cmt_url = admin_url + 'leads/update_note_comment';
		} else {
			cmt_url = admin_url + 'leads/add_note_comment';
		}
		if (to_type == 'lead-attach') {
			dz_url = admin_url + 'leads/add_reply_comments_attachments';
		}
	} else if (to_type == 'client-attach' || to_type == 'client-reply') { //reply + attach
		if (reply_edit == 'true') {
			cmt_url = admin_url + 'clients/update_note_comment';
		} else {
			cmt_url = admin_url + 'clients/add_note_comment';
		}
		if (to_type == 'client-attach') {
			dz_url = admin_url + 'clients/add_reply_comments_attachments';
		}
	} else if (to_type == 'internal-attach' || to_type == 'internal-reply') { //reply + attach
		if (reply_edit == 'true') {
			cmt_url = admin_url + 'internal/update_reply_comments';
		} else {
			cmt_url = admin_url + 'internal/add_comment';
		}
		if (to_type == 'internal-attach') {
			dz_url = admin_url + 'internal/add_reply_comments_attachments';
		}
	} else if (to_type == 'task-reply') { //comment
		if (reply_edit == 'true') {
			cmt_url = admin_url + 'tasks/edit_comment';
		} else {
			cmt_url = admin_url + 'tasks/add_task_comment';
			$('#action').val('commented');
		}
	} else if (to_type == 'ticket-reply') { //comment
		if (reply_edit == 'true') {
			cmt_url = admin_url + 'tickets/edit_ticket_note';
		} else {
			cmt_url = admin_url + 'tickets/add_ticket_note';
			$('#action').val('commented');
		}
	}
	$('#feed_form #f_type').val(to_type);
	$('#feed_form #noteid').val(to_note_id);
	$('#feed_form #parentid').val(to_parent_id);
	$('#feed_form #content').val(reply_content);
}
$(document).on('submit', '#feed_form', function(e) {
	e.preventDefault();
	var form = $(this);
	var data = $(form).serialize();
	$.post(cmt_url, data).success(function(response) {
		response = $.parseJSON(response);
		if (response.success) {
			if (response.comment_id !== undefined && response.comment_id != '') {
				$('#feed_form #commentid').val(response.comment_id);
			}
			var type = $('#feed_form #f_type').val();
			console.log(type, dz_url, $('#feed_form #commentid').val())
			if (feedReplyDropzone.getQueuedFiles().length > 0 && $('#feed_form #commentid').val() != '' && (type == 'lead-attach' || type == 'client-attach' || type == 'internal-attach')) {
				feedReplyDropzone.options.url = dz_url;
				feedReplyDropzone.processQueue();
				return;
			}
			if ($('#action').val() != '') var message = $('#action').val();
			// alert_float('success', data_alert[message]);
		}
		Load_feeds();
	});
	return false;
});
// Delete reply comment
function remove_feed_reply(id, noteid, selector) {
	if (!confirm('Are you sure you want to remove this comment?')) {
		return false;
	}
	var removefrom = $(selector).attr('data-feed-type');
	if (removefrom == 'lead-reply') {
		var remove_url = admin_url + 'leads/remove_note_comment/' + id + '/' + noteid;
	} else if (removefrom == 'client-reply') {
		var remove_url = admin_url + 'clients/remove_note_comment/' + id + '/' + noteid;
	} else if (removefrom == 'internal-reply') {
		var remove_url = admin_url + 'internal/remove_note_comment/' + id + '/' + noteid;
	} else if (removefrom == 'task-reply') {
		var remove_url = admin_url + 'tasks/remove_comment/' + id;
	} else if (removefrom == 'ticket-reply') {
		var remove_url = admin_url + 'tickets/remove_note/' + id;
	}
	$.get(remove_url, function(response) {
		if (response.success == true) {
			$(selector).closest('.post_comments_wrapper').remove();
			// alert_float('success', data_alert.removed);
		}
	}, 'json');
}
// Delete attachments
function remove_attach(selector) {
	if (!confirm('Are you sure you want to remove this attachment?')) {
		return false;
	}
	var remove_from = $(selector).attr('data-feed-type');
	var attach_id = $(selector).attr('data-attach-id');
	if (remove_from == 'lead-attach') {
		var remove_attch_url = admin_url + 'leads/delete_attachment/' + attach_id;
	} else if (remove_from == 'client-attach') {
		var remove_attch_url = admin_url + 'clients/delete_attachment/' + attach_id;
	} else if (remove_from == 'internal-attach') {
		var remove_attch_url = admin_url + 'internal/delete_attachment/' + attach_id;
	}
	$.post(remove_attch_url, function(response) {
		if (response.success == true) {
			$(selector).closest('.post-image-wrapper').remove();
			// alert_float('success', data_alert.attachremoved);
		}
	}, 'json');
}
// Edit reply comment
function edit_feed_reply(id, selector) {
	var reply_content = $(selector).closest('.note-comment').find('input.post-reply').val();
	$(selector).closest('.commentsection').find('textarea.reply-post').val(br2nl(reply_content)).attr('data-feed-edit', 'true').focus().end()
		.find('.feed-reply-attach').attr('data-feed-edit', 'true').end()
		.find('.comment-content.editing').removeClass('editing').end()
		.find('.feed_comment').find('.escape-to-cancel').remove().end().append('<span class="text-muted escape-to-cancel">Escape to cancel</span>');
	$(selector).closest('.note-comment').find('.comment-content').addClass('editing');
	$('#feed_form #commentid').val(id);
}
// Like and Unlike posts
function like_feed_post(selector, noteid, parentid, status) {
	var likefrom = $(selector).attr('data-feed-type');
	var objdata = {
		'group_id': parentid,
		'status': status,
	};
	if (likefrom == 'lead-like') {
		var like_url = admin_url + 'leads/like_unlike_notes/' + noteid;
	} else if (likefrom == 'client-like') {
		var like_url = admin_url + 'clients/like_Unlike_Note/' + noteid;
	} else if (likefrom == 'internal-like') {
		var like_url = admin_url + 'internal/add_like_to_note/' + noteid;
	}
	$.post(like_url, objdata, function(response) {
		if (response.status == true) { console.log(response.status );
			$(selector).removeAttr('onclick');
			if (status == 1) {
				$(selector).html('liked');
				$(selector).removeClass('unliked').addClass('liked');
				$(selector).attr('onclick', 'like_feed_post(this,' + noteid + ',' + parentid + ',0)');
				$('#action').val('liked');
			} else {
				$(selector).html('unlike');
				$(selector).removeClass('liked').addClass('unliked');
				$(selector).attr('onclick', 'like_feed_post(this,' + noteid + ',' + parentid + ',1)');
				(selector).html('like_btn_html');
				$('#action').val('unliked');
			}
			if ($('#action').val() != '') var message = $('#action').val();
			// alert_float(response.alert_type, data_alert[message]);
			Load_feeds();
		}
	}, 'json');
}
// Like and Unlike comments
function like_feed_reply(selector, commentid, noteid, parentid, status) {
	var likefrom = $(selector).attr('data-feed-type');
	var objdata = {
		'note_id': noteid,
		'group_id': parentid,
		'status': status
	};
	if (likefrom == 'lead-reply-like') {
		var like_url = admin_url + 'leads/add_like_to_note_comment/' + commentid;
	} else if (likefrom == 'client-reply-like') {
		var like_url = admin_url + 'clients/add_like_to_note_comment/' + commentid;
	} else if (likefrom == 'internal-reply-like') {
		var like_url = admin_url + 'internal/add_like_to_note_comment/' + commentid;
	}
	$.post(like_url, objdata, function(response) {
		if (response.status == true) { console.log("status"+response.status);
			$(selector).removeAttr('onclick');
			if (status == 1) {
				$(selector).removeClass('unliked').addClass('liked').find('em').text('Liked');
				$(selector).attr('onclick', 'like_ifeed_reply(this,' + commentid + ',' + noteid + ',' + parentid + ',0)');
				$('#action').val('liked');
			} else {
				$(selector).removeClass('liked').addClass('unliked').find('em').text('Unlike');
				$(selector).attr('onclick', 'like_ifeed_reply(this,' + commentid + ',' + noteid + ',' + parentid + ',1)');
				$('#action').val('unliked');
			}
			if ($('#action').val() != '') var message = $('#action').val();
			// alert_float(response.alert_type, data_alert[message]);
			Load_feeds();
		}
	}, 'json');
}

// load more replies for post comment
function view_more_note_comments(selector) { //return false;
	$(selector).append('<span class="preload"></span>');
	
	var main_id = $(selector).attr('data-parentid');
	var noteid = $(selector).attr('data-noteid');
	var page = $(selector).find('input[name="page"]').val();
	var total_pages = $(selector).attr('data-total-pages');
	var type = $(selector).attr('data-feed-type');
	var objdata = {
		main_id: main_id,
		noteid: noteid,
		page: page,
		f_type: type
	};
	if (page <= total_pages) {
		$.post(admin_url + 'misc/get_more_replies/', objdata, function(response) {
			if (response.feedshtml !== undefined) {
				$(selector).data('track-load-comments', page);
				$(selector).parent().before(response.feedshtml);
				page++;
				$(selector).find('input[name="page"]').val(page);
				if (page >= total_pages - 1) {
					$(selector).parent().addClass('hide');
				}
			}
			setTimeout(function() {
				$(selector).find('.preload').remove();
			}, 200);
		}, 'json');
	}
	$(selector).parent().next('.write-comment').removeClass('hide');
}

function mark_feed_as_read(feedid, selector) {
	var rNotiObj = $('#rightnotify .notification-box#rnoti_' + feedid);
	var setstatus = 1,
		undostatus = 0,
		objdata = {
			status: setstatus
		};
	setTimeout(function() {
		$(selector).removeClass('unread');
	}, 2000);
	$.post(admin_url + 'misc/set_notification_status/' + feedid, objdata, function(response) {
		if (response.success && response.statusdetails) {
			console.log(response.statusdetails);
			if (response.statusdetails.isread != undefined && response.statusdetails.isread != setstatus) {
				undostatus = 1;
			}
		} else undostatus = 1;
		if (undostatus) {
			$(selector).addClass('unread');
		} else {
			rNotiObj.find('.notify-read-status').prop('checked', true);
			if (rNotiObj.find('.notify-read-status').is(":checked")) {
				rNotiObj.removeClass('unread');
			} else {
				rNotiObj.addClass('unread');
			}
		}
		load_unread_notifications();
	}, 'json');
}
var cnt =0;
$(document).on('touchstart mouseenter', '#feed-activity .maindiv.unread', function(e) {
	var notify_id = $(this).attr('data-feedid');
	console.log("---"+ notify_id+"--ss--" );
	mark_feed_as_read(notify_id, this);
});

function refresh_node(feedid) {
	call_data = {
		action: 'refresh_node',
		feed_id: feedid
	}
	$('body').append('<div class="dt-loader"></div>');
	$.post(admin_url + 'misc/get_feeds', call_data, function(response) {
		if (response != '' && $('#feed-activity').length > 0 && response.feedshtml != '') {
			$('#feed-activity .maindiv[data-feedid=' + feedid + ']').replaceWith(response.feedshtml);
			textarea_resize();
			$('.commentlisting .reply-comment-body .comment-time').contents().filter(function() {
				return this.nodeType === 3;
			}).remove();
			$('.commentsection [data-toggle="tooltip"]').tooltip({
				html: true,
				placement: 'auto top',
				container: $('#feed-activity')
			});
		}
		$('body').find('div.dt-loader').remove();
	}, 'json');
	setTimeout(function() {
		set_notificationbarheight();
	}, 500);
}
$(document).scroll(function() {
    var Wscroll = $(this).scrollTop();
    var temp_notify_id = 0;
    $('div.maindiv.unread').each(function(){
			//console.log("---"+$(this).attr('class')+"--class--");
			var ThisOffset = $(this).closest('div').offset();
            if(Wscroll > ThisOffset.top &&  Wscroll < ThisOffset.top  + $(this).closest('div').outerHeight(true)){
			if($(this).hasClass("unread")){
			    var notify_id = $(this).attr('data-feedid');
				if(notify_id  != temp_notify_id){
				temp_notify_id = notify_id;	
				console.log("---"+ notify_id +"--scroll--");
				mark_feed_as_read(notify_id, this);
				$(this).removeClass("unread")
				return false; // stops the iteration after the first one on screen
				}
			
			}
		}
    });
}); 