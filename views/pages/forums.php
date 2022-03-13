<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card direct-chat direct-chat-warning">
                        <div class="card-body">
                            <div id="frame-chat" class="direct-chat-messages" style="overflow-y: auto;">
                                <?php if ($data['num_mess'] > 25) { ?>
                                    <div id="thisIsTop" style="text-decoration: underline; cursor: pointer;">
                                        <p class="text-center text-warning">View more...</p>
                                    </div>
                                <?php } ?>
                                <div id="appendTop"></div>
                                <!-- start loop -->
                                <?php foreach ($data['mess_forums'] as $value) { ?>
                                    <?php if($value['account_id'] == $_SESSION['message_userID']){ ?>
                                        <div class="direct-chat-msg right mt-4">
                                            <img class="direct-chat-img" src="<?php echo $value['avatar'] ?>" alt="message user image">
                                            <div class="direct-chat-text">
                                                <div class="float-right">
                                                    <button class="btn btn-sm nav-link text-muted" onclick="editComment(`<?php echo $value['id'] ?>`)" style="margin-top: -11px; margin-right: -17px;">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                                <p id="content-cmt-<?php echo $value['id'] ?>"><?php echo ConvertMessage($value['message']) ?></p>
                                                <hr style="margin: 0px; padding: 0px;">
                                                <span class="direct-chat-timestamp text-light"><?php echo $value['username'] ?></span> | 
                                                <span class="direct-chat-timestamp text-light"><?php echo ConvertDate($value['date_create']) ?></span>
                                                <?php if($value['edited'] == 1){ ?>
                                                | <span class="direct-chat-timestamp text-light">edited</span>
                                                <?php } ?>  
                                            </div>
                                        </div>
                                    <?php }else{ ?>
                                        <div class="direct-chat-msg mt-4">
                                            <img class="direct-chat-img" src="<?php echo $value['avatar'] ?>" alt="message user image">
                                            <div class="direct-chat-text">
                                                <?php echo ConvertMessage($value['message']) ?>
                                                <hr style="margin: 0px; padding: 0px;">
                                                <span class="direct-chat-timestamp text-light"><?php echo $value['username'] ?></span> | 
                                                <span class="direct-chat-timestamp text-light"><?php echo ConvertDate($value['date_create']) ?></span>  
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <!-- end loop -->
                                <div id="append"></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="input-group">
                                <textarea id="mymess" placeholder="Type comment ..." class="form-control" rows="1"></textarea>
                                <span class="input-group-append">
                                    <button type="button" id="btn-sent" class="btn btn-warning">Send</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="EditMessage" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticEditMess" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-uppercase" id="staticEditMess"><i class="fas fa-edit"></i> edit comment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <input type="text" id="id-comment" hidden>
            <div class="form-group">
                  <label>Message</label>
                  <textarea id="content-comment-s1" class="form-control" rows="4"></textarea>
              </div>
              <div class="form-group">
                  <label>Confirm password</label>
                  <input id="input-pass" type="password" class="form-control">
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="btn-save" type="button" class="btn btn-primary">Save now</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    var totalNumMess = parseInt(<?php echo $data['num_mess'] ?>);
    var totalPage = 1;
    var pages = 1;
    var froms = 0;
    const messages = document.getElementById('frame-chat');

    function appendMessage() {
        const message = document.getElementsByClassName('message')[0];
        const newMessage = message.cloneNode(true);
        messages.appendChild(newMessage);
    }

    function getMessages() {
        // Prior to getting your messages.
        shouldScroll = messages.scrollTop + messages.clientHeight === messages.scrollHeight;
        /*
         * Get your messages, we'll just simulate it by appending a new one syncronously.
         */
        appendMessage();
        // After getting your messages.
        if (!shouldScroll) {
            scrollToBottom();
        }
    }

    function scrollToBottom() {
        messages.scrollTop = messages.scrollHeight;
    }

    scrollToBottom();

    function editComment(id){
        var comment_ = $('#content-cmt-'+id).html();
        comment_ = replaceAll(comment_, "<br>", "\n");
        $('#id-comment').val(id);
        $('#content-comment-s1').val(comment_);
        $('#EditMessage').modal('show');
    }

    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function replaceAll(str, find, replace) {
        return str.replace(new RegExp(escapeRegExp(find), 'g'), replace);
    }

    $(document).ready(function() {
        var screen_height = window.innerHeight;

        if (screen_height < 700) {
            screen_height = screen_height - 57;
            $('#frame-chat').css('height', screen_height + 'px');
        } else {
            screen_height = screen_height - 57 - 57 - 250;
            $('#frame-chat').css('height', screen_height + 'px');
        }

        $('#btn-sent').click(function() {
            $('#btn-sent').attr("disabled", true);
            var my_mess = $('#mymess').val().trim();

            if(my_mess.length > 0){
                $.ajax('index.php?url=process-push-forums', {
                    type: 'POST',
                    data: {
                        mess: my_mess
                    },
                    success: function(data) {
                        $('#frame-chat').html(data);
                        $('#mymess').val('');
                        $('#mymess').css('height', 38);
                        scrollToBottom();
                        $('#btn-sent').attr("disabled", false);
                        $('#mymess').focus();

                        $.ajax('index.php?url=process-database-size', {
                            type: 'POST',
                            data: {},
                            success: function(data) {
                                $('#db-size').html(data);
                            }
                        });
                    }
                });
            }else{
                alert("Please enter content in the chat box");
                $('#btn-sent').attr("disabled", false);
                $('#mymess').focus();
            }
        });

        $('#mymess').keyup(function(e){
            if(e.keyCode == 13)
            {
                let text_box_height = parseInt($('#mymess').css('height')) + 32;
                $('#mymess').css('height', text_box_height+'px');
            }
        });

        $('#thisIsTop').click(function() {
            totalPage = Math.ceil(totalNumMess/25);
            pages++;

            if (pages <= totalPage) {
                froms = ((pages - 1) * 25);

                $.ajax('index.php?url=process-prev-forums', {
                    type: 'POST',
                    data: {
                        from: froms
                    },
                    success: function(data) {
                        $('#appendTop').prepend(data);
                    }
                });
            }

            if (pages >= totalPage) {
                $('#thisIsTop').remove();
            }
        });

         $('#btn-save').click(function(){
            var _id = $('#id-comment').val();
            var _comment = $('#content-comment-s1').val();
            var _pass  = $('#input-pass').val().trim();

            if(_pass.length > 0){
                $.ajax('index.php?url=ajax-edit-comment-forums', {
                    type: 'POST',
                    data: {
                        id:         _id,
                        comment:    _comment,
                        pass:       _pass
                    },
                    success: function(data) {
                        if(data == 'false'){
                            alert('Incorrect password!');
                        }else{
                            _comment = replaceAll(_comment, '\n', "<br/>");

                            $('#content-cmt-'+_id).html(_comment);
                            $('#input-pass').val('');
                            $('#EditMessage').modal('hide');
                        }
                    }
                });
            }else{
                alert('Please enter a password!');
            }
        });
    });
</script>