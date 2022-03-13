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
                                        <p class="text-center text-danger">View more...</p>
                                    </div>
                                <?php } ?>
                                <div id="appendTop"></div>
                                <!-- start loop -->
                                <?php foreach ($data['list_mess'] as $value) { ?>
                                    <div id="mess-<?php echo $value['id'] ?>" class="direct-chat-msg message">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left"><?php echo $_SESSION['message_userNAME'] ?></span>
                                            <span class="direct-chat-timestamp float-right"><?php echo ConvertDate($value['date_create']) ?></span>
                                        </div>
                                        <img class="direct-chat-img" src="<?php echo $_SESSION['message_userAVATAR'] ?>" alt="message user image">
                                        <div class="direct-chat-text">
                                            <div class="float-right">
                                                <button class="dropdown btn btn-sm" style="margin-top: -11px; margin-right: -20px;">
                                                    <a class="nav-link text-muted" data-toggle="dropdown" href="#">
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                        <a href="#" class="dropdown-item dropdown-footer text-left" onclick="copyToClipboard(`#cpy-<?php echo $value['id'] ?>`)"><i class="fas fa-copy"></i> Copy message</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a href="#" class="dropdown-item dropdown-footer text-left" onclick="editMessage(`<?php echo $value['id'] ?>`)"><i class="fas fa-edit"></i> Edit message</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a href="#" class="dropdown-item dropdown-footer text-left" onclick="deleteMessage(`<?php echo $value['id'] ?>`)"><i class="fa fa-trash"></i> Delete message</a>
                                                    </div>
                                                </button>
                                            </div>

                                            <p id="cpy-<?php echo $value['id'] ?>"><?php echo ConvertMessage($value['message']) ?></p>
                                        </div>
                                    </div>
                                <?php } ?>
                                <!-- end loop -->

                                <div id="append"></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="input-group">
                                <textarea id="mymess" placeholder="Type message ..." class="form-control" rows="1"></textarea>
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
        <h5 class="modal-title text-uppercase" id="staticEditMess"><i class="fas fa-edit"></i> edit message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <input type="text" id="id-message" hidden>
            <div class="form-group">
                  <label>Message</label>
                  <textarea id="content-mess" class="form-control" rows="4"></textarea>
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

<!-- Delete Modal -->
<div class="modal fade" id="DeleteMessage" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticDeleteMess" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-uppercase" id="staticDeleteMess"><i class="fas fa-trash"></i> delete message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <input type="text" id="id-message-del" hidden>
            <div class="form-group">
                  <label>Message</label>
                  <textarea id="content-mess-del" class="form-control" rows="4" readonly></textarea>
              </div>
              <div class="form-group">
                  <label>Confirm password</label>
                  <input id="input-pass-del" type="password" class="form-control">
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="btn-del" type="button" class="btn btn-primary">Delete now</button>
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
                $.ajax('index.php?url=ajax-push-message', {
                    type: 'POST',
                    data: {
                        mess: my_mess
                    },
                    success: function(data) {
                        $('#mymess').val('');
                        $('#append').append(data);
                        $('#mymess').css('height', 38);
                        scrollToBottom();
                        $('#btn-sent').attr("disabled", false);
                        totalNumMess++;
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

                $.ajax('index.php?url=ajax-get-message', {
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
            var id_mes = $('#id-message').val();
            var cont_mess = $('#content-mess').val();
            var conf_pass = $('#input-pass').val().trim();

            if(conf_pass.length > 0){
                $.ajax('index.php?url=process-edit-message', {
                    type: 'POST',
                    data: {
                        idMess: id_mes,
                        pass: conf_pass,
                        cont: cont_mess
                    },
                    success: function(data) {
                        if(data == 'false'){
                            alert('Password incorrect!');
                        }else{
                            $('#cpy-'+id_mes).html(data);
                            $('#input-pass').val('');
                            $('#EditMessage').modal('hide');
                        }
                    }
                });
            }else alert('Please enter a password!');
        });

        $('#btn-del').click(function(){
            var id_mes = $('#id-message-del').val();
            var conf_pass = $('#input-pass-del').val().trim();

            if(conf_pass.length > 0){
                $.ajax('index.php?url=process-delete-message', {
                    type: 'POST',
                    data: {
                        idMess: id_mes,
                        pass: conf_pass
                    },
                    success: function(data) {
                        if(data == 'false'){
                            alert('Password incorrect!');
                        }else{
                            $('#DeleteMessage').modal('hide');
                            $('#input-pass-del').val('');
                            $('#mess-'+id_mes).remove();
                            totalNumMess++;
                            
                            $.ajax('index.php?url=process-database-size', {
                                type: 'POST',
                                data: {},
                                success: function(data) {
                                    $('#db-size').html(data);
                                }
                            });
                        }
                    }
                });
            }else alert('Please enter a password!');
        });
    });

    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }

    function editMessage(id){
        var content = $('#cpy-'+id).html().replace(/<br>/g, "\n");
        $('#id-message').val(id);
        $('#content-mess').val(content);
        $('#EditMessage').modal('show');
    }

    function deleteMessage(id){
        var content = $('#cpy-'+id).html().replace(/<br>/g, "\n");
        $('#id-message-del').val(id);
        $('#content-mess-del').val(content);
        $('#DeleteMessage').modal('show');
    }
</script>