function AVK_GAME(y, ea, fa) {
	function G() {
		window.scrollTo(0, 1);
		var b = a.SCREEN_HEIGHT,
			f = a.SCREEN_WIDTH,
			k = window.innerWidth || document.body.clientWidth,
			e = window.innerHeight || document.body.clientHeight,
			g = k,
			d = e;
		e / b * 1.5 > k / f ? e = k * b / f : k = e * f / b;
		a.GUI.position.x = 0;
		a.GUI.position.y = 0;
		a.GUI.scale.x = 1;
		a.GUI.scale.y = 1;
		e > d && (a.GUI.scale.y = e / d, a.GUI.position.y = -a.GUI.scale.y * a.SCREEN_WIDTH / k * (e - d) / 2, e = d);
		a.BACK_SPR.position.x = a.GUI.position.x;
		a.BACK_SPR.position.y = a.GUI.position.y;
		a.BACK_SPR.scale.x = a.GUI.scale.x;
		a.BACK_SPR.scale.y = a.GUI.scale.y;
		a.LOADER_SPR.position.x = a.GUI.position.x;
		a.LOADER_SPR.position.y = a.GUI.position.y;
		a.LOADER_SPR.scale.x = a.GUI.scale.x;
		a.LOADER_SPR.scale.y = a.GUI.scale.y;
		b = ga.RENDER.view;
		b.style.height = e + "px";
		b.style.width = k + "px";
		b.style.top = (d - e) / 2 + "px";
		b.style.left = (g - k) / 2 + "px"
	}
	function H() {
		A++;
		v = 0;
		0 == A && I()
	}
	function I() {
		var b = (new Date).getTime();
		200 < b - n && (n = b - 200);
		var f = b - n;
		n = b;
		B.length;
		w += f;
		5E3 < w && (w -= 5E3, s++, s >= B.length && (s = 0), r.setText(B[s]));
		x.position.x = -412 + 488 * (A + v / (v + 1)) / (J.length + 1);
		a.RENDER.render(a.STAGE);
		A < J.length + 1 && (requestAnimFrame(I), v += .01)
	}
	function R() {
		if (GLOB_M && 7E3 > K) {
			var a = (new Date).getTime();
			200 < a - C && (C = a - 200);
			K += a - C;
			C = a;
			0 == GLOB_SND_TO_LOAD ? S() : requestAnimFrame(R)
		} else S()
	}
	function S() {
		H();
		I();
		a.LOADER_SPR.removeChild(r);
		a.WND = "INTRO";
		a.INTRO = new T;
		a.WND = "TITLE";
		a.TITLE = new U;
		a.WND = "HERO_S";
		a.HERO_S = new V;
		a.WND = "PRINCESS";
		a.PRINCESS = new W;
		a.WND = "CREDITS";
		a.CREDITS = new X;
		a.WND = "MAIN";
		a.MAIN = new Y;
		a.WND = "GAME";
		a.GAME = new Z;
		a.WND = "LEVELS";
		a.LEVELS = new $;
		a.WND = "BLOCKS";
		a.BLOCKS = new aa;
		a.FPS = new PIXI.BitmapText("fps:", {
			font: "20px AVK_FNT_main",
			align: "left"
		});
		u = D = E = 0;
		ha();
		n = (new Date).getTime();
		requestAnimFrame(ba)
	}
	function ba() {
		var b = (new Date).getTime();
		200 < b - n && (n = b - 200);
		ia(b - n);
		a.ACT.update(b - n);
		u += b - n;
		for (E++; 1E3 < u;) D = E, a.FPS.setText("[" + L + "]" + M + " fps:" + D), E = 0, u -= 1E3;
		n = b;
		a.RENDER.render(a.STAGE);
		requestAnimFrame(ba)
	}
	function b(z, f, k, e, g, d) {
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		var c = [z, f, k, e, g, d];
		this.make_copy = function() {
			a.WND = this.WND;
			a.EL = this.EL;
			a.ID = this.ID + 1;
			return new b(c[0], c[1], c[2], c[3], c[4], c[5])
		};
		this.animations = d;
		this.uni_width = e;
		this.uni_height = g;
		this.time = 1E3;
		this.WND = a.WND;
		this.EL = a.EL;
		this.ID = a.ID;
		var l = this,
			m = 0,
			p = 0;
		if ("" == z) this.sprite = new PIXI.DisplayObjectContainer;
		else if (1 == d) this.sprite = PIXI.Sprite.fromFrame(z);
		else {
			e = [];
			for (g = 0; g < d; g++) {
				var t = PIXI.Texture.fromFrame(z + "_anim_" + g);
				e.push(t)
			}
			this.sprite = new PIXI.MovieClip(e)
		}
		this.sprite.position.x = f;
		this.sprite.position.y = k;
		a.GUI.addChild(this.sprite);
		this.update = function(a) {
			var b = !1;
			m -= a;
			0 >= m && (m = l.time / l.animations, p--, 0 > p && (p = l.animations - 1, b = !0), l.sprite.gotoAndStop(p));
			return b
		};
		this.set_parent = function(b) {
			b.sprite.addChild(this.sprite);
			b = this.sprite;
			for (var c = 0, e = 0; b = b.parent;) b != a.GUI && (c += b.position.x, e += b.position.y);
			this.sprite.position.x -= c;
			this.sprite.position.y -= e
		}
	}
	function ca(b, f, k, e, d, h) {
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		var c = [b, f, k, e, d, h];
		this.make_copy = function() {
			a.WND = this.WND;
			a.EL = this.EL;
			a.ID = this.ID + 1;
			return new ca(c[0], c[1], c[2], c[3], c[4], c[5])
		};
		this.animations = h;
		this.uni_width = e;
		this.uni_height = d;
		this.time = 1E3;
		this.WND = a.WND;
		this.EL = a.EL;
		this.ID = a.ID;
		var l = 0,
			m = 0;
		if ("" == b) this.sprite = new PIXI.DisplayObjectContainer;
		else if (1 == h) this.sprite = PIXI.Sprite.fromFrame(b);
		else {
			e = [];
			for (d = 0; d < h; d++) {
				var p = PIXI.Texture.fromFrame(b + "_anim_" + d);
				e.push(p)
			}
			this.sprite = new PIXI.MovieClip(e)
		}
		this.sprite.position.x = f;
		this.sprite.position.y = k;
		a.GUI.addChild(this.sprite);
		this.update = function(a) {
			l -= a;
			0 >= l && (l = this.time / animations, m++, m >= animations && (m = 0), this.sprite.gotoAndStop(m))
		};
		this.set_parent = function(b) {
			b.sprite.addChild(this.sprite);
			b = this.sprite;
			for (var c = 0, e = 0; b = b.parent;) b != a.GUI && (c += b.position.x, e += b.position.y);
			this.sprite.position.x -= c;
			this.sprite.position.y -= e
		}
	}
	function q(b, f, d, e, g, h) {
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		var c = [b, f, d, e, g, h];
		this.make_copy = function() {
			a.WND = this.WND;
			a.EL = this.EL;
			a.ID = this.ID + 1;
			return new q(c[0], c[1], c[2], c[3], c[4], c[5])
		};
		this.txt = new PIXI.BitmapText("", {
			font: Math.floor(.8 * g) + "px AVK_FNT_main",
			align: "left"
		});
		this.align = "left";
		this.caption = "";
		this.animations = h;
		this.uni_width = e;
		this.uni_height = g;
		this.time = 1E3;
		this.WND = a.WND;
		this.EL = a.EL;
		this.ID = a.ID;
		var l = 0,
			m = 0;
		if ("" == b) this.sprite = new PIXI.DisplayObjectContainer, this.sprite.position.x = f, this.sprite.position.y = d;
		else if (1 == h) this.sprite = PIXI.Sprite.fromFrame(b), this.sprite.position.x = f, this.sprite.position.y = d;
		else {
			e = [];
			for (var p = 0; p < h; p++) {
				var t = PIXI.Texture.fromFrame(b + "_anim_" + p);
				e.push(t)
			}
			this.sprite = new PIXI.MovieClip(e)
		}
		this.sprite.position.x = f;
		this.sprite.position.y = d;
		a.GUI.addChild(this.sprite);
		this.sprite.addChild(this.txt);
		this.update = function(a) {
			l -= a;
			0 >= l && (l = this.time / animations, m++, m >= animations && (m = 0), this.sprite.gotoAndStop(m))
		};
		this.set_parent = function(b) {
			b.sprite.addChild(this.sprite);
			b = this.sprite;
			for (var c = 0, e = 0; b = b.parent;) b != a.GUI && (c += b.position.x, e += b.position.y);
			this.sprite.position.x -= c;
			this.sprite.position.y -= e
		};
		this.set_style = function(a, b, c) {
			this.align = c;
			this.txt.setStyle({
				font: Math.floor(g * a * .8) + "px " + b,
				align: c
			});
			this.set_text(this.caption)
		};
		this.refresh = function() {
			switch (this.align) {
			case "left":
				this.txt.position.x = 0;
				break;
			case "right":
				this.txt.position.x = this.uni_width - this.txt.textWidth;
				break;
			case "center":
				this.txt.position.x = (this.uni_width - this.txt.textWidth) / 2, this.txt.position.y = (this.uni_height - this.txt.textHeight) / 2
			}
		};
		this.set_text = function(b) {
			this.caption != b && (this.caption = b, this.txt.setText(b), a.RENDER.render(a.STAGE), this.refresh())
		}
	}
	function d(b, f, k, e, g, h) {
		var c = this;
		this.add = function(a) {
			c.sprite.addChild(a.sprite)
		};
		var l = [b, f, k, e, g, h];
		this.make_copy = function() {
			a.WND = c.WND;
			a.EL = c.EL;
			a.ID = c.ID + 1;
			return new d(l[0], l[1], l[2], l[3], l[4], l[5])
		};
		var m = !1;
		this.enabled = !0;
		this.animations = h;
		this.uni_width = e;
		this.uni_height = g;
		this.time = 1E3;
		this.down_sprite = null;
		this.WND = a.WND;
		this.EL = a.EL;
		this.ID = a.ID;
		var p = 0,
			t = 0;
		if ("" == b) this.sprite = new PIXI.DisplayObjectContainer;
		else if (1 == h) this.sprite = PIXI.Sprite.fromFrame(b);
		else {
			e = [];
			for (g = 0; g < h; g++) {
				var n = PIXI.Texture.fromFrame(b + "_anim_" + g);
				e.push(n)
			}
			this.sprite = new PIXI.MovieClip(e)
		}
		this.sprite.position.x = f;
		this.sprite.position.y = k;
		this.sprite.owner = this;
		this.sprite.buttonMode = !0;
		this.sprite.interactive = !0;
		this.sprite.mousedown = this.sprite.touchstart = function(a) {
			c.enabled && (null == c.down_sprite ? (m || (c.sprite.position.x = c.sprite.owner.down_x, c.sprite.position.y = c.sprite.owner.down_y), c.sprite.scale.x = 1 - F, c.sprite.scale.y = 1 - F) : (c.sprite.visible = !1, c.down_sprite.visible = !0), N("down", c.sprite.owner.WND, c.sprite.owner.EL, c.sprite.owner.ID), a.originalEvent.stopPropagation(), a.originalEvent.preventDefault())
		};
		this.add_active = function(a) {
			c.down_sprite = a;
			c.down_sprite.buttonMode = !0;
			c.down_sprite.interactive = !0;
			c.down_sprite.visible = !1;
			c.down_sprite.click = c.down_sprite.tap = c.sprite.tap;
			c.down_sprite.mouseup = c.down_sprite.touchend = c.down_sprite.mouseupoutside = c.down_sprite.touchendoutside = c.down_sprite.touchendoutside;
			c.down_sprite.mousedown = c.down_sprite.touchstart = c.down_sprite.touchstart
		};
		this.sprite.click = this.sprite.tap = function(a) {
			c.enabled && (null == c.down_sprite ? (c.sprite.scale.x = 1, c.sprite.scale.y = 1, c.sprite.position.x = c.sprite.owner.up_x, c.sprite.position.y = c.sprite.owner.up_y) : (c.sprite.visible = !0, c.down_sprite.visible = !1), N("click", c.sprite.owner.WND, c.sprite.owner.EL, c.sprite.owner.ID), a.originalEvent.stopPropagation(), a.originalEvent.preventDefault())
		};
		this.sprite.mouseup = this.sprite.touchend = this.sprite.mouseupoutside = this.sprite.touchendoutside = function(a) {
			c.enabled && (null == c.down_sprite ? (c.sprite.scale.x = 1, c.sprite.scale.y = 1, c.sprite.position.x = c.sprite.owner.up_x, c.sprite.position.y = c.sprite.owner.up_y) : (c.sprite.visible = !0, c.down_sprite.visible = !1), N("up", c.sprite.owner.WND, c.sprite.owner.EL, c.sprite.owner.ID), a.originalEvent.stopPropagation(), a.originalEvent.preventDefault())
		};
		a.GUI.addChild(this.sprite);
		this.update = function(a) {
			p -= a;
			0 >= p && (p = c.time / animations, t++, t >= animations && (t = 0), c.sprite.gotoAndStop(t))
		};
		this.set_enabled = function(a) {
			c.enabled = a;
			c.sprite.alpha = a ? 1 : .48
		};
		this.set_parent = function(b) {
			b.sprite.addChild(c.sprite);
			b = c.sprite;
			for (var e = 0, f = 0; b = b.parent;) b != a.GUI && (e += b.position.x, f += b.position.y);
			c.sprite.position.x -= e;
			c.sprite.position.y -= f;
			c.down_x -= e;
			c.down_y -= f;
			c.up_x -= e;
			c.up_y -= f
		};
		this.refresh = function() {
			c.down_x = c.sprite.position.x + c.uni_width * F / 2;
			c.down_y = c.sprite.position.y + c.uni_height * F / 2;
			c.up_x = c.sprite.position.x;
			c.up_y = c.sprite.position.y
		};
		this.center = function() {
			m = !0;
			c.sprite.anchor.x = .5;
			c.sprite.anchor.y = .5;
			c.sprite.position.x += .5 * c.uni_width;
			c.sprite.position.y += .5 * c.uni_height;
			for (var a = 0; a < c.sprite.children.length; a++) c.sprite.children[a].position.x -= .5 * c.uni_width, c.sprite.children[a].position.y -= .5 * c.uni_height;
			c.refresh()
		};
		this.refresh()
	}
	function da(b, f, d, e, g, h) {
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		var c = [b, f, d, e, g, h];
		this.make_copy = function() {
			a.WND = this.WND;
			a.EL = this.EL;
			a.ID = this.ID + 1;
			return new da(c[0], c[1], c[2], c[3], c[4], c[5])
		};
		var l = "l",
			m = 1,
			p = 100,
			n = 100;
		this.animations = h;
		this.uni_width = e;
		this.uni_height = g;
		this.time = 1E3;
		this.WND = a.WND;
		this.EL = a.EL;
		this.ID = a.ID;
		var q = 0,
			r = 0;
		if ("" == b) this.sprite = new PIXI.DisplayObjectContainer, this.sprite.position.x = f, this.sprite.position.y = d;
		else if (1 == h) this.sprite = PIXI.Sprite.fromFrame(b), this.sprite.position.x = f, this.sprite.position.y = d;
		else {
			e = [];
			for (g = 0; g < h; g++) {
				var s = PIXI.Texture.fromFrame(b + "_anim_" + g);
				e.push(s)
			}
			this.sprite = new PIXI.MovieClip(e)
		}
		this.sprite.position.x = f;
		this.sprite.position.y = d;
		this.old_x = this.sprite.position.x;
		this.old_y = this.sprite.position.y;
		a.GUI.addChild(this.sprite);
		this.update = function(a) {
			q -= a;
			0 >= q && (q = this.time / animations, r++, r >= animations && (r = 0), this.sprite.gotoAndStop(r))
		};
		this.set_parent = function(b) {
			b.sprite.addChild(this.sprite);
			b = this.sprite;
			for (var c = 0, e = 0; b = b.parent;) b != a.GUI && (c += b.position.x, e += b.position.y);
			this.sprite.position.x -= c;
			this.sprite.position.y -= e;
			this.old_x = this.sprite.position.x;
			this.old_y = this.sprite.position.y
		};
		this.set_max = function(a) {
			p = a;
			this.set_progress(n / p)
		};
		this.set_val = function(a) {
			n = a;
			this.set_progress(n / p)
		};
		this.get_val = function() {
			return n
		};
		this.get_progress = function() {
			return m
		};
		this.set_progress = function(a) {
			0 > a && (a = 0);
			1 < a && (a = 1);
			m = a;
			switch (l) {
			case "l":
				this.sprite.scale.x = a;
				this.sprite.scale.y = 1;
				this.sprite.position.x = this.old_x;
				this.sprite.position.y = this.old_y;
				break;
			case "r":
				this.sprite.scale.x = a;
				this.sprite.scale.y = 1;
				this.sprite.position.x = this.old_x + this.uni_width * (1 - a);
				this.sprite.position.y = this.old_y;
				break;
			case "u":
				this.sprite.scale.x = 1;
				this.sprite.scale.y = a;
				this.sprite.position.x = this.old_x;
				this.sprite.position.y = this.old_y;
				break;
			case "d":
				this.sprite.scale.x = 1, this.sprite.scale.y = a, this.sprite.position.x = this.old_x, this.sprite.position.y = this.old_y + this.uni_height * (1 - a)
			}
		};
		this.set_align = function(a) {
			l = a;
			this.set_progress(m)
		}
	}
	function T() {
		this.sprite = new PIXI.DisplayObjectContainer;
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		a.GUI.addChild(this.sprite);
		this.sprite.visible = !1;
		a.EL = "back";
		this.back = new b("", 0, 0, 640, 1138, 1);
		this.sprite.addChild(this.back.sprite);
		a.EL = "start_btn";
		this.start_btn = new b("intro_start_btn", 245, 800, 155, 159, 1);
		this.sprite.addChild(this.start_btn.sprite);
		a.EL = "btn_start";
		this.btn_start = new d("intro_btn_start", 251, 808, 140, 144, 1);
		this.sprite.addChild(this.btn_start.sprite);
		a.EL = "no_snd_btn";
		this.no_snd_btn = new b("intro_no_snd_btn", 554, 241, 70, 72, 1);
		this.sprite.addChild(this.no_snd_btn.sprite);
		a.EL = "snd_btn";
		this.snd_btn = new b("intro_snd_btn", 554, 241, 71, 72, 1);
		this.sprite.addChild(this.snd_btn.sprite);
		a.EL = "btn_no_snd";
		this.btn_no_snd = new d("intro_btn_no_snd", 557, 244, 64, 66, 1);
		this.sprite.addChild(this.btn_no_snd.sprite);
		a.EL = "btn_snd";
		this.btn_snd = new d("intro_btn_snd", 557, 244, 64, 66, 1);
		this.sprite.addChild(this.btn_snd.sprite);
		a.EL = "up";
		this.up = new b("intro_up", 0, 319, 18, 472, 1);
		this.sprite.addChild(this.up.sprite);
		a.EL = "back_1";
		this.back_1 = new b("intro_back_1", 12, 330, 610, 212, 1);
		this.sprite.addChild(this.back_1.sprite);
		a.EL = "back_1_into";
		this.back_1_into = new b("", 27, 352, 591, 185, 1);
		this.sprite.addChild(this.back_1_into.sprite);
		a.EL = "s_1_1";
		this.s_1_1 = new b("intro_s_1_1", 378, 368, 240, 169, 1);
		this.sprite.addChild(this.s_1_1.sprite);
		a.EL = "s_1_2";
		this.s_1_2 = new b("intro_s_1_2", 122, 392, 399, 145, 1);
		this.sprite.addChild(this.s_1_2.sprite);
		a.EL = "s_1_3";
		this.s_1_3 = new b("intro_s_1_3", 58, 406, 351, 132, 1);
		this.sprite.addChild(this.s_1_3.sprite);
		a.EL = "s_1_4";
		this.s_1_4 = new b("intro_s_1_4", 40, 458, 70, 80, 1);
		this.sprite.addChild(this.s_1_4.sprite);
		a.EL = "up_1";
		this.up_1 = new b("intro_up_1", 0, 319, 640, 453, 1);
		this.sprite.addChild(this.up_1.sprite);
		a.EL = "back_2";
		this.back_2 = new b("intro_back_2", 23, 551, 297, 194, 1);
		this.sprite.addChild(this.back_2.sprite);
		a.EL = "back_2_into";
		this.back_2_into = new b("", 28, 556, 277, 184, 1);
		this.sprite.addChild(this.back_2_into.sprite);
		a.EL = "s_1_5";
		this.s_1_5 = new b("intro_s_1_5", 32, 557, 194, 184, 1);
		this.sprite.addChild(this.s_1_5.sprite);
		a.EL = "up_2";
		this.up_2 = new b("intro_up_2", 0, 530, 340, 242, 1);
		this.sprite.addChild(this.up_2.sprite);
		a.EL = "back_3";
		this.back_3 = new b("intro_back_3", 316, 544, 306, 201, 1);
		this.sprite.addChild(this.back_3.sprite);
		a.EL = "back_3_into";
		this.back_3_into = new b("", 340, 555, 278, 186, 1);
		this.sprite.addChild(this.back_3_into.sprite);
		a.EL = "s_1_6";
		this.s_1_6 = new b("intro_s_1_6", 441, 549, 177, 192, 1);
		this.sprite.addChild(this.s_1_6.sprite);
		a.EL = "up_3";
		this.up_3 = new b("intro_up_3", 309, 529, 331, 243, 1);
		this.sprite.addChild(this.up_3.sprite);
		this.back_1_into.set_parent(this.back_1);
		this.s_1_1.set_parent(this.back_1_into);
		this.s_1_2.set_parent(this.back_1_into);
		this.s_1_3.set_parent(this.back_1_into);
		this.s_1_4.set_parent(this.back_1_into);
		this.back_2_into.set_parent(this.back_2);
		this.s_1_5.set_parent(this.back_2_into);
		this.back_3_into.set_parent(this.back_3);
		this.s_1_6.set_parent(this.back_3_into)
	}
	function U() {
		this.sprite = new PIXI.DisplayObjectContainer;
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		a.GUI.addChild(this.sprite);
		this.sprite.visible = !1;
		a.EL = "back";
		this.back = new b("", 0, 0, 640, 1138, 1);
		this.sprite.addChild(this.back.sprite);
		a.EL = "start_btn";
		this.start_btn = new b("title_start_btn", 249, 697, 155, 159, 1);
		this.sprite.addChild(this.start_btn.sprite);
		a.EL = "btn_start";
		this.btn_start = new d("title_btn_start", 255, 705, 140, 144, 1);
		this.sprite.addChild(this.btn_start.sprite)
	}
	function V() {
		this.sprite = new PIXI.DisplayObjectContainer;
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		a.GUI.addChild(this.sprite);
		this.sprite.visible = !1;
		a.EL = "stay";
		this.stay = new b("hero_s_stay", 0, 0, 100, 100, 150);
		this.sprite.addChild(this.stay.sprite);
		a.EL = "go";
		this.go = new b("hero_s_go", 0, 0, 100, 100, 11);
		this.sprite.addChild(this.go.sprite)
	}
	function W() {
		this.sprite = new PIXI.DisplayObjectContainer;
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		a.GUI.addChild(this.sprite);
		this.sprite.visible = !1;
		a.EL = "prnc";
		this.prnc = new b("princess_prnc", 0, 0, 100, 150, 30);
		this.sprite.addChild(this.prnc.sprite)
	}
	function X() {
		this.sprite = new PIXI.DisplayObjectContainer;
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		a.GUI.addChild(this.sprite);
		this.sprite.visible = !1;
		a.EL = "back";
		this.back = new b("credits_back", 2, 326, 638, 812, 1);
		this.sprite.addChild(this.back.sprite);
		a.EL = "start_btn";
		this.start_btn = new b("credits_start_btn", 387, 778, 155, 159, 1);
		this.sprite.addChild(this.start_btn.sprite);
		a.EL = "btn_start";
		this.btn_start = new d("credits_btn_start", 393, 786, 140, 144, 1);
		this.sprite.addChild(this.btn_start.sprite);
		a.EL = "no_snd_btn";
		this.no_snd_btn = new b("credits_no_snd_btn", 554, 261, 70, 72, 1);
		this.sprite.addChild(this.no_snd_btn.sprite);
		a.EL = "snd_btn";
		this.snd_btn = new b("credits_snd_btn", 554, 261, 71, 72, 1);
		this.sprite.addChild(this.snd_btn.sprite);
		a.EL = "btn_no_snd";
		this.btn_no_snd = new d("credits_btn_no_snd", 557, 264, 64, 66, 1);
		this.sprite.addChild(this.btn_no_snd.sprite);
		a.EL = "btn_snd";
		this.btn_snd = new d("credits_btn_snd", 557, 264, 64, 66, 1);
		this.sprite.addChild(this.btn_snd.sprite)
	}
	function Y() {
		this.sprite = new PIXI.DisplayObjectContainer;
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		a.GUI.addChild(this.sprite);
		this.sprite.visible = !1;
		a.EL = "back";
		this.back = new b("main_back", 0, 0, 640, 1138, 1);
		this.sprite.addChild(this.back.sprite);
		a.EL = "clouds";
		this.clouds = new b("", 0, 0, 0, 0, 1);
		this.sprite.addChild(this.clouds.sprite);
		a.EL = "cl_0";
		this.cl_0 = new b("main_cl_0", 0, 248, 349, 87, 1);
		this.sprite.addChild(this.cl_0.sprite);
		a.EL = "cl_1";
		this.cl_1 = new b("main_cl_1", 0, 248, 463, 102, 1);
		this.sprite.addChild(this.cl_1.sprite);
		a.EL = "cl_2";
		this.cl_2 = new b("main_cl_2", 0, 240, 270, 95, 1);
		this.sprite.addChild(this.cl_2.sprite);
		a.EL = "up";
		this.up = new b("main_up", 0, 248, 639, 890, 1);
		this.sprite.addChild(this.up.sprite);
		//a.EL = "credits_btn";
		//this.credits_btn = new b("main_credits_btn", 291, 831, 98, 101, 1);
		//this.sprite.addChild(this.credits_btn.sprite);
		//a.EL = "btn_credits";
		//this.btn_credits = new d("main_btn_credits", 295, 836, 89, 92, 1);
		//this.sprite.addChild(this.btn_credits.sprite);
		//a.EL = "more_btn";
		//this.more_btn = new b("main_more_btn", 291, 725, 97, 100, 1);
		//this.sprite.addChild(this.more_btn.sprite);
		//a.EL = "btn_more";
		//this.btn_more = new d("main_btn_more", 295, 729, 89, 92, 1);
		//this.sprite.addChild(this.btn_more.sprite);
		a.EL = "start_btn";
		this.start_btn = new b("main_start_btn", 264, 562, 155, 159, 1);
		this.sprite.addChild(this.start_btn.sprite);
		a.EL = "btn_start";
		this.btn_start = new d("main_btn_start", 270, 570, 140, 144, 1);
		this.sprite.addChild(this.btn_start.sprite);
		a.EL = "no_snd_btn";
		this.no_snd_btn = new b("main_no_snd_btn", 554, 261, 70, 72, 1);
		this.sprite.addChild(this.no_snd_btn.sprite);
		a.EL = "snd_btn";
		this.snd_btn = new b("main_snd_btn", 554, 261, 71, 72, 1);
		this.sprite.addChild(this.snd_btn.sprite);
		a.EL = "btn_no_snd";
		this.btn_no_snd = new d("main_btn_no_snd", 557, 264, 64, 66, 1);
		this.sprite.addChild(this.btn_no_snd.sprite);
		a.EL = "btn_snd";
		this.btn_snd = new d("main_btn_snd", 557, 264, 64, 66, 1);
		this.sprite.addChild(this.btn_snd.sprite)
	}
	function Z() {
		this.sprite = new PIXI.DisplayObjectContainer;
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		a.GUI.addChild(this.sprite);
		this.sprite.visible = !1;
		a.EL = "back";
		this.back = new b("game_back", 0, 0, 640, 879, 1);
		this.sprite.addChild(this.back.sprite);
		a.EL = "close_btn";
		this.close_btn = new b("game_close_btn", 481, 263, 70, 71, 1);
		this.sprite.addChild(this.close_btn.sprite);
		a.EL = "btn_close";
		this.btn_close = new d("game_btn_close", 484, 266, 64, 66, 1);
		this.sprite.addChild(this.btn_close.sprite);
		a.EL = "no_snd_btn";
		this.no_snd_btn = new b("game_no_snd_btn", 556, 263, 70, 72, 1);
		this.sprite.addChild(this.no_snd_btn.sprite);
		a.EL = "snd_btn";
		this.snd_btn = new b("game_snd_btn", 556, 263, 71, 72, 1);
		this.sprite.addChild(this.snd_btn.sprite);
		a.EL = "btn_no_snd";
		this.btn_no_snd = new d("game_btn_no_snd", 559, 266, 64, 66, 1);
		this.sprite.addChild(this.btn_no_snd.sprite);
		a.EL = "restart_btn";
		this.restart_btn = new b("game_restart_btn", 406, 263, 70, 71, 1);
		this.sprite.addChild(this.restart_btn.sprite);
		a.EL = "btn_restart";
		this.btn_restart = new d("game_btn_restart", 409, 266, 63, 65, 1);
		this.sprite.addChild(this.btn_restart.sprite);
		a.EL = "btn_snd";
		this.btn_snd = new d("game_btn_snd", 559, 266, 64, 66, 1);
		this.sprite.addChild(this.btn_snd.sprite);
		a.EL = "txt_level";
		this.txt_level = new q("", 81, 278, 91, 32, 1);
		this.sprite.addChild(this.txt_level.sprite);
		a.EL = "game_place";
		this.game_place = new b("", 1, 348, 639, 519, 1);
		this.sprite.addChild(this.game_place.sprite);
		a.EL = "down_up";
		this.down_up = new b("game_down_up", 0, 867, 640, 271, 1);
		this.sprite.addChild(this.down_up.sprite);
		a.EL = "help_place";
		this.help_place = new b("", 1, 348, 639, 519, 1);
		this.sprite.addChild(this.help_place.sprite);
		a.EL = "back_arrow";
		this.back_arrow = new b("", 336, 86, 5, 5, 1);
		this.sprite.addChild(this.back_arrow.sprite);
		a.EL = "arrow";
		this.arrow = new b("", 336, 86, 5, 5, 1);
		this.sprite.addChild(this.arrow.sprite);
		a.EL = "hlp_back";
		this.hlp_back = new b("game_hlp_back", 88, 11, 255, 98, 1);
		this.sprite.addChild(this.hlp_back.sprite);
		a.EL = "hlp";
		this.hlp = new b("game_hlp", 321, 78, 115, 101, 1);
		this.sprite.addChild(this.hlp.sprite);
		a.EL = "txt_moves";
		this.txt_moves = new q("", 4, 886, 58, 48, 1);
		this.sprite.addChild(this.txt_moves.sprite);
		a.EL = "txt_step_1";
		this.txt_step_1 = new q("", 121, 872, 31, 16, 1);
		this.sprite.addChild(this.txt_step_1.sprite);
		a.EL = "txt_step_2";
		this.txt_step_2 = new q("", 107, 895, 31, 16, 1);
		this.sprite.addChild(this.txt_step_2.sprite);
		a.EL = "txt_step_3";
		this.txt_step_3 = new q("", 92, 919, 31, 16, 1);
		this.sprite.addChild(this.txt_step_3.sprite);
		a.EL = "princess_place";
		this.princess_place = new b("", 270, 182, 100, 150, 1);
		this.sprite.addChild(this.princess_place.sprite);
		a.EL = "hero_place";
		this.hero_place = new b("", 478, 824, 100, 100, 1);
		this.sprite.addChild(this.hero_place.sprite);
		a.EL = "go_place";
		this.go_place = new b("", 1, 348, 639, 519, 1);
		this.sprite.addChild(this.go_place.sprite);
		a.EL = "start_3";
		this.start_3 = new b("game_start_3", 0, 0, 170, 170, 1);
		this.sprite.addChild(this.start_3.sprite);
		a.EL = "finish_3";
		this.finish_3 = new b("game_finish_3", 0, 0, 170, 170, 1);
		this.sprite.addChild(this.finish_3.sprite);
		a.EL = "block_3";
		this.block_3 = new O("", 0, 0, 170, 170, 1);
		this.sprite.addChild(this.block_3.sprite);
		a.EL = "editor";
		this.editor = new b("", 0, 0, 0, 0, 1);
		this.sprite.addChild(this.editor.sprite);
		a.EL = "btn_edit";
		this.btn_edit = new d("game_btn_edit", 2, 941, 61, 62, 1);
		this.sprite.addChild(this.btn_edit.sprite);
		a.EL = "btn_edit_save";
		this.btn_edit_save = new d("game_btn_edit_save", 2, 1003, 61, 62, 1);
		this.sprite.addChild(this.btn_edit_save.sprite);
		a.EL = "btn_change";
		this.btn_change = new d("game_btn_change", 146, 1003, 61, 62, 1);
		this.sprite.addChild(this.btn_change.sprite);
		a.EL = "btn_edit_2";
		this.btn_edit_2 = new d("game_btn_edit_2", 80, 941, 61, 62, 1);
		this.sprite.addChild(this.btn_edit_2.sprite);
		a.EL = "btn_edit_3";
		this.btn_edit_3 = new d("game_btn_edit_3", 147, 941, 61, 62, 1);
		this.sprite.addChild(this.btn_edit_3.sprite);
		a.EL = "btn_edit_4";
		this.btn_edit_4 = new d("game_btn_edit_4", 214, 941, 61, 62, 1);
		this.sprite.addChild(this.btn_edit_4.sprite);
		a.EL = "btn_edit_erase";
		this.btn_edit_erase = new d("game_btn_edit_erase", 291, 941, 61, 62, 1);
		this.sprite.addChild(this.btn_edit_erase.sprite);
		a.EL = "btn_edit_nail";
		this.btn_edit_nail = new d("game_btn_edit_nail", 291, 1003, 61, 62, 1);
		this.sprite.addChild(this.btn_edit_nail.sprite);
		a.EL = "btn_edit_up";
		this.btn_edit_up = new d("game_btn_edit_up", 376, 941, 61, 62, 1);
		this.sprite.addChild(this.btn_edit_up.sprite);
		a.EL = "btn_edit_right";
		this.btn_edit_right = new d("game_btn_edit_right", 438, 941, 61, 62, 1);
		this.sprite.addChild(this.btn_edit_right.sprite);
		a.EL = "btn_edit_down";
		this.btn_edit_down = new d("game_btn_edit_down", 438, 1003, 61, 62, 1);
		this.sprite.addChild(this.btn_edit_down.sprite);
		a.EL = "btn_edit_left";
		this.btn_edit_left = new d("game_btn_edit_left", 376, 1003, 61, 62, 1);
		this.sprite.addChild(this.btn_edit_left.sprite);
		a.EL = "btn_edit_chain_up";
		this.btn_edit_chain_up = new d("game_btn_edit_chain_up", 517, 941, 61, 62, 1);
		this.sprite.addChild(this.btn_edit_chain_up.sprite);
		a.EL = "btn_edit_chain_right";
		this.btn_edit_chain_right = new d("game_btn_edit_chain_right", 579, 941, 61, 62, 1);
		this.sprite.addChild(this.btn_edit_chain_right.sprite);
		a.EL = "btn_edit_chain_down";
		this.btn_edit_chain_down = new d("game_btn_edit_chain_down", 579, 1003, 61, 62, 1);
		this.sprite.addChild(this.btn_edit_chain_down.sprite);
		a.EL = "btn_edit_chain_left";
		this.btn_edit_chain_left = new d("game_btn_edit_chain_left", 517, 1003, 61, 62, 1);
		this.sprite.addChild(this.btn_edit_chain_left.sprite);
		a.EL = "cursor";
		this.cursor = new b("game_cursor", 82, 755, 32, 32, 1);
		this.sprite.addChild(this.cursor.sprite);
		a.EL = "shad";
		this.shad = new b("game_shad", 4, 5, 64, 114, 1);
		this.sprite.addChild(this.shad.sprite);
		a.EL = "dym_place";
		this.dym_place = new b("", 0, 0, 0, 0, 1);
		this.sprite.addChild(this.dym_place.sprite);
		a.EL = "wrays";
		this.wrays = new b("game_wrays", 179, 348, 283, 283, 1);
		this.sprite.addChild(this.wrays.sprite);
		a.EL = "win";
		this.win = new b("", 0, 0, 0, 0, 1);
		this.sprite.addChild(this.win.sprite);
		a.EL = "prog";
		this.prog = new b("game_prog", 70, 117, 512, 819, 1);
		this.sprite.addChild(this.prog.sprite);
		a.EL = "compl";
		this.compl = new b("game_compl", 42, 195, 556, 758, 1);
		this.sprite.addChild(this.compl.sprite);
		a.EL = "new_close_btn_win";
		this.new_close_btn_win = new b("game_new_close_btn_win", 270, 802, 98, 101, 1);
		this.sprite.addChild(this.new_close_btn_win.sprite);
		a.EL = "btn_new_close_win";
		this.btn_new_close_win = new d("game_btn_new_close_win", 274, 807, 89, 92, 1);
		this.sprite.addChild(this.btn_new_close_win.sprite);
		a.EL = "zv1";
		this.zv1 = new b("game_zv1", 141, 179, 78, 76, 1);
		this.sprite.addChild(this.zv1.sprite);
		a.EL = "zv2";
		this.zv2 = new b("game_zv2", 283, 157, 78, 76, 1);
		this.sprite.addChild(this.zv2.sprite);
		a.EL = "zv3";
		this.zv3 = new b("game_zv3", 438, 179, 78, 76, 1);
		this.sprite.addChild(this.zv3.sprite);
		a.EL = "close_btn_win";
		this.close_btn_win = new b("game_close_btn_win", 441, 742, 98, 101, 1);
		this.sprite.addChild(this.close_btn_win.sprite);
		a.EL = "btn_close_win";
		this.btn_close_win = new d("game_btn_close_win", 445, 747, 89, 92, 1);
		this.sprite.addChild(this.btn_close_win.sprite);
		a.EL = "more_btn";
		this.more_btn = new b("game_more_btn", 123, 737, 97, 100, 1);
		this.sprite.addChild(this.more_btn.sprite);
		a.EL = "btn_more";
		this.btn_more = new d("game_btn_more", 127, 741, 89, 92, 1);
		this.sprite.addChild(this.btn_more.sprite);
		a.EL = "next_btn_win";
		this.next_btn_win = new b("game_next_btn_win", 250, 742, 155, 159, 1);
		this.sprite.addChild(this.next_btn_win.sprite);
		a.EL = "btn_next_win";
		this.btn_next_win = new d("game_btn_next_win", 256, 750, 140, 144, 1);
		this.sprite.addChild(this.btn_next_win.sprite);
		a.EL = "sled";
		this.sled = new b("game_sled", 559, 439, 50, 50, 1);
		this.sprite.addChild(this.sled.sprite);
		this.back_arrow.set_parent(this.help_place);
		this.arrow.set_parent(this.help_place);
		this.hlp_back.set_parent(this.back_arrow);
		this.hlp.set_parent(this.arrow);
		this.start_3.set_parent(this.game_place);
		this.finish_3.set_parent(this.game_place);
		this.btn_edit_save.set_parent(this.editor);
		this.btn_change.set_parent(this.editor);
		this.btn_edit_2.set_parent(this.editor);
		this.btn_edit_3.set_parent(this.editor);
		this.btn_edit_4.set_parent(this.editor);
		this.btn_edit_erase.set_parent(this.editor);
		this.btn_edit_nail.set_parent(this.editor);
		this.btn_edit_up.set_parent(this.editor);
		this.btn_edit_right.set_parent(this.editor);
		this.btn_edit_down.set_parent(this.editor);
		this.btn_edit_left.set_parent(this.editor);
		this.btn_edit_chain_up.set_parent(this.editor);
		this.btn_edit_chain_right.set_parent(this.editor);
		this.btn_edit_chain_down.set_parent(this.editor);
		this.btn_edit_chain_left.set_parent(this.editor);
		this.cursor.set_parent(this.editor);
		this.prog.set_parent(this.win);
		this.compl.set_parent(this.win);
		this.new_close_btn_win.set_parent(this.compl);
		this.btn_new_close_win.set_parent(this.compl);
		this.zv1.set_parent(this.win);
		this.zv2.set_parent(this.win);
		this.zv3.set_parent(this.win);
		this.close_btn_win.set_parent(this.prog);
		this.btn_close_win.set_parent(this.prog);
		this.more_btn.set_parent(this.prog);
		this.btn_more.set_parent(this.prog);
		this.next_btn_win.set_parent(this.prog);
		this.btn_next_win.set_parent(this.prog);
		this.sled.set_parent(this.dym_place)
	}
	function O(d, f, k, e, g, h) {
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		var c = [d, f, k, e, g, h];
		this.make_copy = function() {
			a.WND = this.WND;
			a.EL = this.EL;
			a.ID = this.ID + 1;
			return new O(c[0], c[1], c[2], c[3], c[4], c[5])
		};
		this.uni_width = e;
		this.uni_height = g;
		this.sprite = new PIXI.DisplayObjectContainer;
		a.GUI.addChild(this.sprite);
		this.WND = a.WND;
		this.EL = a.EL;
		this.ID = a.ID;
		a.EL = "blocks_back_3";
		this.blocks_back_3 = new b("game_blocks_back_3", 0, 0, 170, 170, 1);
		this.sprite.addChild(this.blocks_back_3.sprite);
		a.EL = "path_3_3";
		this.path_3_3 = new b("game_path_3_3", 0, 58, 96, 48, 1);
		this.sprite.addChild(this.path_3_3.sprite);
		a.EL = "path_2_3";
		this.path_2_3 = new b("game_path_2_3", 61, 55, 49, 115, 1);
		this.sprite.addChild(this.path_2_3.sprite);
		a.EL = "path_1_3";
		this.path_1_3 = new b("game_path_1_3", 65, 58, 105, 40, 1);
		this.sprite.addChild(this.path_1_3.sprite);
		a.EL = "path_0_3";
		this.path_0_3 = new b("game_path_0_3", 64, 0, 43, 95, 1);
		this.sprite.addChild(this.path_0_3.sprite);
		a.EL = "chain_0_3";
		this.chain_0_3 = new b("game_chain_0_3", 20, 0, 129, 29, 1);
		this.sprite.addChild(this.chain_0_3.sprite);
		a.EL = "chain_1_3";
		this.chain_1_3 = new b("game_chain_1_3", 139, 28, 31, 103, 1);
		this.sprite.addChild(this.chain_1_3.sprite);
		a.EL = "chain_2_3";
		this.chain_2_3 = new b("game_chain_2_3", 29, 137, 117, 33, 1);
		this.sprite.addChild(this.chain_2_3.sprite);
		a.EL = "chain_3_3";
		this.chain_3_3 = new b("game_chain_3_3", 0, 32, 29, 117, 1);
		this.sprite.addChild(this.chain_3_3.sprite);
		a.EL = "nail_3";
		this.nail_3 = new b("game_nail_3", 22, 19, 127, 124, 1);
		this.sprite.addChild(this.nail_3.sprite);
		this.path_3_3.set_parent(this.blocks_back_3);
		this.path_2_3.set_parent(this.blocks_back_3);
		this.path_1_3.set_parent(this.blocks_back_3);
		this.path_0_3.set_parent(this.blocks_back_3);
		this.chain_0_3.set_parent(this.blocks_back_3);
		this.chain_1_3.set_parent(this.blocks_back_3);
		this.chain_2_3.set_parent(this.blocks_back_3);
		this.chain_3_3.set_parent(this.blocks_back_3);
		this.sprite.position.x = f;
		this.sprite.position.y = k;
		this.set_parent = function(b) {
			b.sprite.addChild(this.sprite);
			b = this.sprite;
			for (var c = 0, e = 0; b = b.parent;) b != a.GUI && (c += b.position.x, e += b.position.y);
			this.sprite.position.x -= c;
			this.sprite.position.y -= e
		}
	}
	function $() {
		this.sprite = new PIXI.DisplayObjectContainer;
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		a.GUI.addChild(this.sprite);
		this.sprite.visible = !1;
		a.EL = "back";
		this.back = new b("levels_back", 0, 800, 637, 338, 1);
		this.sprite.addChild(this.back.sprite);
		a.EL = "back_second";
		this.back_second = new b("levels_back_second", 153, 254, 317, 59, 1);
		this.sprite.addChild(this.back_second.sprite);
		a.EL = "place";
		this.place = new b("", 31, 229, 575, 646, 1);
		this.sprite.addChild(this.place.sprite);
		a.EL = "lev_btn_20";
		this.lev_btn_20 = new b("levels_lev_btn_20", 503, 643, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_20.sprite);
		a.EL = "lev_btn_19";
		this.lev_btn_19 = new b("levels_lev_btn_19", 390, 643, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_19.sprite);
		a.EL = "lev_btn_18";
		this.lev_btn_18 = new b("levels_lev_btn_18", 279, 643, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_18.sprite);
		a.EL = "lev_btn_17";
		this.lev_btn_17 = new b("levels_lev_btn_17", 166, 643, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_17.sprite);
		a.EL = "lev_btn_16";
		this.lev_btn_16 = new b("levels_lev_btn_16", 54, 643, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_16.sprite);
		a.EL = "lev_btn_15";
		this.lev_btn_15 = new b("levels_lev_btn_15", 503, 544, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_15.sprite);
		a.EL = "lev_btn_14";
		this.lev_btn_14 = new b("levels_lev_btn_14", 390, 544, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_14.sprite);
		a.EL = "lev_btn_13";
		this.lev_btn_13 = new b("levels_lev_btn_13", 279, 544, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_13.sprite);
		a.EL = "lev_btn_12";
		this.lev_btn_12 = new b("levels_lev_btn_12", 166, 544, 85, 89, 1);
		this.sprite.addChild(this.lev_btn_12.sprite);
		a.EL = "lev_btn_11";
		this.lev_btn_11 = new b("levels_lev_btn_11", 54, 543, 85, 89, 1);
		this.sprite.addChild(this.lev_btn_11.sprite);
		a.EL = "lev_btn_10";
		this.lev_btn_10 = new b("levels_lev_btn_10", 503, 446, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_10.sprite);
		a.EL = "lev_btn_9";
		this.lev_btn_9 = new b("levels_lev_btn_9", 390, 446, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_9.sprite);
		a.EL = "lev_btn_8";
		this.lev_btn_8 = new b("levels_lev_btn_8", 279, 446, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_8.sprite);
		a.EL = "lev_btn_7";
		this.lev_btn_7 = new b("levels_lev_btn_7", 166, 446, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_7.sprite);
		a.EL = "lev_btn_6";
		this.lev_btn_6 = new b("levels_lev_btn_6", 54, 446, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_6.sprite);
		a.EL = "lev_btn_5";
		this.lev_btn_5 = new b("levels_lev_btn_5", 502, 348, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_5.sprite);
		a.EL = "lev_btn_4";
		this.lev_btn_4 = new b("levels_lev_btn_4", 390, 348, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_4.sprite);
		a.EL = "lev_btn_3";
		this.lev_btn_3 = new b("levels_lev_btn_3", 279, 348, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_3.sprite);
		a.EL = "lev_btn_2";
		this.lev_btn_2 = new b("levels_lev_btn_2", 167, 348, 86, 88, 1);
		this.sprite.addChild(this.lev_btn_2.sprite);
		a.EL = "lev_btn_1";
		this.lev_btn_1 = new b("levels_lev_btn_1", 54, 348, 85, 88, 1);
		this.sprite.addChild(this.lev_btn_1.sprite);
		a.EL = "z_1_1";
		this.z_1_1 = new b("levels_z_1_1", 64, 409, 12, 11, 1);
		this.sprite.addChild(this.z_1_1.sprite);
		a.EL = "z_1_2";
		this.z_1_2 = new b("levels_z_1_2", 88, 409, 12, 11, 1);
		this.sprite.addChild(this.z_1_2.sprite);
		a.EL = "z_1_3";
		this.z_1_3 = new b("levels_z_1_3", 112, 409, 12, 11, 1);
		this.sprite.addChild(this.z_1_3.sprite);
		a.EL = "z_2_1";
		this.z_2_1 = new b("levels_z_2_1", 178, 409, 12, 11, 1);
		this.sprite.addChild(this.z_2_1.sprite);
		a.EL = "z_2_2";
		this.z_2_2 = new b("levels_z_2_2", 202, 409, 12, 11, 1);
		this.sprite.addChild(this.z_2_2.sprite);
		a.EL = "z_2_3";
		this.z_2_3 = new b("levels_z_2_3", 225, 409, 12, 11, 1);
		this.sprite.addChild(this.z_2_3.sprite);
		a.EL = "z_3_1";
		this.z_3_1 = new b("levels_z_3_1", 289, 409, 12, 11, 1);
		this.sprite.addChild(this.z_3_1.sprite);
		a.EL = "z_3_2";
		this.z_3_2 = new b("levels_z_3_2", 313, 409, 12, 11, 1);
		this.sprite.addChild(this.z_3_2.sprite);
		a.EL = "z_3_3";
		this.z_3_3 = new b("levels_z_3_3", 337, 409, 12, 11, 1);
		this.sprite.addChild(this.z_3_3.sprite);
		a.EL = "z_4_1";
		this.z_4_1 = new b("levels_z_4_1", 400, 409, 12, 11, 1);
		this.sprite.addChild(this.z_4_1.sprite);
		a.EL = "z_4_2";
		this.z_4_2 = new b("levels_z_4_2", 424, 409, 12, 11, 1);
		this.sprite.addChild(this.z_4_2.sprite);
		a.EL = "z_4_3";
		this.z_4_3 = new b("levels_z_4_3", 448, 409, 12, 11, 1);
		this.sprite.addChild(this.z_4_3.sprite);
		a.EL = "z_5_1";
		this.z_5_1 = new b("levels_z_5_1", 512, 409, 12, 11, 1);
		this.sprite.addChild(this.z_5_1.sprite);
		a.EL = "z_5_2";
		this.z_5_2 = new b("levels_z_5_2", 536, 409, 12, 11, 1);
		this.sprite.addChild(this.z_5_2.sprite);
		a.EL = "z_5_3";
		this.z_5_3 = new b("levels_z_5_3", 560, 409, 12, 11, 1);
		this.sprite.addChild(this.z_5_3.sprite);
		a.EL = "z_6_1";
		this.z_6_1 = new b("levels_z_6_1", 64, 507, 12, 11, 1);
		this.sprite.addChild(this.z_6_1.sprite);
		a.EL = "z_6_2";
		this.z_6_2 = new b("levels_z_6_2", 88, 507, 12, 11, 1);
		this.sprite.addChild(this.z_6_2.sprite);
		a.EL = "z_6_3";
		this.z_6_3 = new b("levels_z_6_3", 112, 507, 12, 11, 1);
		this.sprite.addChild(this.z_6_3.sprite);
		a.EL = "z_7_1";
		this.z_7_1 = new b("levels_z_7_1", 177, 507, 12, 11, 1);
		this.sprite.addChild(this.z_7_1.sprite);
		a.EL = "z_7_2";
		this.z_7_2 = new b("levels_z_7_2", 201, 507, 12, 11, 1);
		this.sprite.addChild(this.z_7_2.sprite);
		a.EL = "z_7_3";
		this.z_7_3 = new b("levels_z_7_3", 224, 507, 12, 11, 1);
		this.sprite.addChild(this.z_7_3.sprite);
		a.EL = "z_8_1";
		this.z_8_1 = new b("levels_z_8_1", 290, 507, 12, 11, 1);
		this.sprite.addChild(this.z_8_1.sprite);
		a.EL = "z_8_2";
		this.z_8_2 = new b("levels_z_8_2", 314, 507, 12, 11, 1);
		this.sprite.addChild(this.z_8_2.sprite);
		a.EL = "z_8_3";
		this.z_8_3 = new b("levels_z_8_3", 337, 507, 12, 11, 1);
		this.sprite.addChild(this.z_8_3.sprite);
		a.EL = "z_9_1";
		this.z_9_1 = new b("levels_z_9_1", 400, 507, 12, 11, 1);
		this.sprite.addChild(this.z_9_1.sprite);
		a.EL = "z_9_2";
		this.z_9_2 = new b("levels_z_9_2", 424, 507, 12, 11, 1);
		this.sprite.addChild(this.z_9_2.sprite);
		a.EL = "z_9_3";
		this.z_9_3 = new b("levels_z_9_3", 448, 507, 12, 11, 1);
		this.sprite.addChild(this.z_9_3.sprite);
		a.EL = "z_10_1";
		this.z_10_1 = new b("levels_z_10_1", 514, 507, 12, 11, 1);
		this.sprite.addChild(this.z_10_1.sprite);
		a.EL = "z_10_2";
		this.z_10_2 = new b("levels_z_10_2", 538, 507, 12, 11, 1);
		this.sprite.addChild(this.z_10_2.sprite);
		a.EL = "z_10_3";
		this.z_10_3 = new b("levels_z_10_3", 561, 507, 12, 11, 1);
		this.sprite.addChild(this.z_10_3.sprite);
		a.EL = "z_11_1";
		this.z_11_1 = new b("levels_z_11_1", 64, 604, 12, 11, 1);
		this.sprite.addChild(this.z_11_1.sprite);
		a.EL = "z_11_2";
		this.z_11_2 = new b("levels_z_11_2", 88, 604, 12, 11, 1);
		this.sprite.addChild(this.z_11_2.sprite);
		a.EL = "z_11_3";
		this.z_11_3 = new b("levels_z_11_3", 112, 604, 12, 11, 1);
		this.sprite.addChild(this.z_11_3.sprite);
		a.EL = "z_12_1";
		this.z_12_1 = new b("levels_z_12_1", 176, 605, 12, 11, 1);
		this.sprite.addChild(this.z_12_1.sprite);
		a.EL = "z_12_2";
		this.z_12_2 = new b("levels_z_12_2", 200, 605, 12, 11, 1);
		this.sprite.addChild(this.z_12_2.sprite);
		a.EL = "z_12_3";
		this.z_12_3 = new b("levels_z_12_3", 224, 605, 12, 11, 1);
		this.sprite.addChild(this.z_12_3.sprite);
		a.EL = "z_13_1";
		this.z_13_1 = new b("levels_z_13_1", 289, 605, 12, 11, 1);
		this.sprite.addChild(this.z_13_1.sprite);
		a.EL = "z_13_2";
		this.z_13_2 = new b("levels_z_13_2", 313, 605, 12, 11, 1);
		this.sprite.addChild(this.z_13_2.sprite);
		a.EL = "z_13_3";
		this.z_13_3 = new b("levels_z_13_3", 337, 605, 12, 11, 1);
		this.sprite.addChild(this.z_13_3.sprite);
		a.EL = "z_14_1";
		this.z_14_1 = new b("levels_z_14_1", 400, 605, 12, 11, 1);
		this.sprite.addChild(this.z_14_1.sprite);
		a.EL = "z_14_2";
		this.z_14_2 = new b("levels_z_14_2", 424, 605, 12, 11, 1);
		this.sprite.addChild(this.z_14_2.sprite);
		a.EL = "z_14_3";
		this.z_14_3 = new b("levels_z_14_3", 448, 605, 12, 11, 1);
		this.sprite.addChild(this.z_14_3.sprite);
		a.EL = "z_15_1";
		this.z_15_1 = new b("levels_z_15_1", 513, 605, 12, 11, 1);
		this.sprite.addChild(this.z_15_1.sprite);
		a.EL = "z_15_2";
		this.z_15_2 = new b("levels_z_15_2", 537, 605, 12, 11, 1);
		this.sprite.addChild(this.z_15_2.sprite);
		a.EL = "z_15_3";
		this.z_15_3 = new b("levels_z_15_3", 561, 605, 12, 11, 1);
		this.sprite.addChild(this.z_15_3.sprite);
		a.EL = "z_16_1";
		this.z_16_1 = new b("levels_z_16_1", 64, 704, 12, 11, 1);
		this.sprite.addChild(this.z_16_1.sprite);
		a.EL = "z_16_2";
		this.z_16_2 = new b("levels_z_16_2", 88, 704, 12, 11, 1);
		this.sprite.addChild(this.z_16_2.sprite);
		a.EL = "z_16_3";
		this.z_16_3 = new b("levels_z_16_3", 112, 704, 12, 11, 1);
		this.sprite.addChild(this.z_16_3.sprite);
		a.EL = "z_17_1";
		this.z_17_1 = new b("levels_z_17_1", 176, 704, 12, 11, 1);
		this.sprite.addChild(this.z_17_1.sprite);
		a.EL = "z_17_2";
		this.z_17_2 = new b("levels_z_17_2", 200, 704, 12, 11, 1);
		this.sprite.addChild(this.z_17_2.sprite);
		a.EL = "z_17_3";
		this.z_17_3 = new b("levels_z_17_3", 224, 704, 12, 11, 1);
		this.sprite.addChild(this.z_17_3.sprite);
		a.EL = "z_18_1";
		this.z_18_1 = new b("levels_z_18_1", 289, 704, 12, 11, 1);
		this.sprite.addChild(this.z_18_1.sprite);
		a.EL = "z_18_2";
		this.z_18_2 = new b("levels_z_18_2", 313, 704, 12, 11, 1);
		this.sprite.addChild(this.z_18_2.sprite);
		a.EL = "z_18_3";
		this.z_18_3 = new b("levels_z_18_3", 337, 704, 12, 11, 1);
		this.sprite.addChild(this.z_18_3.sprite);
		a.EL = "z_19_1";
		this.z_19_1 = new b("levels_z_19_1", 400, 704, 12, 11, 1);
		this.sprite.addChild(this.z_19_1.sprite);
		a.EL = "z_19_2";
		this.z_19_2 = new b("levels_z_19_2", 424, 704, 12, 11, 1);
		this.sprite.addChild(this.z_19_2.sprite);
		a.EL = "z_19_3";
		this.z_19_3 = new b("levels_z_19_3", 448, 704, 12, 11, 1);
		this.sprite.addChild(this.z_19_3.sprite);
		a.EL = "z_20_1";
		this.z_20_1 = new b("levels_z_20_1", 513, 704, 12, 11, 1);
		this.sprite.addChild(this.z_20_1.sprite);
		a.EL = "z_20_2";
		this.z_20_2 = new b("levels_z_20_2", 537, 704, 12, 11, 1);
		this.sprite.addChild(this.z_20_2.sprite);
		a.EL = "z_20_3";
		this.z_20_3 = new b("levels_z_20_3", 561, 704, 12, 11, 1);
		this.sprite.addChild(this.z_20_3.sprite);
		a.EL = "btn_lev_20";
		this.btn_lev_20 = new d("levels_btn_lev_20", 507, 647, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_20.sprite);
		a.EL = "btn_lev_19";
		this.btn_lev_19 = new d("levels_btn_lev_19", 394, 647, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_19.sprite);
		a.EL = "btn_lev_18";
		this.btn_lev_18 = new d("levels_btn_lev_18", 283, 647, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_18.sprite);
		a.EL = "btn_lev_17";
		this.btn_lev_17 = new d("levels_btn_lev_17", 170, 647, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_17.sprite);
		a.EL = "btn_lev_16";
		this.btn_lev_16 = new d("levels_btn_lev_16", 58, 647, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_16.sprite);
		a.EL = "btn_lev_15";
		this.btn_lev_15 = new d("levels_btn_lev_15", 506, 548, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_15.sprite);
		a.EL = "btn_lev_14";
		this.btn_lev_14 = new d("levels_btn_lev_14", 394, 548, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_14.sprite);
		a.EL = "btn_lev_13";
		this.btn_lev_13 = new d("levels_btn_lev_13", 283, 548, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_13.sprite);
		a.EL = "btn_lev_12";
		this.btn_lev_12 = new d("levels_btn_lev_12", 170, 548, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_12.sprite);
		a.EL = "btn_lev_11";
		this.btn_lev_11 = new d("levels_btn_lev_11", 57, 548, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_11.sprite);
		a.EL = "btn_lev_10";
		this.btn_lev_10 = new d("levels_btn_lev_10", 507, 450, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_10.sprite);
		a.EL = "btn_lev_9";
		this.btn_lev_9 = new d("levels_btn_lev_9", 394, 450, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_9.sprite);
		a.EL = "btn_lev_8";
		this.btn_lev_8 = new d("levels_btn_lev_8", 283, 450, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_8.sprite);
		a.EL = "btn_lev_7";
		this.btn_lev_7 = new d("levels_btn_lev_7", 170, 450, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_7.sprite);
		a.EL = "btn_lev_6";
		this.btn_lev_6 = new d("levels_btn_lev_6", 58, 450, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_6.sprite);
		a.EL = "btn_lev_5";
		this.btn_lev_5 = new d("levels_btn_lev_5", 507, 352, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_5.sprite);
		a.EL = "btn_lev_4";
		this.btn_lev_4 = new d("levels_btn_lev_4", 394, 352, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_4.sprite);
		a.EL = "btn_lev_3";
		this.btn_lev_3 = new d("levels_btn_lev_3", 283, 352, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_3.sprite);
		a.EL = "btn_lev_2";
		this.btn_lev_2 = new d("levels_btn_lev_2", 170, 352, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_2.sprite);
		a.EL = "btn_lev_1";
		this.btn_lev_1 = new d("levels_btn_lev_1", 58, 352, 78, 80, 1);
		this.sprite.addChild(this.btn_lev_1.sprite);
		a.EL = "zz_1_1";
		this.zz_1_1 = new b("levels_zz_1_1", 67, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_1_1.sprite);
		a.EL = "zz_1_2";
		this.zz_1_2 = new b("levels_zz_1_2", 89, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_1_2.sprite);
		a.EL = "zz_1_3";
		this.zz_1_3 = new b("levels_zz_1_3", 111, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_1_3.sprite);
		a.EL = "zz_2_1";
		this.zz_2_1 = new b("levels_zz_2_1", 179, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_2_1.sprite);
		a.EL = "zz_2_2";
		this.zz_2_2 = new b("levels_zz_2_2", 201, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_2_2.sprite);
		a.EL = "zz_2_3";
		this.zz_2_3 = new b("levels_zz_2_3", 223, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_2_3.sprite);
		a.EL = "zz_3_1";
		this.zz_3_1 = new b("levels_zz_3_1", 292, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_3_1.sprite);
		a.EL = "zz_3_2";
		this.zz_3_2 = new b("levels_zz_3_2", 314, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_3_2.sprite);
		a.EL = "zz_3_3";
		this.zz_3_3 = new b("levels_zz_3_3", 336, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_3_3.sprite);
		a.EL = "zz_4_1";
		this.zz_4_1 = new b("levels_zz_4_1", 403, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_4_1.sprite);
		a.EL = "zz_4_2";
		this.zz_4_2 = new b("levels_zz_4_2", 425, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_4_2.sprite);
		a.EL = "zz_4_3";
		this.zz_4_3 = new b("levels_zz_4_3", 447, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_4_3.sprite);
		a.EL = "zz_5_1";
		this.zz_5_1 = new b("levels_zz_5_1", 516, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_5_1.sprite);
		a.EL = "zz_5_2";
		this.zz_5_2 = new b("levels_zz_5_2", 538, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_5_2.sprite);
		a.EL = "zz_5_3";
		this.zz_5_3 = new b("levels_zz_5_3", 560, 407, 11, 10, 1);
		this.sprite.addChild(this.zz_5_3.sprite);
		a.EL = "zz_6_1";
		this.zz_6_1 = new b("levels_zz_6_1", 67, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_6_1.sprite);
		a.EL = "zz_6_2";
		this.zz_6_2 = new b("levels_zz_6_2", 89, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_6_2.sprite);
		a.EL = "zz_6_3";
		this.zz_6_3 = new b("levels_zz_6_3", 111, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_6_3.sprite);
		a.EL = "zz_7_1";
		this.zz_7_1 = new b("levels_zz_7_1", 179, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_7_1.sprite);
		a.EL = "zz_7_2";
		this.zz_7_2 = new b("levels_zz_7_2", 201, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_7_2.sprite);
		a.EL = "zz_7_3";
		this.zz_7_3 = new b("levels_zz_7_3", 223, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_7_3.sprite);
		a.EL = "zz_8_1";
		this.zz_8_1 = new b("levels_zz_8_1", 292, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_8_1.sprite);
		a.EL = "zz_8_2";
		this.zz_8_2 = new b("levels_zz_8_2", 314, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_8_2.sprite);
		a.EL = "zz_8_3";
		this.zz_8_3 = new b("levels_zz_8_3", 336, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_8_3.sprite);
		a.EL = "zz_9_1";
		this.zz_9_1 = new b("levels_zz_9_1", 403, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_9_1.sprite);
		a.EL = "zz_9_2";
		this.zz_9_2 = new b("levels_zz_9_2", 425, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_9_2.sprite);
		a.EL = "zz_9_3";
		this.zz_9_3 = new b("levels_zz_9_3", 447, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_9_3.sprite);
		a.EL = "zz_10_1";
		this.zz_10_1 = new b("levels_zz_10_1", 516, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_10_1.sprite);
		a.EL = "zz_10_2";
		this.zz_10_2 = new b("levels_zz_10_2", 538, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_10_2.sprite);
		a.EL = "zz_10_3";
		this.zz_10_3 = new b("levels_zz_10_3", 560, 505, 11, 10, 1);
		this.sprite.addChild(this.zz_10_3.sprite);
		a.EL = "zz_11_1";
		this.zz_11_1 = new b("levels_zz_11_1", 66, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_11_1.sprite);
		a.EL = "zz_11_2";
		this.zz_11_2 = new b("levels_zz_11_2", 88, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_11_2.sprite);
		a.EL = "zz_11_3";
		this.zz_11_3 = new b("levels_zz_11_3", 110, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_11_3.sprite);
		a.EL = "zz_12_1";
		this.zz_12_1 = new b("levels_zz_12_1", 179, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_12_1.sprite);
		a.EL = "zz_12_2";
		this.zz_12_2 = new b("levels_zz_12_2", 201, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_12_2.sprite);
		a.EL = "zz_12_3";
		this.zz_12_3 = new b("levels_zz_12_3", 223, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_12_3.sprite);
		a.EL = "zz_13_1";
		this.zz_13_1 = new b("levels_zz_13_1", 292, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_13_1.sprite);
		a.EL = "zz_13_2";
		this.zz_13_2 = new b("levels_zz_13_2", 314, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_13_2.sprite);
		a.EL = "zz_13_3";
		this.zz_13_3 = new b("levels_zz_13_3", 336, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_13_3.sprite);
		a.EL = "zz_14_1";
		this.zz_14_1 = new b("levels_zz_14_1", 403, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_14_1.sprite);
		a.EL = "zz_14_2";
		this.zz_14_2 = new b("levels_zz_14_2", 425, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_14_2.sprite);
		a.EL = "zz_14_3";
		this.zz_14_3 = new b("levels_zz_14_3", 447, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_14_3.sprite);
		a.EL = "zz_15_1";
		this.zz_15_1 = new b("levels_zz_15_1", 515, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_15_1.sprite);
		a.EL = "zz_15_2";
		this.zz_15_2 = new b("levels_zz_15_2", 537, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_15_2.sprite);
		a.EL = "zz_15_3";
		this.zz_15_3 = new b("levels_zz_15_3", 559, 603, 11, 10, 1);
		this.sprite.addChild(this.zz_15_3.sprite);
		a.EL = "zz_16_1";
		this.zz_16_1 = new b("levels_zz_16_1", 67, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_16_1.sprite);
		a.EL = "zz_16_2";
		this.zz_16_2 = new b("levels_zz_16_2", 89, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_16_2.sprite);
		a.EL = "zz_16_3";
		this.zz_16_3 = new b("levels_zz_16_3", 111, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_16_3.sprite);
		a.EL = "zz_17_1";
		this.zz_17_1 = new b("levels_zz_17_1", 179, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_17_1.sprite);
		a.EL = "zz_17_2";
		this.zz_17_2 = new b("levels_zz_17_2", 201, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_17_2.sprite);
		a.EL = "zz_17_3";
		this.zz_17_3 = new b("levels_zz_17_3", 223, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_17_3.sprite);
		a.EL = "zz_18_1";
		this.zz_18_1 = new b("levels_zz_18_1", 292, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_18_1.sprite);
		a.EL = "zz_18_2";
		this.zz_18_2 = new b("levels_zz_18_2", 314, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_18_2.sprite);
		a.EL = "zz_18_3";
		this.zz_18_3 = new b("levels_zz_18_3", 336, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_18_3.sprite);
		a.EL = "zz_19_1";
		this.zz_19_1 = new b("levels_zz_19_1", 403, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_19_1.sprite);
		a.EL = "zz_19_2";
		this.zz_19_2 = new b("levels_zz_19_2", 425, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_19_2.sprite);
		a.EL = "zz_19_3";
		this.zz_19_3 = new b("levels_zz_19_3", 447, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_19_3.sprite);
		a.EL = "zz_20_1";
		this.zz_20_1 = new b("levels_zz_20_1", 516, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_20_1.sprite);
		a.EL = "zz_20_2";
		this.zz_20_2 = new b("levels_zz_20_2", 538, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_20_2.sprite);
		a.EL = "zz_20_3";
		this.zz_20_3 = new b("levels_zz_20_3", 560, 702, 11, 10, 1);
		this.sprite.addChild(this.zz_20_3.sprite);
		a.EL = "lock_20";
		this.lock_20 = new b("levels_lock_20", 507, 647, 78, 80, 1);
		this.sprite.addChild(this.lock_20.sprite);
		a.EL = "lock_19";
		this.lock_19 = new b("levels_lock_19", 394, 647, 78, 80, 1);
		this.sprite.addChild(this.lock_19.sprite);
		a.EL = "lock_18";
		this.lock_18 = new b("levels_lock_18", 283, 647, 78, 80, 1);
		this.sprite.addChild(this.lock_18.sprite);
		a.EL = "lock_17";
		this.lock_17 = new b("levels_lock_17", 170, 647, 78, 80, 1);
		this.sprite.addChild(this.lock_17.sprite);
		a.EL = "lock_16";
		this.lock_16 = new b("levels_lock_16", 58, 647, 78, 80, 1);
		this.sprite.addChild(this.lock_16.sprite);
		a.EL = "lock_15";
		this.lock_15 = new b("levels_lock_15", 507, 548, 78, 80, 1);
		this.sprite.addChild(this.lock_15.sprite);
		a.EL = "lock_14";
		this.lock_14 = new b("levels_lock_14", 394, 548, 78, 80, 1);
		this.sprite.addChild(this.lock_14.sprite);
		a.EL = "lock_13";
		this.lock_13 = new b("levels_lock_13", 283, 548, 78, 80, 1);
		this.sprite.addChild(this.lock_13.sprite);
		a.EL = "lock_12";
		this.lock_12 = new b("levels_lock_12", 170, 548, 78, 80, 1);
		this.sprite.addChild(this.lock_12.sprite);
		a.EL = "lock_11";
		this.lock_11 = new b("levels_lock_11", 57, 548, 78, 80, 1);
		this.sprite.addChild(this.lock_11.sprite);
		a.EL = "lock_10";
		this.lock_10 = new b("levels_lock_10", 507, 450, 78, 80, 1);
		this.sprite.addChild(this.lock_10.sprite);
		a.EL = "lock_9";
		this.lock_9 = new b("levels_lock_9", 394, 450, 78, 80, 1);
		this.sprite.addChild(this.lock_9.sprite);
		a.EL = "lock_8";
		this.lock_8 = new b("levels_lock_8", 285, 450, 78, 80, 1);
		this.sprite.addChild(this.lock_8.sprite);
		a.EL = "lock_7";
		this.lock_7 = new b("levels_lock_7", 170, 450, 78, 80, 1);
		this.sprite.addChild(this.lock_7.sprite);
		a.EL = "lock_6";
		this.lock_6 = new b("levels_lock_6", 57, 450, 78, 80, 1);
		this.sprite.addChild(this.lock_6.sprite);
		a.EL = "lock_5";
		this.lock_5 = new b("levels_lock_5", 506, 352, 78, 80, 1);
		this.sprite.addChild(this.lock_5.sprite);
		a.EL = "lock_4";
		this.lock_4 = new b("levels_lock_4", 394, 352, 78, 80, 1);
		this.sprite.addChild(this.lock_4.sprite);
		a.EL = "lock_3";
		this.lock_3 = new b("levels_lock_3", 283, 352, 78, 80, 1);
		this.sprite.addChild(this.lock_3.sprite);
		a.EL = "lock_2";
		this.lock_2 = new b("levels_lock_2", 170, 352, 78, 80, 1);
		this.sprite.addChild(this.lock_2.sprite);
		a.EL = "lock_1";
		this.lock_1 = new b("levels_lock_1", 58, 352, 78, 80, 1);
		this.sprite.addChild(this.lock_1.sprite);
		a.EL = "btn";
		this.btn = new P("", 26, 0, 5, 12, 1);
		this.sprite.addChild(this.btn.sprite);
		a.EL = "no_snd_btn";
		this.no_snd_btn = new b("levels_no_snd_btn", 549, 201, 70, 72, 1);
		this.sprite.addChild(this.no_snd_btn.sprite);
		a.EL = "snd_btn";
		this.snd_btn = new b("levels_snd_btn", 549, 201, 71, 72, 1);
		this.sprite.addChild(this.snd_btn.sprite);
		a.EL = "btn_no_snd";
		this.btn_no_snd = new d("levels_btn_no_snd", 552, 204, 64, 66, 1);
		this.sprite.addChild(this.btn_no_snd.sprite);
		a.EL = "btn_snd";
		this.btn_snd = new d("levels_btn_snd", 552, 204, 64, 66, 1);
		this.sprite.addChild(this.btn_snd.sprite);
		a.EL = "close_btn";
		this.close_btn = new b("levels_close_btn", 263, 737, 121, 125, 1);
		this.sprite.addChild(this.close_btn.sprite);
		a.EL = "btn_close";
		this.btn_close = new d("levels_btn_close", 268, 743, 111, 114, 1);
		this.sprite.addChild(this.btn_close.sprite);
		a.EL = "btn_add";
		this.btn_add = new d("levels_btn_add", 26, 46, 5, 16, 1);
		this.sprite.addChild(this.btn_add.sprite);
		this.z_1_1.set_parent(this.lev_btn_1);
		this.z_1_2.set_parent(this.lev_btn_1);
		this.z_1_3.set_parent(this.lev_btn_1);
		this.z_2_1.set_parent(this.lev_btn_2);
		this.z_2_2.set_parent(this.lev_btn_2);
		this.z_2_3.set_parent(this.lev_btn_2);
		this.z_3_1.set_parent(this.lev_btn_3);
		this.z_3_2.set_parent(this.lev_btn_3);
		this.z_3_3.set_parent(this.lev_btn_3);
		this.z_4_1.set_parent(this.lev_btn_4);
		this.z_4_2.set_parent(this.lev_btn_4);
		this.z_4_3.set_parent(this.lev_btn_4);
		this.z_5_1.set_parent(this.lev_btn_5);
		this.z_5_2.set_parent(this.lev_btn_5);
		this.z_5_3.set_parent(this.lev_btn_5);
		this.z_6_1.set_parent(this.lev_btn_6);
		this.z_6_2.set_parent(this.lev_btn_6);
		this.z_6_3.set_parent(this.lev_btn_6);
		this.z_7_1.set_parent(this.lev_btn_7);
		this.z_7_2.set_parent(this.lev_btn_7);
		this.z_7_3.set_parent(this.lev_btn_7);
		this.z_8_1.set_parent(this.lev_btn_8);
		this.z_8_2.set_parent(this.lev_btn_8);
		this.z_8_3.set_parent(this.lev_btn_8);
		this.z_9_1.set_parent(this.lev_btn_9);
		this.z_9_2.set_parent(this.lev_btn_9);
		this.z_9_3.set_parent(this.lev_btn_9);
		this.z_10_1.set_parent(this.lev_btn_10);
		this.z_10_2.set_parent(this.lev_btn_10);
		this.z_10_3.set_parent(this.lev_btn_10);
		this.z_11_1.set_parent(this.lev_btn_11);
		this.z_11_2.set_parent(this.lev_btn_11);
		this.z_11_3.set_parent(this.lev_btn_11);
		this.z_12_1.set_parent(this.lev_btn_12);
		this.z_12_2.set_parent(this.lev_btn_12);
		this.z_12_3.set_parent(this.lev_btn_12);
		this.z_13_1.set_parent(this.lev_btn_13);
		this.z_13_2.set_parent(this.lev_btn_13);
		this.z_13_3.set_parent(this.lev_btn_13);
		this.z_14_1.set_parent(this.lev_btn_14);
		this.z_14_2.set_parent(this.lev_btn_14);
		this.z_14_3.set_parent(this.lev_btn_14);
		this.z_15_1.set_parent(this.lev_btn_15);
		this.z_15_2.set_parent(this.lev_btn_15);
		this.z_15_3.set_parent(this.lev_btn_15);
		this.z_16_1.set_parent(this.lev_btn_16);
		this.z_16_2.set_parent(this.lev_btn_16);
		this.z_16_3.set_parent(this.lev_btn_16);
		this.z_17_1.set_parent(this.lev_btn_17);
		this.z_17_2.set_parent(this.lev_btn_17);
		this.z_17_3.set_parent(this.lev_btn_17);
		this.z_18_1.set_parent(this.lev_btn_18);
		this.z_18_2.set_parent(this.lev_btn_18);
		this.z_18_3.set_parent(this.lev_btn_18);
		this.z_19_1.set_parent(this.lev_btn_19);
		this.z_19_2.set_parent(this.lev_btn_19);
		this.z_19_3.set_parent(this.lev_btn_19);
		this.z_20_1.set_parent(this.lev_btn_20);
		this.z_20_2.set_parent(this.lev_btn_20);
		this.z_20_3.set_parent(this.lev_btn_20);
		this.zz_1_1.set_parent(this.btn_lev_1);
		this.zz_1_2.set_parent(this.btn_lev_1);
		this.zz_1_3.set_parent(this.btn_lev_1);
		this.zz_2_1.set_parent(this.btn_lev_2);
		this.zz_2_2.set_parent(this.btn_lev_2);
		this.zz_2_3.set_parent(this.btn_lev_2);
		this.zz_3_1.set_parent(this.btn_lev_3);
		this.zz_3_2.set_parent(this.btn_lev_3);
		this.zz_3_3.set_parent(this.btn_lev_3);
		this.zz_4_1.set_parent(this.btn_lev_4);
		this.zz_4_2.set_parent(this.btn_lev_4);
		this.zz_4_3.set_parent(this.btn_lev_4);
		this.zz_5_1.set_parent(this.btn_lev_5);
		this.zz_5_2.set_parent(this.btn_lev_5);
		this.zz_5_3.set_parent(this.btn_lev_5);
		this.zz_6_1.set_parent(this.btn_lev_6);
		this.zz_6_2.set_parent(this.btn_lev_6);
		this.zz_6_3.set_parent(this.btn_lev_6);
		this.zz_7_1.set_parent(this.btn_lev_7);
		this.zz_7_2.set_parent(this.btn_lev_7);
		this.zz_7_3.set_parent(this.btn_lev_7);
		this.zz_8_1.set_parent(this.btn_lev_8);
		this.zz_8_2.set_parent(this.btn_lev_8);
		this.zz_8_3.set_parent(this.btn_lev_8);
		this.zz_9_1.set_parent(this.btn_lev_9);
		this.zz_9_2.set_parent(this.btn_lev_9);
		this.zz_9_3.set_parent(this.btn_lev_9);
		this.zz_10_1.set_parent(this.btn_lev_10);
		this.zz_10_2.set_parent(this.btn_lev_10);
		this.zz_10_3.set_parent(this.btn_lev_10);
		this.zz_11_1.set_parent(this.btn_lev_11);
		this.zz_11_2.set_parent(this.btn_lev_11);
		this.zz_11_3.set_parent(this.btn_lev_11);
		this.zz_12_1.set_parent(this.btn_lev_12);
		this.zz_12_2.set_parent(this.btn_lev_12);
		this.zz_12_3.set_parent(this.btn_lev_12);
		this.zz_13_1.set_parent(this.btn_lev_13);
		this.zz_13_2.set_parent(this.btn_lev_13);
		this.zz_13_3.set_parent(this.btn_lev_13);
		this.zz_14_1.set_parent(this.btn_lev_14);
		this.zz_14_2.set_parent(this.btn_lev_14);
		this.zz_14_3.set_parent(this.btn_lev_14);
		this.zz_15_1.set_parent(this.btn_lev_15);
		this.zz_15_2.set_parent(this.btn_lev_15);
		this.zz_15_3.set_parent(this.btn_lev_15);
		this.zz_16_1.set_parent(this.btn_lev_16);
		this.zz_16_2.set_parent(this.btn_lev_16);
		this.zz_16_3.set_parent(this.btn_lev_16);
		this.zz_17_1.set_parent(this.btn_lev_17);
		this.zz_17_2.set_parent(this.btn_lev_17);
		this.zz_17_3.set_parent(this.btn_lev_17);
		this.zz_18_1.set_parent(this.btn_lev_18);
		this.zz_18_2.set_parent(this.btn_lev_18);
		this.zz_18_3.set_parent(this.btn_lev_18);
		this.zz_19_1.set_parent(this.btn_lev_19);
		this.zz_19_2.set_parent(this.btn_lev_19);
		this.zz_19_3.set_parent(this.btn_lev_19);
		this.zz_20_1.set_parent(this.btn_lev_20);
		this.zz_20_2.set_parent(this.btn_lev_20);
		this.zz_20_3.set_parent(this.btn_lev_20)
	}
	function P(b, f, k, e, g, h) {
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		var c = [b, f, k, e, g, h];
		this.make_copy = function() {
			a.WND = this.WND;
			a.EL = this.EL;
			a.ID = this.ID + 1;
			return new P(c[0], c[1], c[2], c[3], c[4], c[5])
		};
		this.uni_width = e;
		this.uni_height = g;
		this.sprite = new PIXI.DisplayObjectContainer;
		a.GUI.addChild(this.sprite);
		this.WND = a.WND;
		this.EL = a.EL;
		this.ID = a.ID;
		a.EL = "btn_start";
		this.btn_start = new d("levels_btn_start", 0, 0, 5, 12, 1);
		this.sprite.addChild(this.btn_start.sprite);
		a.EL = "txt_num";
		this.txt_num = new q("", 1, 0, 4, 7, 1);
		this.sprite.addChild(this.txt_num.sprite);
		this.txt_num.set_parent(this.btn_start);
		this.sprite.position.x = f;
		this.sprite.position.y = k;
		this.set_parent = function(b) {
			b.sprite.addChild(this.sprite);
			b = this.sprite;
			for (var c = 0, e = 0; b = b.parent;) b != a.GUI && (c += b.position.x, e += b.position.y);
			this.sprite.position.x -= c;
			this.sprite.position.y -= e
		}
	}
	function aa() {
		this.sprite = new PIXI.DisplayObjectContainer;
		this.add = function(a) {
			this.sprite.addChild(a.sprite)
		};
		a.GUI.addChild(this.sprite);
		this.sprite.visible = !1;
		a.EL = "bl_000_0";
		this.bl_000_0 = new b("blocks_bl_000_0", 1, -27, 211, 215, 1);
		this.sprite.addChild(this.bl_000_0.sprite);
		a.EL = "bl_000_1";
		this.bl_000_1 = new b("blocks_bl_000_1", 1, -18, 211, 206, 1);
		this.sprite.addChild(this.bl_000_1.sprite);
		a.EL = "bl_001_0";
		this.bl_001_0 = new b("blocks_bl_001_0", 1, -20, 423, 208, 1);
		this.sprite.addChild(this.bl_001_0.sprite);
		a.EL = "bl_002_0";
		this.bl_002_0 = new b("blocks_bl_002_0", 0, 0, 212, 362, 1);
		this.sprite.addChild(this.bl_002_0.sprite);
		a.EL = "bl_003_0";
		this.bl_003_0 = new b("blocks_bl_003_0", 1, -16, 211, 204, 1);
		this.sprite.addChild(this.bl_003_0.sprite);
		a.EL = "bl_003_1";
		this.bl_003_1 = new b("blocks_bl_003_1", 1, -22, 211, 210, 1);
		this.sprite.addChild(this.bl_003_1.sprite);
		a.EL = "bl_004_0";
		this.bl_004_0 = new b("blocks_bl_004_0", 0, 0, 211, 188, 1);
		this.sprite.addChild(this.bl_004_0.sprite);
		a.EL = "bl_004_1";
		this.bl_004_1 = new b("blocks_bl_004_1", 0, -47, 211, 235, 1);
		this.sprite.addChild(this.bl_004_1.sprite);
		a.EL = "bl_005_0";
		this.bl_005_0 = new b("blocks_bl_005_0", 1, -37, 211, 225, 1);
		this.sprite.addChild(this.bl_005_0.sprite);
		a.EL = "bl_005_1";
		this.bl_005_1 = new b("blocks_bl_005_1", 1, -27, 211, 215, 1);
		this.sprite.addChild(this.bl_005_1.sprite);
		a.EL = "bl_006_0";
		this.bl_006_0 = new b("blocks_bl_006_0", 0, 0, 212, 188, 1);
		this.sprite.addChild(this.bl_006_0.sprite);
		a.EL = "bl_006_1";
		this.bl_006_1 = new b("blocks_bl_006_1", 0, -16, 212, 204, 1);
		this.sprite.addChild(this.bl_006_1.sprite);
		a.EL = "bl_007_0";
		this.bl_007_0 = new b("blocks_bl_007_0", 1, -1, 211, 189, 1);
		this.sprite.addChild(this.bl_007_0.sprite);
		a.EL = "bl_007_1";
		this.bl_007_1 = new b("blocks_bl_007_1", 1, -61, 211, 249, 1);
		this.sprite.addChild(this.bl_007_1.sprite);
		a.EL = "bl_008_0";
		this.bl_008_0 = new b("blocks_bl_008_0", 0, -19, 211, 207, 1);
		this.sprite.addChild(this.bl_008_0.sprite);
		a.EL = "bl_008_1";
		this.bl_008_1 = new b("blocks_bl_008_1", 0, -34, 211, 222, 1);
		this.sprite.addChild(this.bl_008_1.sprite);
		a.EL = "bl_008_2";
		this.bl_008_2 = new b("blocks_bl_008_2", 0, -25, 211, 213, 1);
		this.sprite.addChild(this.bl_008_2.sprite)
	}
	var n, ia = ea,
		ha = y,
		N = fa,
		a = this,
		J = "../addons/dayu_zhinv/template/mobile/data/images_1.json ../addons/dayu_zhinv/template/mobile/data/images_2.json ../addons/dayu_zhinv/template/mobile/data/images_3.json ../addons/dayu_zhinv/template/mobile/data/images_4.json ../addons/dayu_zhinv/template/mobile/data/fonts/main_font.xml ../addons/dayu_zhinv/template/mobile/data/fonts/small_font.xml".split(" "),
		B = ["织女被劫了...", "在王母娘娘的宫殿里...", "牛郎赶紧去救出织女!!", "备马..."],
		s = 0,
		w = 0,
		r = new PIXI.Text("", {
			font: "bold 32px Arial",
			fill: "#F7E930",
			align: "center",
			stroke: "#7E4C21",
			strokeThickness: 6
		});
	r.position.x = 320;
	r.anchor.x = .5;
	r.position.y = 700;
	r.setText(B[s]);
	s++;
	var Q = new PIXI.AssetLoader(J),
		E = 0,
		D = 0,
		u = 0,
		F = .015;
	Q.onComplete = R;
	this.LANGUAGE = "TXT";
	this.GUI_BUSY = !1;
	this.SCREEN_WIDTH = 640;
	this.SCREEN_HEIGHT = 1138;
	this.STAGE = new PIXI.Stage(14745583);
	this.RENDER = PIXI.autoDetectRenderer(this.SCREEN_WIDTH, this.SCREEN_HEIGHT, null, !0, !0);
	document.body.appendChild(this.RENDER.view);
	this.RENDER.view.style.position = "absolute";
	this.RENDER.view.style.top = "0px";
	this.RENDER.view.style.left = "0px";
	this.RENDER.view.style["z-index"] = "1";
	this.TMP_SPR = new PIXI.DisplayObjectContainer;
	this.LOADER_SPR = new PIXI.DisplayObjectContainer;
	this.BACK_SPR = new PIXI.DisplayObjectContainer;
	this.GUI = new PIXI.DisplayObjectContainer;
	this.UP_SPR = new PIXI.DisplayObjectContainer;
	this.TOO_SLOW = !1;
	y = new PIXI.AssetLoader(["../addons/dayu_zhinv/template/mobile/data/pics/main.jpg", "../addons/dayu_zhinv/template/mobile/data/pics/up.png", "../addons/dayu_zhinv/template/mobile/data/pics/upper.png"]);
	var x = 0;
	y.onComplete = function() {
		Q.load();
		var b = PIXI.Sprite.fromFrame("../addons/dayu_zhinv/template/mobile/data/pics/main.jpg");
		x = PIXI.Sprite.fromFrame("../addons/dayu_zhinv/template/mobile/data/pics/up.png");
		var f = PIXI.Sprite.fromFrame("../addons/dayu_zhinv/template/mobile/data/pics/upper.png");
		a.LOADER_SPR.addChild(b);
		a.LOADER_SPR.addChild(x);
		a.LOADER_SPR.addChild(f);
		a.LOADER_SPR.addChild(r);
		x.position.x = -412;
		x.position.y = 614;
		f.position.y = 607;
		H()
	};
	var ga = this;
	window.addEventListener("resize", function() {
		G()
	});
	window.onorientationchange = G;
	G();
	this.RENDER.view.addEventListener("touchmove", function(a) {
		a.stopPropagation();
		a.preventDefault();
		this.deb("tm")
	}, !1);
	this.STAGE.addChild(this.TMP_SPR);
	this.STAGE.addChild(this.BACK_SPR);
	this.STAGE.addChild(this.LOADER_SPR);
	this.STAGE.addChild(this.GUI);
	this.STAGE.addChild(this.UP_SPR);
	var A = -1;
	Q.onProgress = H;
	var v = 0;
	n = (new Date).getTime();
	this.EL = this.WND = "";
	this.ID = 0;
	var M = "",
		L = 0;
	this.deb = function(b) {
		M = b;
		L++;
		a.FPS.setText("[" + L + "]" + M + " fps:" + D)
	};
	this.clear = function() {
		for (; 0 < a.LOADER_SPR.children.length;) a.LOADER_SPR.removeChild(a.LOADER_SPR.children[0])
	};
	var C = (new Date).getTime(),
		K = 0;
	this.init_captions = function() {
		for (var b, f = 0; f < a.DATA.gui.length; f++) {
			if (b = !a[a.DATA.gui[f].id].sprite.visible) a.GUI.removeChild(a[a.DATA.gui[f].id].sprite), a.TMP_SPR.addChildAt(a[a.DATA.gui[f].id].sprite, 0);
			a[a.DATA.gui[f].id].sprite.visible = !0;
			a[a.DATA.gui[f].id][a.DATA.gui[f].element].set_text(a.get("gui", "val", f));
			b && (a.GUI.addChildAt(a[a.DATA.gui[f].id].sprite, 0), a[a.DATA.gui[f].id].sprite.visible = !1)
		}
	};
	this.AVK_spr = b;
	this.AVK_spr_bar = ca;
	this.AVK_txt = q;
	this.AVK_btn = d;
	this.AVK_bar = da;
	this.AVK_INTRO = T;
	this.AVK_TITLE = U;
	this.AVK_HERO_S = V;
	this.AVK_PRINCESS = W;
	this.AVK_CREDITS = X;
	this.AVK_MAIN = Y;
	this.AVK_GAME = Z;
	this.AVK_AVK_blocks_3 = O;
	this.AVK_LEVELS = $;
	this.AVK_AVK_level = P;
	this.AVK_BLOCKS = aa;
	this.DATA = new function() {
		this.gui = [];
		this.gui.id = !1;
		this.gui.element = !1;
		this.gui.val = !0;
		this.blocks = [{
			id: "1",
			path: 10,
			path_2: 0,
			chain: 0,
			block: "bl_000",
			"var": 2
		}, {
			id: "2",
			path: 0,
			path_2: 0,
			chain: 2,
			block: "bl_001",
			"var": 1
		}, {
			id: "3",
			path: 0,
			path_2: 0,
			chain: 4,
			block: "bl_002",
			"var": 1
		}, {
			id: "4",
			path: 9,
			path_2: 0,
			chain: 0,
			block: "bl_003",
			"var": 2
		}, {
			id: "5",
			path: 12,
			path_2: 0,
			chain: 0,
			block: "bl_004",
			"var": 2
		}, {
			id: "6",
			path: 3,
			path_2: 0,
			chain: 0,
			block: "bl_005",
			"var": 2
		}, {
			id: "7",
			path: 5,
			path_2: 0,
			chain: 0,
			block: "bl_006",
			"var": 2
		}, {
			id: "8",
			path: 6,
			path_2: 0,
			chain: 0,
			block: "bl_007",
			"var": 2
		}, {
			id: "9",
			path: 0,
			path_2: 0,
			chain: 0,
			block: "bl_008",
			"var": 3
		}];
		this.blocks.id = !1;
		this.blocks.path = !1;
		this.blocks.path_2 = !1;
		this.blocks.chain = !1;
		this.blocks.block = !1;
		this.blocks["var"] = !1;
		this.str = [];
		this.str.id = !1;
		this.str.txt = !0;
		this.relations = [];
		this.relations.id = !1;
		this.relations.rel_id = !1
	};
	this.LOCAL = {};
	this.get = function(b, f, d) {
		return a.DATA[b][f] ? a.LOCAL[a.DATA[b][d][f]][a.LANGUAGE] : a.DATA[b][d][f]
	};
	this.filtered = function(b, f, d, e, g) {
		for (var h = 0, c = 0; c < a.DATA[b].length; c++) if (a.DATA[b][c][e] == g) {
			if (h == d) return a.get(b, f, c);
			h++
		}
		return null
	};
	this.find = function(b, d, k) {
		for (var e = 0; e < a.DATA[b].length; e++) if (a.DATA[b][e][d] == k) return e;
		return -1
	};
	this.ACT = new function() {
		var b, d = [];
		this.lin = function(a, b, d) {
			return a + (b - a) * d
		};
		this.sqrt = function(a, b, d) {
			return this.lin(a, b, Math.sqrt(d))
		};
		this.sin_plus = function(a, b, d) {
			return 1 + .05 * Math.sin(this.lin(a, b, d))
		};
		this.sin = function(a, b, d) {
			return Math.sin(this.lin(a, b, d))
		};
		this.n2 = function(a, b, d) {
			return this.lin(a, b, Math.pow(d, 2))
		};
		this.simple = function(a, b, d) {
			return b
		};
		this.simple_min = function(a, b, d) {
			return .5 < d ? b : a
		};
		this.simple_end = function(a, b, d) {
			return 1 == d ? b : a
		};
		this.init = function(a) {
			b = a
		};
		this.start = function(a, e, g) {
			if (b && b[a]) {
				for (var h = -1, c = 0; c < d.length54; c++) if (0 >= d[5 * c + 3]) {
					h = 5 * c;
					break
				}
				0 > h && (h = d.length, d.push(0), d.push(0), d.push(0), d.push(0), d.push(0));
				d[h] = a;
				d[h + 1] = e;
				d[h + 2] = b[a].pause;
				d[h + 3] = b[a].time;
				d[h + 4] = g
			} else alert("Can't find " + a)
		};
		this.change = function(b, d, f) {
			switch (d) {
			case "prop":
				b.set_property(f);
				break;
			case "x":
				b.sprite.position.x = f;
				break;
			case "y":
				b.sprite.position.y = f;
				break;
			case "global_x":
				b.sprite.position.x = f * a.SCREEN_WIDTH;
				break;
			case "global_y":
				b.sprite.position.y = f * a.SCREEN_HEIGHT;
				break;
			case "scale":
				b.sprite.position.x = b.x - b.uni_width * (f - 1) / 2;
				b.sprite.position.y = b.y - b.uni_height * (f - 1) / 2;
				b.sprite.scale.x = f;
				b.sprite.scale.y = f;
				break;
			default:
				b.sprite[d] = f
			}
		};
		this.update = function(a) {
			for (var e = 0; e < d.length / 5; e++) if (0 < d[5 * e + 3]) if (0 < d[5 * e + 2]) d[5 * e + 2] -= a, 0 >= d[5 * e + 2] && (d[5 * e + 3] += d[5 * e + 2], d[5 * e + 2] = 0);
			else {
				d[5 * e + 3] -= a;
				0 >= d[5 * e + 3] && (d[5 * e + 3] = 0);
				var g = (b[d[5 * e]].time - d[5 * e + 3]) / b[d[5 * e]].time,
					h;
				for (h in b[d[5 * e]].changes) {
					var c = b[d[5 * e]].changes[h];
					this.change(d[5 * e + 1], h, this[c.trans](c.beg, c.end, g))
				}
				if (0 == d[5 * e + 3] && d[5 * e + 4]) d[5 * e + 4](d[5 * e + 1])
			}
		}
	};
	y.load()
};