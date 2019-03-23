;(function ($) {
	$.extend($.fn, {
		scrollTo: function (m, t) {
			t = t == undefined ? 200 : t;
			var n = t / 10, s = m / n, that = this, timer = null, p = f = that.scrollTop();
			var smoothScroll = function () {
				if (m < 0 && (p - f < m || p <= 0)) {
					window.clearInterval(timer);
					return false;
				} else if (m > 0 && p - f > m) {
					window.clearInterval(timer);
					return false;
				} else if (m == 0) {
					window.clearInterval(timer);
					return false;
				}
				that.scrollTop(p);
				p = parseInt(p + s);
				if (m < 0 && p < 0) {
					that.scrollTop(0);
				}
			};
			timer = window.setInterval(function () {
				smoothScroll();
			}, 10);
		}
	})
})($);
var $pt = {}, w = $(window).width(), h = $(window).height();
(function ($) {
	$pt.config = {
		base: {
			width: w,
			height: h,
			page: 1,
			totalpage: 1,
		},
		chapter: {
			init: 0,
			info: {
				id: '',
				title: '',
				content: '',
			},
			novel: {
				id: '',
				name: '',
				marked: 0,
			},
			from: {
				id: '',
				url: '',
			},
			next: {
				id: '',
				url: '',
			},
			prev: {
				id: '',
				url: '',
			},
		},
		reader: {
			status: 0,
			fontsize: 16,//字体大小
			theme: 'default',//模板
			moon: 0,//夜间模式 0关闭 1开启
			style: 0,//0上下 1左右
			cache: 0,//离线数
		}
	};
	$pt.chapter = {
		//初始化
		init: function (chapter, reader) {
			$pt.chapter.initreader(reader);
			$pt.chapter.initchapter(chapter);
			$pt.chapter.menu.init();
			$pt.chapter.menu.cache.load();
		},
		//初始化阅读器
		initreader: function (config) {
			if (config) {
				$pt.config.reader = $.extend($pt.config.reader, config);
			}
			$pt.config.base.rh = parseInt($pt.config.reader.fontsize * 1.8 * 1.5);
			//初始化阅读器
			//横屏竖屏参数
			if ($pt.config.reader.style == 0) {
				//竖屏
				$('.pt-reader').removeClass('about');
				$('.pt-reader .content').css({
					'width': $pt.config.base.width,
					'height': 'auto',
					'-webkit-column-width': 'inherit'
				})
				$('.pt-reader').off('swipeRight');
				$('.pt-reader').off('swipeLeft');
			} else {
				//横屏
				$('.pt-reader').addClass('about');
				$('.pt-reader .body').css({'height': null, 'width': $pt.config.base.width});
				$('.pt-reader .content').css({
					'width': $pt.config.base.width,
					'height': $pt.config.base.height,
					'-webkit-column-width': $pt.config.base.width,
				});
				$('.pt-reader .body .aboutheader').width($pt.config.base.width)
				//$('.pt-reader').on('swipeRight', $pt.chapter.menu.next);
				//$('.pt-reader').on('swipeLeft', $pt.chapter.menu.prev);
			}
			//预读
			$('.pt-menu .cache .c' + $pt.config.reader.cache).addClass('active').siblings().removeClass('active');
			//翻页
			$('.option.style li').eq($pt.config.reader.style).addClass('active').siblings().removeClass('active');
			//字体
			$pt.chapter.menu.fontsize.change();
			//末班
			$('body').removeAttr('class').addClass('theme-' + $pt.config.reader.theme);
			$('.option.theme .theme-' + $pt.config.reader.theme).addClass('active').siblings().removeClass('active');
			//日间夜间模式
			if ($pt.config.reader.moon) {
				$('.menu-moon').addClass('ptm-hide');
				$('.menu-sun').removeClass('ptm-hide');
				$('body').addClass('theme-moon');
			}
			//
		},
		//初始化章节信息
		initchapter: function (config) {
			var lastid = 0;
			if (config && config!=[]) {
				$pt.config.chapter = $.extend($pt.config.chapter, config);
				lastid = (config.info == undefined || config.info.lastid == undefined)?  $pt.config.chapter.info.id : config.info.lastid
			}
			if ($pt.config.chapter.init == 0) {
				$('.pt-reader').show();
				$('.loading').hide();
				if ($pt.config.chapter.novel.marked) {
					//已收藏
					$('.menu-mark').addClass('ptm-hide');
					$('.menu-marked').removeClass('ptm-hide');
				} else {
					//未收藏
					$('.menu-marked').addClass('ptm-hide');
					$('.menu-mark').removeClass('ptm-hide');
				}
				$pt.config.chapter.init = 1;
				var c = JSON.parse(JSON.stringify($pt.config.chapter));
				delete c.novel;
				window.localStorage.setItem('cc_' + $pt.config.chapter.novel.id + '_' + $pt.config.chapter.info.id, JSON.stringify(c))
			} else {
				$pt.chapter.menu.cache.load();
				history.pushState(null, '', $pt.config.chapter.info.url);
				$('title').html($pt.config.chapter.info.title);
				$.post('/api/reader/updatemark.json', {novelid: $pt.config.chapter.novel.id, chapterid: $pt.config.chapter.info.id}, function (res) {
				}, 'json')
			}
			//根据章节内容处理
			$('.pt-reader .aboutheader .title').text($pt.config.chapter.info.title);
			$('.pt-reader .content').html('').removeClass('anim').css('-webkit-transform', 'translate3d(0px, 0px, 0px)').html('<p class="title">' + $pt.config.chapter.info.title + '</p>' + $pt.config.chapter.info.content + '<p class="endline"></p>');
			//初始化章节之后的处理
			if ($pt.config.reader.style == 0) {
				//竖屏 计算高度
				$('.pt-reader .body').height(Math.ceil($('.pt-reader .body').height() / ( $pt.config.base.height - $pt.config.base.rh)) * ( $pt.config.base.height - $pt.config.base.rh));
				$('.pt-reader').scrollTop(0);
			} else {
				$('.pt-reader .content').css('-webkit-transform', 'translate3d(0px, 0px, 0px)')
				$pt.config.base.totalpage = 1 + ($('.pt-reader .content p').last().offset().left - 15) / ($pt.config.base.width - 15);
				//横屏计算页码
				if (lastid == $pt.config.chapter.next.id && lastid > 0) {
					$pt.config.base.page = $pt.config.base.totalpage;
					$('.pt-reader .content').css('-webkit-transform', 'translate3d(-' + ($pt.config.base.page - 1) * ($pt.config.base.width - 15) + 'px, 0px, 0px)')
				} else {
					$pt.config.base.page = 1;
				}
				$('.page').html($pt.config.base.page + '/' + $pt.config.base.totalpage);
				setTimeout(function () {
					$('.pt-reader .content').addClass('anim');
				}, 600);
			}
		},
		//菜单
		menu: {
			init: function () {
				var width = $pt.config.base.width, height = $pt.config.base.height;
				$('.pt-reader').on('click', function (event) {
					if ($pt.config.reader.status == 0) {
						var x = event.clientX, y = event.clientY;
						if (x / width < 0.4) {
							$pt.chapter.menu.prev();
						} else if (x / width > 0.6) {
							$pt.chapter.menu.next();
						} else {
							if (y / height < 0.3) {
								$pt.chapter.menu.prev();
							} else if (y / height > 0.7) {
								$pt.chapter.menu.next();
							} else {
								$pt.chapter.menu.show();
							}
						}
					}
				});
				// 点其他位置关闭菜单
				$('.pt-menu .bg').on('click', function () {
					$pt.chapter.menu.hide();
					$('.pt-menu .button').removeClass('active');
				});
				// 更多菜单操作按钮
				$('.pt-menu .menu-more').on('tap', $pt.chapter.menu.more);
				// 收藏按钮操作 未关注事件
				$('.pt-menu .menu-mark').on('tap', $pt.chapter.menu.mark.add);
				// 已关注事件
				$('.pt-menu .menu-marked').on('tap', $pt.chapter.menu.mark.remove);
				// 夜间模式
				$('.pt-menu .menu-moon').on('tap', $pt.chapter.menu.moon.apply);
				// 日间模式
				$('.pt-menu .menu-sun').on('tap', $pt.chapter.menu.moon.unapply);
				// 预读
				$('.pt-menu .menu-cache').on('tap', $pt.chapter.menu.cache.trigger);
				// 设置键
				$('.pt-menu .menu-setting').on('tap', $pt.chapter.menu.setting.trigger);
				// 换源
				$('.pt-menu .menu-change').on('tap', $pt.chapter.menu.change.load);
				$(document).on('tap', '.menu-op.change li', $pt.chapter.menu.change.change);
				// 下一章
				$('.pt-menu .center .next').on('tap', $pt.chapter.chapter.next);
				// 上一章
				$('.pt-menu .center .prev').on('tap', function () {
					$pt.chapter.chapter.prev(1)
				});
			},
			fontsize: {
				inc: function () {
					$pt.config.reader.fontsize++;
					$.post('/api/reader/fontsize.json', {fontsize: $pt.config.reader.fontsize}, function () {
						$pt.chapter.menu.fontsize.change();
					}, 'json');
				},
				dec: function () {
					$pt.config.reader.fontsize--;
					$.post('/api/reader/fontsize.json', {fontsize: $pt.config.reader.fontsize}, function () {
						$pt.chapter.menu.fontsize.change();
					}, 'json');
				},
				change: function () {
					$('.option.fontsize span').text($pt.config.reader.fontsize);
					var r = $('.pt-reader .content .title').css('fontSize', parseInt($pt.config.reader.fontsize) + 2);
					$('.pt-reader .content').css('fontSize', $pt.config.reader.fontsize);
					if ($pt.config.reader.style == 1) {
						var p = 1 + ($('.pt-reader .content p').last().offset().left - 15) / ($pt.config.base.width - 15);
						$pt.config.base.totalpage = p > $pt.config.base.page ? p : $pt.config.base.page;
						$('.page').html($pt.config.base.page + '/' + $pt.config.base.totalpage);
					}
				}
			},
			change: {
				load: function () {
					$(this).toggleClass('active').siblings().removeClass('active');
					$('.pt-menu .change').toggle().siblings('.menu-op').hide();
					$.get('/api/reader/getsamechapter.json', {novelid: $pt.config.chapter.novel.id, chapterid: $pt.config.chapter.info.id}, function (res) {
						if (res.status == 1) {
							if (res.data.length > 0) {
								var h = '';
								$.each(res.data, function (k, v) {
									if (v.siteid == $pt.config.chapter.from.id) {
										h += '<li class="ptm-list-view-cell ptm-text-cut ptm-text-left active" data-siteid="' + v.siteid + '">[' + v.sitename + '] ' + v.name + '</li>';
									} else {
										h += '<li class="ptm-list-view-cell ptm-text-cut ptm-text-left" data-siteid="' + v.siteid + '">[' + v.sitename + '] ' + v.name + '</li>';
									}
								});
								if (res.data.length < 4) {
									$('.menu-op.change .ptm-list-view').height(res.data.length * 40);
								} else {
									$('.menu-op.change .ptm-list-view').height(160);
								}
							} else {
								h += '<li class="ptm-list-view-cell ptm-text-cut active" data-siteid="' + v.siteid + '">' + v.sitename + ' - ' + v.name + '</li>';
							}
							$('.menu-op.change .ptm-list-view').html(h)
						}
					}, 'json');
					event.preventDefault();
					return false;
				},
				change: function () {
					var siteid = $(this).data('siteid');
					$ptm.loading.open('换源中...');
					data = $pt.chapter.chapter._get($pt.config.chapter.novel.id, $pt.config.chapter.info.id, siteid);
					$ptm.loading.close();
					$pt.chapter.initchapter(data);
					$(this).addClass('active').siblings().removeClass('active');
					$ptm.toast('换源成功');
					$('.pt-menu .menu-change').removeClass('active');
					$('.pt-menu .change').toggle();
					$pt.chapter.menu.hide();
				}
			},
			setting: {
				trigger: function () {
					$(this).toggleClass('active').siblings().removeClass('active');
					$('.pt-menu .setting').toggle().siblings('.menu-op').hide();
					//阅读模式
					$('.option.style li').on('tap', function () {
						var num = $(this).index();
						$.post('/api/reader/readstyle.json', {v: num}, function () {
							$pt.config.reader.style = num;
							$pt.chapter.initreader($pt.config.reader);
							$pt.chapter.initchapter([]);
						}, 'json');
					});
					//模板切换
					$('.option.theme .theme-area').on('tap', function () {
						$pt.config.reader.theme = $(this).data('theme');
						$('body').removeAttr('class').addClass('theme-' + $pt.config.reader.theme);
						$('.option.theme .theme-' + $pt.config.reader.theme).addClass('active').siblings().removeClass('active');
						$.post('/api/reader/theme.json', {v: $pt.config.reader.theme}, function () {
						}, 'json');
					});
					// 字体加大
					$('.option.fontsize .inc').on('tap', $pt.chapter.menu.fontsize.inc);
					// 字体减小
					$('.option.fontsize .dec').on('tap', $pt.chapter.menu.fontsize.dec);
				}
			},
			cache: {
				trigger: function () {
					$(this).toggleClass('active').siblings().removeClass('active');
					$('.pt-menu .cache').toggle().siblings('.menu-op').hide();
					$('.pt-menu .cache li').on('tap', function () {
						$pt.chapter.menu.cache.set($(this).data('num'))
					})
				},
				set: function (num) {
					$pt.config.reader.cache = num;
					$('.pt-menu .cache .c' + num).addClass('active').siblings().removeClass('active')
					$.post('/api/reader/cache.json', {v: num}, function () {
						$pt.chapter.menu.cache.load();
					}, 'json');
				},
				load: function () {
					if (!$pt.config.reader.cache) return;
					var novelid = $pt.config.chapter.novel.id, chapterid = $pt.config.chapter.info.id;
					var ls_start = parseInt(window.localStorage.getItem('cc_' + novelid + '_start'));
					var ls_end = parseInt(window.localStorage.getItem('cc_' + novelid + '_end'));
					var start = chapterid - 1, end = parseInt(chapterid) + parseInt($pt.config.reader.cache);
					if (end >= $pt.config.chapter.novel.num) {
						end = parseInt($pt.config.chapter.novel.num) + 1;
					}
					if (start < 1) start = 1;
					//删除前面缓存过多的章节
					if (start > ls_start) {
						for (i = ls_start; i < start; i++) {
							window.localStorage.removeItem('cc_' + novelid + '_' + i);
						}
					}
					//删除后面缓存过多的章节
					if (end <= ls_end) {
						for (i = end; i < ls_end; i++) {
							window.localStorage.removeItem('cc_' + novelid + '_' + i);
						}
					}
					//缓存
					for (i = start; i < end; i++) {
						$pt.chapter.chapter._getasync(novelid, i);
					}
					//记录
					window.localStorage.setItem('cc_' + novelid + '_start', start);
					window.localStorage.setItem('cc_' + novelid + '_end', end);
				}
			},
			more: function () {
				$(this).toggleClass('active');
				$('.pt-menu .more').toggle();
			},
			moon: {
				apply: function () {
					$('.pt-menu .menu-op').hide();
					$('.menu-moon').addClass('ptm-hide');
					$('.menu-sun').removeClass('ptm-hide');
					$('body').addClass('theme-moon');
					$.post('/api/reader/moon.json', {v: 1}, function () {
						$pt.config.reader.moon = 1;
						$ptm.toast('切换到夜间模式');
					}, 'json');
				},
				unapply: function () {
					$('.pt-menu .menu-op').hide();
					$('body').removeClass('theme-moon');
					$('.menu-sun').addClass('ptm-hide');
					$('.menu-moon').removeClass('ptm-hide');
					$.post('/api/reader/moon.json', {v: 0}, function () {
						$pt.config.reader.moon = 0;
						$ptm.toast('切换到日间模式');
					}, 'json');
				}
			},
			mark: {
				add: function () {
					$.post('/api/reader/mark.json', {id: $pt.config.chapter.novel.id, 'chapterid': $pt.config.chapter.info.id, type: 'add'}, function (res) {
						if (res.status == 1) {
							$ptm.toast('成功加入收藏夹');
							$('.menu-mark').addClass('ptm-hide');
							$('.menu-marked').removeClass('ptm-hide');
						} else {
							alert(res.info);
						}
					}, 'json');
				},
				remove: function () {
					$.post('/api/reader/mark', {id: $pt.config.chapter.novel.id, type: 'remove'}, function (res) {
						if (res.status == 1) {
							$ptm.toast('取消收藏成功');
							$('.menu-marked').addClass('ptm-hide');
							$('.menu-mark').removeClass('ptm-hide');
						} else {
							alert(res.info);
						}
					}, 'json');
				}
			},
			trigger: function () {
				if ($pt.config.reader.status == 1) {
					$pt.chapter.menu.hide();
				} else {
					$pt.chapter.menu.show();
				}
			},
			show: function () {
				$pt.config.reader.status = 1;
				$('.pt-menu').show();
			},
			hide: function () {
				$pt.config.reader.status = 0;
				$('.pt-menu').hide();
			},
			//向下
			next: function () {
				if ($pt.config.reader.style == 0) {
					if ($('.pt-reader').scrollTop() + $pt.config.base.height == $('.pt-reader .body').height()) {
						//到达了底部则下一章
						$pt.chapter.chapter.next();
					} else {
						//没有到达了底部则下一页
						$pt.chapter.page.next();
					}
				} else {
					if ($pt.config.base.page == $pt.config.base.totalpage) {
						$pt.chapter.chapter.next();
					} else {
						$pt.chapter.page.next();
					}
				}
			},
			//向上
			prev: function () {
				if ($pt.config.reader.style == 0) {
					if ($('.pt-reader').scrollTop() == 0) {
						//顶部则上一章
						$pt.chapter.chapter.prev();
					} else {
						//没有到顶部就到上一页
						$pt.chapter.page.prev();
					}
				} else {
					if ($pt.config.base.page == 1) {
						$pt.chapter.chapter.prev();
					} else {
						$pt.chapter.page.prev();
					}
				}
			},
		},
		//页面滚动操作
		page: {
			next: function () {
				if ($pt.config.reader.style == 0) {
					$('.pt-reader').scrollTo($pt.config.base.height - $pt.config.base.rh, 300);
				} else {
					$pt.config.base.page++;
					$('.page').html($pt.config.base.page + '/' + $pt.config.base.totalpage);
					$('.pt-reader .content').css('-webkit-transform', 'translate3d(-' + ($pt.config.base.page - 1) * ($pt.config.base.width - 15) + 'px, 0px, 0px)')
				}
			},
			prev: function () {
				if ($pt.config.reader.style == 0) {
					$('.pt-reader').scrollTo($pt.config.base.rh - parseInt($pt.config.base.height), 300);
				} else {
					$pt.config.base.page--;
					$('.page').html($pt.config.base.page + '/' + $pt.config.base.totalpage);
					$('.pt-reader .content').css('-webkit-transform', 'translate3d(-' + ($pt.config.base.page - 1) * ($pt.config.base.width - 15) + 'px, 0px, 0px)')
				}
			}
		},
		//上一章 下一章
		chapter: {
			next: function () {
				if ($pt.config.chapter.next.id == 0) {
					window.location.href = $pt.config.chapter.next.url;
				} else {
					var data = $pt.chapter.chapter.get($pt.config.chapter.novel.id, $pt.config.chapter.next.id, 0);
					$pt.chapter.initchapter(data);
				}
			},
			prev: function (id) {
				if ($pt.config.chapter.prev.id == 0) {
					window.location.href = $pt.config.chapter.prev.url;
				} else {
					data = $pt.chapter.chapter.get($pt.config.chapter.novel.id, $pt.config.chapter.prev.id, 0);
					if (id) data.info.lastid = id;
					$pt.chapter.initchapter(data);
				}
			},
			get: function (novelid, chapterid, siteid) {
				if (siteid == undefined) siteid = 0;
				var data = JSON.parse(window.localStorage.getItem('cc_' + novelid + '_' + chapterid));
				if (data === null || data.info.content.length < 200) {
					//3次获取
					data = $pt.chapter.chapter._get(novelid, chapterid, siteid);
					if (data === null || !data.info || data.info.content.length < 200) {
						data = $pt.chapter.chapter._get(novelid, chapterid, siteid);
					}
					if (data === null || !data.info || data.info.content.length < 200) {
						data = $pt.chapter.chapter._get(novelid, chapterid, siteid);
					}
				}
				return data;
			},
			_get: function (novelid, chapterid, siteid) {
				var data = {};
				$.ajax({
					method: "POST",
					url: '/api/reader/getchapter.json',
					data: {novelid: novelid, chapterid: chapterid, siteid: siteid},
					async: false,
					dataType: 'json',
					success: function (res) {
						if (res.status == 1) {
							data = res.data;
							if (data.info.content.length > 200) {
								window.localStorage.setItem('cc_' + novelid + '_' + chapterid, JSON.stringify(data))
							}
						}
					}
				});
				return data;
			},
			_getasync: function (novelid, chapterid, siteid) {
				var data = JSON.parse(window.localStorage.getItem('cc_' + novelid + '_' + chapterid));
				if (data === null || data.info.content.length < 200) {
					$.ajax({
						method: "POST",
						url: '/api/reader/getchapter.json',
						data: {novelid: novelid, chapterid: chapterid, siteid: siteid},
						dataType: 'json',
						success: function (res) {
							if (res.status == 1) {
								data = res.data;
								if (data.info.content.length > 200) {
									window.localStorage.setItem('cc_' + novelid + '_' + chapterid, JSON.stringify(data))
								}
							}
						}
					});
				}
			}
		}
	}
})($);
$(function(){
	//图片修复
	$('img').on('error',function(){
		this.src='/public/image/nocover.jpg';
	});
})