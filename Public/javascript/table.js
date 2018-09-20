(function ($) {
//表格处理
    $.fn.miTable = function (options) {
        var defaults = {
            selectAll: '#selectAll',
            selectSubmit: '#selectSubmit',
            selectAction: '#selectAction',
            deleteUrl: '',
            actionUrl: '',
            actionParameter: function () {
            }
        }
        var options = $.extend(defaults, options);
        this.each(function () {
            var table = this;
            var id = $(this).attr('id');
            //处理多选单选,id方式
            $(options.selectAll).click(function () {
                $(table).find("[name='id[]']").each(function () {
                    if ($(this).prop("checked")) {
                        $(this).prop("checked", false);
                    } else {
                        $(this).prop("checked", true);
                    }
                })
            });

            //class 方式多选,class方式,可以设置多个
            $('.selectAll').click(function () {
                $(table).find("[name='id[]']").each(function () {
                    if ($(this).prop("checked")) {
                        $(this).prop("checked", false);
                    } else {
                        $(this).prop("checked", true);
                    }
                })
            });

            //处理批量提交
            $(options.selectSubmit).click(function () {
                Do.ready('tips', 'dialog', function () {
                    //记录获取
                    var ids = new Array();
                    $(table).find("[name='id[]']").each(function () {
                        if ($(this).prop("checked")) {
                            ids.push($(this).val());
                        }
                    })
                    toastr.options = {
                        "positionClass": "toast-bottom-right"
                    }
                    if (ids.length == 0) {
                        toastr.warning('请先选择操作记录');
                        return false;
                    }
                    //操作项目
                    var dialog = layer.confirm('你确认要进行本次批量操作！', function () {
                        var parameter = $.extend({
                                ids: ids,
                                type: $(options.selectAction).val()
                            },
                            options.actionParameter()
                        );
                        $.post(options.actionUrl, parameter, function (json) {
                            if (json.status) {
                                toastr.success(json.info);
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                toastr.warning(json.info);
                            }
                        }, 'json');
                        layer.close(dialog);
                    });

                });
            });

            //处理删除
            $(table).find('.js-del').click(function () {
                var obj = this;
                var div = $(obj).parent().parent();
                var url = $(obj).attr('url');
                if (url == '' || url == null || url == 'undefined') {
                    url = options.deleteUrl;
                }
                operat(
                    obj,
                    url,
                    function () {
                        div.remove();
                    },
                    function () {
                    }
                );
            });

            //切换状态，打勾的 soitif
            $(table).find('.js-toggle').on('click', function () {
                var url = $(this).data('url'), id = $(this).data('id'), val = ($(this).attr("src").match(/yes.gif/i)) ? 0 : 1, field = $(this).data('field'), img_obj = this;
                if (url == '' || url == null || url == 'undefined') {
                    url = options.toggleUrl;
                }
                $.post(url, {id: id, val: val, field: field, is_ajax: 1}, function (res) {
                    if (res.error == 0) {
                        img = (res.message > 0) ? '/public/images/yes.gif' : '/public/images/no.gif';
                        $(img_obj).attr("src", img);
                    }
                    else {
                        alert(res.message);
                    }
                }, 'json');
                return false;
            });

            //编辑指定内容 soitif
            $(table).find('.js-edit').on('click', function () {
                var url = $(this).data('url'), id = $(this).data('id'), field = $(this).data('field');
                if (url == '' || url == null || url == 'undefined') {
                    url = options.editUrl;
                }

                if ($(this).children()[0] && $(this).children()[0].tagName.toLowerCase() == 'input') {
                    return;
                }

                var sp = $(this);
                var revert = $(this).html();

                /* 创建一个输入框 */
                var txt = document.createElement("INPUT");
                var oldval = (revert == 'N/A') ? '' : revert;
                var pbox = this;
                txt.value = oldval;
                txt.style.width = ($(this).width() + 12) + "px";
                $(txt).data('oldval', oldval);

                $(this).html('');
                $(this).append(txt);
                $(this).removeClass('mi-edit');
                txt.focus();

                /* 编辑区输入事件处理函数 */
                $(txt).keypress(function () {
                    if (window.event.keyCode == 13) {
                        $(txt).blur();
                        return false;
                    }

                    if (window.event.keyCode == 27) {
                        $(txt).parent().html(revert);
                    }
                });

                /* 编辑区失去焦点的处理函数 */
                $(txt).blur(function () {
                    val = unescape($.trim($(txt).val()));
                    if (val.length > 0 && val != oldval) {
                        $.post(url, {id: id, val: val, field: field, is_ajax: '1'}, function (res) {
                            if (res.error == 0) {
                                $(txt).parent().html(res.message);
                                $(pbox).addClass('mi-edit');
                            }
                            else {
                                alert(res.message);
                            }
                        }, 'json');
                    }
                    else {
                        $(txt).parent().html(oldval);
                        $(pbox).addClass('mi-edit');
                    }
                })
            });

            //处理其他动作
            $(table).find('.js-action').click(function () {
                var obj = this;
                var div = $(obj).parent().parent();
                var url = $(obj).attr('url');
                operat(
                    obj,
                    url,
                    function () {
                        var success = $(obj).attr('success');
                        if (success) {
                            eval(success);
                        }
                    },
                    function () {
                        var failure = $(obj).attr('failure');
                        if (failure) {
                            eval(failure);
                        }
                    }
                );
            });

            //处理动作
            function operat(obj, url, success, failure) {
                Do.ready('tips', 'dialog', function () {
                    var text = $(obj).attr('title');
                    var dialog = layer.confirm('你确认执行' + text + '操作？', function () {
                        var dload = layer.load('操作执行中，请稍候...');
                        $.post(url, {data: $(obj).data('id')},
                            function (json) {
                                layer.close(dload);
                                layer.close(dialog);
                                if (json.status) {
                                    toastr.success(json.info);
                                    success();
                                } else {
                                    toastr.warning(json.info);
                                    failure();
                                }
                            }, 'json');
                    });
                });
            }

            //处理编辑
            $(table).find('.table_edit').blur(function () {
                var obj = this;
                var data = $(obj).attr('data');
                var url = $(obj).attr('url');
                if (url == '' || url == null || url == 'undefined') {
                    url = options.editUrl;
                }
                Do.ready('tips', function () {
                    $.post(url, {
                            data: $(obj).attr('data'),
                            name: $(obj).attr('name'),
                            val: $(obj).val(),
                        },
                        function (json) {
                            if (json.status) {
                                toastr.success(json.info);
                            } else {
                                toastr.warning(json.info);
                            }
                        }, 'json');
                });
            });
        });
    };

//表单处理
    $.fn.miForm = function (options) {
        var defaults = {
            postFun: {},
            returnFun: {}
        }
        var options = $.extend(defaults, options);
        this.each(function () {
            //表单提交
            var form = this;
            Do.ready('form', 'dialog', function () {
                $(form).Validform({
                    ajaxPost: true,
                    postonce: true,
                    tiptype: function (msg, o, cssctl) {
                        if (!o.obj.is("form")) {
                            //设置提示信息
                            var objtip = o.obj.siblings(".input-note");
                            if (o.type == 2) {
                                //通过
                                var className = ' ';
                                $('#tips').html('');
                                objtip.next('.js-tip').remove();
                                objtip.show();
                            }
                            if (o.type == 3) {
                                //未通过
                                var html = '<div class="alert alert-yellow"><strong>注意：</strong>您填写的信息未通过验证，请检查后重新提交！</div>';
                                $('#tips').html(html);
                                var className = 'check-error';
                                if (objtip.next('.js-tip').length == 0) {
                                    objtip.after('<div class="input-note js-tip">' + msg + '</div>');
                                    objtip.hide();
                                }
                            }
                            //设置样式
                            o.obj.parents('.form-group').removeClass('check-error');
                            o.obj.parents('.form-group').addClass(className);
                        }
                    },
                    callback: function (data) {
                        layer.load('表单正在处理中，请稍等 ...');
                        if (data.status == 1) {
                            //成功返回
                            if ($.isFunction(options.returnFun)) {
                                options.returnFun(data);
                            } else {
                                if (data.url == null || data.url == '' || data.url == 'dialog') {
                                    //不带连接
                                    layer.alert(data.info, 1, function () {
                                        if (data.url == 'dialog') {
                                            window.location.reload();
                                            top.frames["mi-iframe"].document.location.reload();
                                            //layer.parent.close();
                                        } else {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    //带连接
                                    $.layer({
                                        shade: [0],
                                        area: ['auto', 'auto'],
                                        dialog: {
                                            msg: data.info,
                                            btns: 2,
                                            type: 4,
                                            btn: ['继续操作', '返回'],
                                            yes: function () {
                                                window.location.reload();
                                            },
                                            no: function () {
                                                window.location.href = data.url;
                                            }
                                        }
                                    });
                                }
                            }
                        } else {
                            //失败返回
                            layer.alert(data.info, 8);
                        }
                    }
                });
                //下拉赋值
                var assignObj = $(form).find('.js-assign');
                assignObj.each(function () {
                    var assignTarget = $(this).attr('target');
                    $(this).change(function () {
                        $(assignTarget).val($(this).val());
                    });
                });
            });
        });
    };
});
