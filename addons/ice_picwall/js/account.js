/*
Powered by 向瑞鸿  18617122016
*/

var account_obj = {
    login_init: function() {
        if (window != top) {
            top.location.href = window.location.href;
        }

        $('form').submit(function() { return false; });
        $('input:submit').click(function() {
            var flag = false;
            $('#UserName, #Password').each(function() {
                if ($(this).val() == '') {
                    $(this).focus();
                    flag = true;
                    return false;
                }
            });
            if (flag) { return; }

            $('.login_msg').html('');
            $(this).attr('disabled', true);

            $.ajax({
                cache: true,
                type: "POST",
                url: 'Login.aspx?type=login', // 提交的URL 
                data: $('#login_form').serialize(),  //  要提交的表单,必须使用name属性 
                async: false,
                success: function(data) {
                    if (data == "Succeed") {
                        window.location.href = "/Admin/Index.aspx";
                    }
                    else if (data == "UserNameError") {
                        $('.login_msg').show().html('登录失败，错误的用户名！');
                    }
                    else if (data == "PasswordError") {
                        $('.login_msg').show().html('登录失败，错误的密码！');
                    } else {

                    }
                    $('input:submit').attr('disabled', false);
                },
                error: function(request) {
                    alert("Connection error");
                }
            });

        });

        $('form input').each(function() {
            $(this).focus(function() {
                $(this).siblings('label').css({ display: 'none' });
            });
            $(this).blur(function() {
                if ($(this).val() == '') {
                    $(this).siblings('label').css({ display: 'block' });
                }
            });
        });
    },

    index_init: function() {
        $('a[group]').click(function() {
            var group = $(this).attr('group');
            if (group == '#') {
                parent.$('#main .menu dt').removeClass('cur');
                parent.$('#main .menu dd').hide();
            } else {
                parent.$('#main .menu dt').removeClass('cur');
                parent.$('#main .menu dt[group=' + group + ']').addClass('cur').next().filter('dd').show();
            }
            parent.$('#main .menu div').removeClass('cur');
            if ($(this).attr('url')) {
                parent.$('#main .menu a[href="' + $(this).attr('url') + '"]').parent().addClass('cur');
            } else {
                parent.$('#main .menu a[href="' + $(this).attr('href') + '"]').parent().addClass('cur');
            }
            parent.main_obj.page_scroll_init();
        });
    },

    profile_init: function() {
        $('#profile_form').submit(function() { return false; });
        $('#profile_form input:submit').click(function() {
            if (global_obj.check_form($('*[notnull]'))) { return false; };

            if ($('#NewPassword').val() != $('#ConfirmPassword').val()) {
                $('.profile_msg').css({ display: 'block' }).html('新密码与确认密码不匹配，请重新输入！');
                return false;
            }

            $('.profile_msg').css({ display: 'none' }).html('');
            $(this).attr('disabled', true);

            $.ajax({
                cache: true,
                type: "POST",
                url: '../Admin/Profile.aspx?type=update', // 提交的URL 
                data: $('#profile_form').serialize(),  //  要提交的表单,必须使用name属性 
                async: false,
                success: function(data) {
                    $('#profile_form input:submit').attr('disabled', false);
                    if (data == "Succeed") {
                        $('.profile_msg').css({ display: 'block' }).html('密码修改成功，请牢记新密码！');
                        $('#OldPassword, #NewPassword, #ConfirmPassword').val('');
                    }
                    else if (data == "ErrorOldPwd") {
                        $('.profile_msg').css({ display: 'block' }).html('修改密码失败，旧密码错误！');
                    }
                },
                error: function(request) {
                    alert("Connection error");
                }
            });

            //			$.post('?', $('#profile_form').serialize(), function(data){
            //				$('#profile_form input:submit').attr('disabled', false);
            //				$('#OldPassword, #NewPassword, #ConfirmPassword').val('');
            //				
            //				if(data.status==1){
            //					$('.profile_msg').css({display:'block'}).html('密码修改成功，请牢记新密码！');
            //				}else if(data.status==2){
            //					$('.profile_msg').css({display:'block'}).html('修改密码失败，旧密码错误！');
            //				}
            //			}, 'json');
        });
    }
}