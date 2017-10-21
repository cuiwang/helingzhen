<html>
 <head></head>
 <body>
  @charset &quot;UTF-8&quot;;html,body {
	position: relative;
	display: block;
	background-color: #efeff4!important;
	direction: ltr
}

.single-ellipsis {
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis
}

.wraper {
	top: 0;
	bottom: 54px;
	width: 100%;
	height: auto;
	overflow-x: hidden;
	overflow-y: scroll;
	-webkit-overflow-scrolling: touch
}

.top-content {
	position: relative;
	width: 100%;
	height: 100%;
	padding-bottom: 44px;
	background-color: #fff
}

.header-cover {
	position: relative;
	height: 95px;
	overflow: hidden;
	background-color: rgba(0,0,0,.3)
}

.header-cover .header-cover-mask,.header-cover .header-cover-img {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-position: center;
	background-size: 120%;
	-webkit-filter: blur(6px)
}

.header-cover.special {
	height: 235px
}

.header-cover.special.has-desc-line-3 {
	height: 295px
}

.header-cover.special.has-desc-line-3 .mask-white {
	height: 135px
}

.header-cover.special.has-desc-line-3 .special-info .intro-wrapper .lastline-space-ellipsis {
	height: 60px
}

.header-cover.special.has-desc-line-3 .special-info .intro-wrapper .lastline-space-ellipsis:before {
	height: 40px
}

.header-cover.special.has-desc-line-2 {
	height: 275px
}

.header-cover.special.has-desc-line-2 .mask-white {
	height: 115px
}

.header-cover.special.has-desc-line-2 .special-info .intro-wrapper .lastline-space-ellipsis {
	height: 40px
}

.header-cover.special.has-desc-line-2 .special-info .intro-wrapper .lastline-space-ellipsis:before {
	height: 20px
}

.header-cover.special.has-desc-line-2 .special-info .intro-wrapper .lastline-space-ellipsis:after {
	padding-right: 1em;
	text-indent: -1em;
	-webkit-line-clamp: 2
}

.header-cover.special.has-desc-line-1 {
	height: 255px
}

.header-cover.special.has-desc-line-1 .mask-white {
	height: 95px
}

.header-cover.special.has-desc-line-1 .special-info .intro-wrapper .right-arrow {
	bottom: 2px
}

.header-cover.special.has-desc-line-1 .special-info .intro-wrapper .lastline-space-ellipsis {
	height: 20px
}

.header-cover.special.has-desc-line-1 .special-info .intro-wrapper .lastline-space-ellipsis:before {
	display: none
}

.header-cover.special.has-desc-line-1 .special-info .intro-wrapper .lastline-space-ellipsis:after {
	padding-right: 0;
	text-indent: 0;
	-webkit-line-clamp: 1
}

.header-cover.special.no-desc .header .special-info .btn-area {
	margin-top: 31px
}

.header-cover.special .header-cover-mask,.header-cover.special .header-cover-img {
	height: 160px;
	-webkit-transform: scale(2);
	-webkit-transform-origin: center;
	background-position: center;
	background-size: 100%;
	-webkit-filter: blur(6px) saturate(1.7)
}

.header-cover.special .header {
	height: 100%
}

.header-cover.special .cover.mask-gray {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 160px
}

.header-cover.special .mask-white {
	position: absolute;
	top: 160px;
	left: 0;
	width: 100%;
	height: 75px;
	background-color: #fff
}

.header-cover.special .mask-white:before {
	position: absolute;
	bottom: 0;
	left: 0;
	display: block;
	width: 100%;
	content: &quot;
	&quot;;-webkit-transform: scaleY(0.5);
	transform: scaleY(0.5);
	-webkit-transform-origin: 0 0;
	pointer-events: none;
	border-bottom: 1px solid #e5e5e5
}

.header-cover .header-cover-mask {
	opacity: .5;
	background-color: #000
}

.header-cover-loading {
	display: block;
	height: 135px;
	text-align: center;
	vertical-align: top;
	color: #a6a6a6;
	background-color: transparent
}

.android .header-cover.special .header-cover-mask {
	opacity: .6
}

.ios .header-cover .header-cover-img {
	height: 95%
}

.ios .special.header-cover .header-cover-img {
	height: 160px
}

.android .header .cover.mask-gray {
	background-color: rgba(0,0,0,.6)
}

body .ui-test-nav-wrap {
	position: relative;
	z-index: 999;
	display: none;
	width: 100%;
	height: 40px;
	background-color: #fff
}

body .ui-test-nav-wrap .ui-test-nav {
	position: relative;
	top: 0;
	z-index: 999;
	display: block;
	width: 100%;
	height: 40px;
	overflow: hidden;
	border-bottom: 1px solid #e5e5e5;
	background: #fff
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list {
	display: -webkit-box;
	display: box;
	height: 40px;
	margin: 0 auto;
	font-size: 10px;
	text-align: center
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list div {
	position: relative;
	display: block;
	height: 100%;
	min-width: 60px;
	padding: 0;
	overflow: hidden;
	box-sizing: border-box;
	text-align: center;
	border: 0;
	border-radius: 0;
	-webkit-box-flex: 1
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list div a {
	position: relative;
	top: -7px;
	display: block;
	height: 42px;
	margin-left: 1px;
	box-sizing: border-box;
	font-size: 12px;
	line-height: 14px;
	color: #383838;
	-webkit-border-radius: 4px;
	border-radius: 4px
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list div a:active {
	background-color: #ededed
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list div a.apply:after {
	position: absolute;
	top: 2px;
	right: 15px;
	width: 22px;
	height: 12px;
	font-size: 8px;
	line-height: 12px;
	content: &quot;
	招募&quot;;color: #fff;
	border-radius: 10px;
	background-color: #f74c31
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list div~div:before {
	position: absolute;
	top: 9px;
	left: 0;
	width: 0;
	height: 22px;
	content: &quot;
	&quot;;border-left: 1px solid #e5e5e5
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list div a:before {
	position: relative;
	top: 3px;
	display: block;
	width: 21px;
	height: 16px;
	margin-top: 8px;
	margin-right: auto;
	margin-bottom: 5px;
	margin-left: auto;
	content: &quot;
	&quot;;vertical-align: text-bottom;
	background-size: 100% 100%
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list #tab_qun:before {
	top: 3px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACoAAAAgCAMAAABThhoPAAABGlBMVEU4ODj///84ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODggeMfgAAAAXXRSTlMAANUrv5hB87Krwwb6DON7EUjZpzhqwlkBgtbsGuo0OeipGA3vB4viLJWuECagvLMtjvTnS2ClyPA1Y/WIYiRhonkIShSDQHJbWiEDM2iNIKo2yWmKE8CoC8bEGwSSrS/dAAABmUlEQVR4XrXU11LbUBSF4RVZsooLtgHjCpjee++d9N6T//1fIxwlloOQILlg3ayZrW/27JFmpCf/nP+jup1S3XZduz4fGyfQcpEw0+WHaBm8bKGQXYby/XR+mj6F6aNYSqMtx8pbsyx3n3nM3gyc1l060wSoku3SLFWA5kycjrXpZHKZNca7dJy1m0GHZitGHXxTbo8WcE11cGLUImPK/vsA21QGK0bz5EzV8brUo24qRz5x6+THIr2XNTWZuNXhrbRXAevgk5EXBxZU9qQvsVu/zsH3hRNYGlGUgSU4WQhgrtCjZzbrG/JxN3Urmy6+NtaxzyK6ReO5FmkPK5bhNot602CrSz+wc6Rj9o2M5eU+hzra4dUfWuWZfjY4VEKOsV7oKdXf9ByrpkvsWhKtfeZSNYvzkE5xKgVcKTFXBNIpr0O6zYB2qSglFXb1jvchHWVM16ym0VWuVWIipObz9zOURofolyDaao5IyQjfoq0+ACtpdAUAL6QX/ijctxUmvB8hlfTgrVJEB0kPgz0a2iANBkYa+jh/wl+Uj12TSc3LaQAAAABJRU5ErkJggg==');
	background-repeat: no-repeat
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list #tab_tribe:before {
	background-position: 0 -200px
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list #tab_hof:before {
	top: 3px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACoAAAAgCAMAAABThhoPAAABLFBMVEU4ODj///84ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODjIMqPYAAAAY3RSTlMAABYnrYx076Y3Sga858uzsgLx/qTzFQ3hwv35agxXEBLKeSWuEfUg0/q9i1icCBzjm0GX300dWZm25rFrKB8JdWYY5L5W8G2pQtkeeEv29I9bpSlccT56SY5yBQ4PB9cBwT+prlF/AAABjElEQVR4XpXUVY8iURBA4dpWoBl8GHTc3Y3xhXFbdz3//z9sp5dcmpaEOY91v4dKKrnyauBeRkU108hW6nQb/pVtzIiqj65O2gSyP51G0bl5yO3d/ZRuI+/2cjA/F6Zr61RTEuhzlfW1IF2dINeUUM0cE6cBek9mTCIayzDZT8cveZDIUlyO9+iUUZglLTGla48FY+o/1W2AWT1a6k8Atu5Rg4SmJZiOptMkLtxXw6MFNJEyxc0ouVmkLKIx3KOyxfaIuJV3rKX9/SVrR/PusM2WeFQt4JoDVsQ5yhTpVswcObLCgSvVAsc2bIjodcwKkDXPzs/PzCxQManrIhtgH3tUdGMBRyQJLC7npVt+eRFIym+HBUNXJ/jDX3GtlZReavCRqu9a14xKbKNc++gXvsbTDzz7qIUZT00sH33D23ha4r2P3tCOp21ufPSixkmcPKGm+aiUuL3adcLu2+7VLSXx0+9pYkv/6KOS77SIrNXJh/8BAFH5JmF6CENKqUkkTQ29TikVmrzwexu4f2tuYXXSCtHoAAAAAElFTkSuQmCC');
	background-repeat: no-repeat
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list #tab_album:before {
	top: 3px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACoAAAAgCAMAAABThhoPAAABLFBMVEU4ODj///84ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODjIMqPYAAAAY3RSTlMAABat7/Enpu3+KFnq6auRBiWI8vM7txMgqLn4augM7pAEMxsJdvAD/d7PHnA3wQJN+1MO1GkwXsv5sD4Q35parJ0BBba1NWUH95fAaxxCJi7V4ZgK/Fh9GbSZlaX2SVDZ1jqqY0LyAAABH0lEQVR4Xq3T1W7DMBiGYTdO0qRJiivCkJmZmZkZvvu/hy1erTnSFrvd3iNLfg7s3zKJKFcvTSYshNWSSHLaCllpTi20k19i+1lYnAIkhDLQME1Rw6ApFWrqAKCbCpQiqmlRUAVqQCNEQ1OD9O8HiH1dK6ZAiUkzGSoMYLtY3evvapY/QU+6F3590zLaFgcuOjpHc+gektA1uFm2eIQ7EEoHUa7dLxLH8EgIfR/DOJ/AxCSmRLo+Mzunzy/U5OISlr+HtQJXpKvwsx82GChiU5zrFkQKb2c3vw8c5D/BIawjkR4HaPnE3zg9w7lz6VzhOvhaOZHeENbtHfzuSZA6P06gUnoqVf7nb9l4DqUvsDl9haw3TgueRHoFRtWqj34AUqx+61G3zS0AAAAASUVORK5CYII=');
	background-repeat: no-repeat
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list #tab_best:before {
	top: 3px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACoAAAAgCAMAAABThhoPAAABxVBMVEU4ODj///84ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODhdGy3tAAAAlnRSTlMAAPz+BvkRgPVXHtqhsRwB+u8rA7XsTczl+NCpq5jg2An9h44SFTHJ3iGfE9avtJkE1UZmallvoK7NF5fcKermJw58PA2/FBrkxfs0nmgfpkT0Ub06T5xyhkxca0dbduudafaPYd2SpKpJmqUC8T3zGQjwIgf3DBhaxiTPdBtUJc4+smXEgQrHKovRD2fo6YJ13/LutlLQkumEAAAB1UlEQVR4XrXURZtTSxSF4cWpJIQQiLt7u7u7u+Hu7u7ufu/6vTyVynnohqQ7DHgnew2+8ca2sv1NWlBZwRIqKrFBr58l+XuxTraPZvNBtXfv1HZs5y4D8tqEg0ey0GHFwfCNU4F9egno7QHhqz5LxwoKFhY5DzyKGf/XS709JjpNwDwXF1TpvUWrvHcatbuq1Ntm8aBabisDXnlb7ZxB3thTza9K1XaK4JjaR2lvBV5oLIvWjBTLlAJekd3Ie91z3GIyoeDDHg6ZGtTuJr/I+1UwotoW7sUvk1NXoUQoDqs1m+B5SPE+x23oBlgHpZ6JWRQcmuIapCE+hC7Jx2qsUdsPHZaMbJfXdGXCAMU14VGzncYlrNNk5gV5T/AklHM8DekMzU3YYPg6QzbAYm+8qNIQswBsIV66jN9Me1h7DbByFVI6EARws5aeafzB2U/3IJziHqQWtgGDbvY7UcT9KJczqOOwTHPGDDLLjD5BUZYYJ8ef8TkAJzsw/pIxC0roCtI390a8Bd5xdc7H910oKZ1jzUda0fDp80ANc2lsYmSU0R6jxcuZbxwdwabibn5nqoM/+F8cWzCEKcz2BMMGbMlVRZJVLpTBFiHrbShPMoki/s0n/AnNlbTBWcU1EQAAAABJRU5ErkJggg==');
	background-repeat: no-repeat
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list #tab_best.mulu:before {
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACoAAAAgCAMAAABThhoPAAAAWlBMVEU4ODj///84ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODgrsvsxAAAAHXRSTlMAAAYYGTg7PD0+QkSAgYKIkJGUtbbt7vDy8/T190ve/2cAAACeSURBVHhe7dTLDgIhDAXQDuLbUdEpqsz9/98UEkPSOiMYo268m7I4i5akpaY6r1PjGCPxzgi6x5PsBGV0NvckYjuwoECWipIFFG3GKH2P9tFDpn+X/n4sTf8/cN8MXxgrjZM3o0zTZsTiEIoNAEluENYVNJYWYUVVtMV1SZraIbrFZUGKMo4TTRkHnOfpVXMyTjN6oMb5ITklQT9xCW+iZCcRu4CKtgAAAABJRU5ErkJggg==')
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list #tab_more {
	position: relative;
	z-index: 1
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list #tab_more .m-point {
	position: absolute;
	top: 0;
	left: 55%;
	z-index: 2;
	display: none;
	width: 1em;
	height: 1em;
	padding: 4px;
	font-size: 12px;
	line-height: 1em;
	color: #fff;
	border-radius: 50%;
	background-color: #ff2900
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list #tab_more:before {
	top: 3px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACoAAAAgBAMAAACWdvcOAAAAFVBMVEU4ODj///84ODg4ODg4ODg4ODg4ODgI0myTAAAABnRSTlMAAIXz9Pm3EuIQAAAALUlEQVR4XmMQxAaoIqqchgqMwKJhaKLJYFEVNFEnsCgDOhi8ouT4eNTHlKczADeKSo7Q4kO0AAAAAElFTkSuQmCC');
	background-repeat: no-repeat
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list #tab-publish {
	color: #fff;
	background-color: #00a5e0
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list #tab-publish:before {
	top: 5px;
	width: 24px;
	height: 24px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAMAAABg3Am1AAAAS1BMVEUAAAD///////////////////////////////////////////////////////////////////////////////////////////////+DmQsHAAAAGHRSTlMA5yUb998T79pGKx/DPDTOlQtS1bOoeGM5w+SrAAAAvklEQVRIx+3S2w7CIBRE0VMqtTcttl7m/79UCjFoTpqBRJ/sPO8VQkD+Z427nYr6BTjMJWCCF0beho3ZQaS+1kE8OIh9i9YLdYJszPdYxTgLAakPQoSD1OPQ5YHBpl4B0itAeg1IX3NgPnrLQOrDr7NgwFSxP4ZeoMFmT4HuOehf/VkIUD0HuudgSn0OMJd44yr2HLgxiKqXTLBgFalnoAFWkXoGZvg5kWzgMN57KQBdo2L2Djv4IqArB/t+uCeBwSOdgh3xzQAAAABJRU5ErkJggg==')
}

body .ui-test-nav-wrap .ui-test-nav .new-tab-list #tab-publish:active {
	background-color: #0092c6
}

body .ui-test-nav-wrap .ui-test-nav .border-1px::after {
	z-index: -1
}

@-webkit-keyframes icon2 {
	50% {
		opacity: 0
	}

	100% {
		-webkit-transform: translate(-175%,2px);
		opacity: 1
	}
}

.ios .ui-test-nav-wrap {
	position: -webkit-sticky!important;
	top: 0;
	z-index: 999
}

.header {
	position: relative;
	width: 100%;
	height: 95px;
	overflow: hidden;
	background-size: 100% 125px;
	-webkit-tap-highlight-color: transparent
}

.header:after {
	position: absolute;
	top: 0;
	left: 0;
	display: block;
	width: 100%;
	height: 100%;
	content: &quot;
	&quot;;background-color: transparent
}

.header .cover {
	position: absolute;
	z-index: 1;
	width: 100%;
	height: 100%;
	background-size: 100% 125px
}

.header .cover.mask-gray {
	z-index: 2;
	background-color: rgba(0,0,0,.5)
}

.header .cover.mask {
	top: 58px;
	z-index: 3;
	height: 40px;
	background-color: transparent;
	background-image: -webkit-gradient(linear,left bottom,left top,color-stop(0%,rgba(0,0,0,.3)),color-stop(100%,transparent))
}

.header .info {
	position: absolute;
	z-index: 5!important;
	display: -moz-box;
	display: -webkit-box;
	display: box;
	width: 100%;
	height: 78px;
	margin: 12px 0 13px
}

.header .info .logo-container {
	position: absolute;
	top: 4px;
	left: 10px;
	z-index: 1;
	height: 60px;
	border: 2px solid #fff;
	border-radius: 5px
}

.header .info .logo {
	width: 60px;
	height: 60px;
	border-radius: 3px
}

.header .info .op,.header .info .sign {
	position: absolute;
	right: 15px;
	z-index: 5;
	width: 80px;
	height: 28px
}

.header .info .op .btn,.header .info .sign .btn {
	display: inline-block;
	width: 65px;
	height: 28px;
	margin-left: 10px;
	box-sizing: border-box;
	font-size: 13px;
	line-height: 28px;
	text-align: center;
	color: #fff;
	border-radius: 3px;
	background-color: #00a5e0
}

.header .info .op .btn:active,.header .info .sign .btn:active {
	background-color: #1a7fc6
}

.header .info .op .vote,.header .info .sign .vote {
	display: none;
	width: 95%;
	height: 27px;
	padding-left: 15px;
	font-size: 16px;
	line-height: 27px;
	text-align: center;
	color: #fff;
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/focused.png?b30a86bb) no-repeat 3px 2px;
	background-size: 20px 20px
}

.header .info .op {
	top: 23px;
	right: 10px;
	display: none;
	width: 62px;
	height: auto
}

.header .info .op .vote-btn {
	display: block;
	width: 100%;
	margin-left: 0
}

.header .info .vote-btn-icon {
	position: relative;
	top: 2px;
	display: inline-block;
	width: 12px;
	height: 12px;
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/../img/follow.png?33fd280a);
	background-size: 100% 100%
}

.header .info .sign {
	bottom: 0;
	display: -webkit-box;
	float: right;
	width: 100%
}

.header .info .sign .info-grade {
	position: relative;
	-webkit-box-flex: 1
}

.header .info .sign .info-grade .add-score-tips:before {
	position: absolute;
	bottom: -5px;
	left: 50%;
	z-index: -1;
	width: 15px;
	height: 15px;
	margin-left: -8px;
	content: &quot;
	&quot;;-webkit-transform: rotate(45deg);
	opacity: .8;
	background: #000
}

.header .info .sign .info-grade .info-grade-wrap {
	position: relative;
	display: -webkit-box;
	height: 28px;
	min-width: 110px;
	padding: 3px 10px 0;
	margin-left: 96px;
	box-sizing: border-box;
	border-width: 2px;
	-webkit-border-radius: 3px;
	border-radius: 3px
}

.header .info .sign .info-grade .add-score-tips {
	position: absolute;
	top: 0;
	left: 20px;
	display: none;
	padding: 4px;
	font-size: 12px;
	-webkit-transform: translateY(-10px);
	-webkit-animation-name: tips_fade;
	-webkit-animation-duration: 3000ms;
	opacity: .8;
	opacity: 0;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	background: #000
}

.header .info .sign .info-grade .add-score-tips .add-score-title {
	color: #fff
}

.header .info .sign .info-grade .add-score-tips .add-score-value {
	color: #8db943
}

.header .info .sign .info-grade .add-score-tips.show {
	display: block
}

.header .info .sign .info-grade .info-grade-value-bar {
	position: relative;
	top: 2px;
	height: 7px;
	border: 1px solid rgba(255,255,255,.17);
	-webkit-border-radius: 8px;
	border-radius: 8px;
	background: rgba(0,0,0,.3);
	-webkit-box-flex: 1
}

.header .info .sign .info-grade .info-grade-value-inner-bar {
	float: left;
	width: 40px;
	height: 7px;
	margin: 0 0 0 2px;
	-webkit-transition: width .6s ease-out;
	-webkit-border-radius: 8px;
	border-radius: 8px;
	background: -webkit-linear-gradient(top,#8db943,#70a128);
	background: linear-gradient(top,#8db943,#70a128)
}

.header .info .sign .info-grade .info-grade-score {
	padding-top: 1px;
	margin: 3px 0 0 6px;
	margin-top: -5px;
	font-size: 10px;
	line-height: 20px;
	vertical-align: middle;
	color: rgba(225,225,225,.7)
}

.header .info .sign .sign-btn,.header .info .sign .send-heart-btn {
	position: relative;
	top: -27px;
	right: -5px;
	width: auto;
	min-width: 62px;
	padding: 1px 8px 0;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis
}

.header .info .sign .sign-btn.disable {
	min-width: 62px;
	line-height: 25px;
	color: rgba(255,255,255,.38);
	border: 1px solid rgba(255,255,255,.5);
	-webkit-border-radius: 3px;
	border-radius: 3px;
	background-color: transparent;
	background-image: none!important
}

.header .info .sign .sign-btn.disable .signed-icon {
	position: relative;
	top: 0;
	left: -4px;
	display: inline-block;
	width: 13px;
	height: 8px;
	background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAQBAMAAAD6/3KbAAAAMFBMVEX///////////////////////////////////////////////////////////////9Or7hAAAAAEHRSTlMAEBIXHCw0QEZabnV4fn+A8J0zyQAAAD9JREFUeF5jQAIKyBy2q8hSeZuRpV4bkCTVjizFeL4AWZfPd2RdLEBJiBRMEiIFk0S2y+cnRAomuZkBCXjBpAArEhiaVa2/dQAAAABJRU5ErkJggg==');
	background-size: 13px 8px
}

.header .info .sign-icon {
	position: relative;
	top: 1px;
	display: inline-block;
	width: 12px;
	height: 12px;
	margin: 0 2px 0 0;
	background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAMAAADXqc3KAAAAS1BMVEUAAAD///////////////////////////////////////////////////////////////////////////////////////////////+DmQsHAAAAGHRSTlMAsMAXcNoRJgq25olABWXyIX9LXMqoNfrCBrZ1AAAAoUlEQVR4Xp3ROQ7DMAxE0ZGtzXsWx/n3P2lAIAJsKVWmnEeQBfVHkuOULkt5SNZT90vHGCUHPuqcFzBL4HXJBGOSwXV+hWCN4NLPsGe10G/YfAsdYy/pXiDvazmQrKfAAE9bBA9zKJACHNKLSVdQ7Nj6mbDUoGVgCBxqQDlgi1rQ7b3ln6DHUzWUVBBriF/wNXiD9lHRgyuvrZNk4mpw1n8A3RkQ6646hjoAAAAASUVORK5CYII=');
	background-size: 12px 12px
}

.header .info .name-info {
	position: relative;
	z-index: 2;
	margin-top: 6px;
	margin-left: 85px;
	font-size: 11px;
	color: #fff
}

@media (max-width:320px) {
	.header .info .name-info .name {
		display: block;
		float: left;
		height: 20px;
		max-width: 220px;
		overflow: hidden;
		font-size: 18px;
		white-space: nowrap;
		text-overflow: ellipsis;
		text-shadow: 0 0 2px #000
	}
}

@media (min-width:321px) {
	.header .info .name-info .name {
		display: block;
		float: left;
		height: 22px;
		max-width: 240px;
		overflow: hidden;
		font-size: 21px;
		white-space: nowrap;
		text-overflow: ellipsis;
		text-shadow: 0 0 2px #000
	}
}

.header .info .name-info .info-num {
	display: inline-block;
	padding: 3px 0;
	margin-top: 6px;
	font-size: 80%;
	font-size: 11px;
	opacity: .7;
	color: #fff;
	border-radius: 3px
}

.header .info .name-info .info-num label {
	float: left;
	margin: -1px 0 0
}

.header .info .name-info .info-num span {
	float: left;
	margin: -1px 5px 0 0
}

.header .info .name-info .info-num span.charm-wrapper {
	margin: 0
}

.header .info .name-info .bar-info-text {
	width: 160px;
	height: 14px;
	margin-top: 3px;
	overflow: hidden;
	line-height: 14px;
	white-space: nowrap;
	text-overflow: ellipsis;
	opacity: .7
}

.header .info .name-info .labels {
	overflow: hidden
}

.header .info .name-info .labels .official-in {
	float: left;
	width: 40px;
	height: 13px;
	margin: 4px 0 0 5px;
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/officialIn.png?be2bf342);
	background-size: 100%
}

.header .info .name-info .labels label {
	display: inline-block;
	float: left;
	width: 27px;
	height: 16px;
	font-size: 11px;
	line-height: 17px;
	text-align: center;
	color: #fff;
	border-radius: 2px;
	background-color: #5c9ad5
}

.header .info .name-info .labels label.tupian {
	background-color: #5c9ad5
}

.header .info .name-info .labels label.niming {
	background-color: #ae8ad6
}

.header .info .name-info .labels label.shijian {
	background-color: #f37576
}

.header .info .name-info .labels label.diqu {
	background-color: #97c257
}

@media (max-width:320px) {
	.header .info .name-info .labels label {
		margin: 1px 0 0 4px
	}
}

@media (min-width:321px) {
	.header .info .name-info .labels label {
		margin: 3px 0 0 4px
	}
}

.header .special-info {
	position: absolute;
	z-index: 5!important;
	width: 100%;
	margin-top: 20px
}

.header .special-info .logo-container {
	position: absolute;
	top: 0;
	left: 10px;
	width: 86px;
	height: 121px;
	border: 2px solid #fff;
	border-radius: 2px;
	background-color: #fff
}

.header .special-info .logo-container img {
	width: 100%;
	height: 100%
}

.header .special-info .text-info {
	height: 124px;
	margin-left: 109px
}

.header .special-info .text-info .name {
	width: 90%;
	overflow: hidden;
	font-size: 19px;
	font-weight: 700;
	white-space: nowrap;
	text-overflow: ellipsis;
	color: #fff
}

.header .special-info .text-info .english-name {
	height: 12px;
	margin-top: 10px;
	font-size: 11px;
	color: rgba(255,255,255,.3);
	text-shadow: 1px 1px rgba(0,0,0,.5)
}

.header .special-info .text-info .rating-container {
	height: 15px;
	margin-top: 11px;
	line-height: 15px
}

.header .special-info .text-info .rating-container .rate-star {
	display: inline-block;
	width: 78px;
	height: 15px;
	vertical-align: middle;
	background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJwAAAFwCAMAAAB6l+IyAAABJlBMVEUAAAD/////+u7/////////////wSv/wCr/////////////////////wCr/////////wCr/wCr/////////wCr/wCr/wCr/////wSz/wCr/wCr/wCr/wCr/wCr/wCr/wCr/wCr/wCr/////////////////////wCr/wCr/wCr/wCr/wCr/wCr/wCr/wCr/wCr/wCr/wCr/wCr/wCr/////wSv/wzP/wCr/wCr/wCr/wCr/wCr/wCr/wCr/wCr/wCr/wCr/wjH/////wCr/xj7/xj//02z/z1z/0GD/8M3/zVX/wjD/xTv/xTr/x0H/yUj/ykv/45//ykz/y0//9dz/zVX/z1r/z1z/wzb/xkD/02n/3o//yUr/ykv/35T/y1D/zFP/wCqb9SeBAAAAYXRSTlMATQIGSw8P+UgXOD4L/UQyzzVBHRkG+yIL9uK6tqWRYi8ULComNjDx7urd26GbbmhSTksoG/Tn1r+uiYJ5dFxbKSIfH8m1FhKhXB7u0MnChXRwbWFWQRoI2q+Xenp3dlpEwOlEFgAABy5JREFUeNrsmFdT20AURu+VZAtX7GAEhE5IaAFCc3oHm04CgfRy//+fyN1dKSOZ8KyPGZ8H78pn/HRXO+NDGZpN6gHHt8JwlgjUr4ms64Lpn4hM6ALpWzWRsEgE6ddFeaUbRD9u5FPdAPrZyMixUSJAPy2WR7qF8aNFhZQJJyd1i+IbNbmBWsPL2dOy3Mhd/bGXp6fW+E1uYpaUnP3L8H8qXCND7r65ct09njEGwRcf9rrVUVJA/OuFtFp0dw2Mn07LDVKA/GRaThERkB9eyF4wREB+QzI0iYB8/LqMhW59RkQwfnhRDI2t5rIYVogIxm+KUpsaJiq6kzlDBONX9XlpkyzTO/rwXDcg3lsSGW9RzFZDpKEriC+ORS+81AmYqpmXGcW/eUsZZt7rB5Dv06fPrSLvsvnnErRcKodReIVZLpUzkZ+Q5dJwLLILWS6VQ/VRF7FcKj+MPwcsl4YT408By6VyZX1YhCmXaT44f4FSLq3vdDpdUnad3wMqm196ffsTx1RyL5ttyRJ+5IQ65V02D08kwzdOuO/nXi6JzqLU16ccE4wglEuiy/b1A3enAFIuqbsnjig5cA88mHJJdL5jxT5bBkpA5TK5gXfZUQIqlwYz2PYBO+4BlUvls441+sUxdaByqVy4A5dQgCmXhj134IIgnitMuXRTPTpgrlQLd9wtB1Muld9S+8485xH5g26uIOXS8FX2/11uQwPMPA9SLo0/OuayTzHVig4YpVwSddsH8ynvzXEdqFx2qpShUKU+ffrcXvIum4UCbtn0g8CHLZsjzEOwZbPMXEYtm37AHPigZfMdK9ugZbNsCwlm2dSpKoEHWTaH2FJCKpuer7ipWgaBymaFr4FTNuvcC1DZ9MucBatsjgScAq1s2r/5CXBl0x/kBMSyuT3AFsyy6W5g0LLpBotZNj07VtCyWWIFtWyaqaKWTTtV1LJppgpbNv+yawcpCAMxGEYpdikew/sfUWMYsDN1NiJ91PxXCGTx+O7L4srmbXFl87KusGxeSzZrtTPt902mK5vRZLKyGU0mK5vRZKqymU0mKpvZZKKymU2mKZutySRlszWZlGz2TSYkm7Mm83DZnDWZh8vmrMkEZPNTk2nI5n6TqcjmXpMJyWbfZFqyuW0yMdncNpmWbHZNpiWbfZNJyWbfZEqyOTSZkmyOTSYkm2OTCcnm2GQ6srnTZEKyOTaZJZu12on3+ybTlc2QS1Y2Qy5Z2Qy5VGUz5RKVzZRLVDZTLk3ZbHJJymaTS0k2B7mEZHNHLh3ZHOUSks1BLi3Z3MqlJpsbueRk800uRdlscmnKZn5YVDbzcKZspnGhsvkyLlU242qqbL6upspmXI2VzadcurJ5W1zZvKwrLJvXks1a7Z/2fZPpymY0maxsRpPJymY0mapsZpOJymY2mahsZpNpymZrMknZbE0mJZt9kwnJ5qzJPFw2Z03m4bI5azIB2fzUZBqy+WjXjlUchoEoipI+5P8/dy3Lsi2NVsWm8IGdaV8nQyCHO28yFdmcNZmQbI5NpiWbfZOJyWbfZFqyOTSZlmyOTSYlm2OTKclmaDIl2YxNJiSbscmEZDM2mY5sTppMSDZjk5mymZf3j+/7JtOVzSKTrGwWmWRls8ikKptVJlHZrDKJymaVSVM2m0ySstlkUpLNIJOQbE5k0pHNKJOQbAaZtGSzl0lNNjuZ5GTzJpOibDaZNGWz/oKislk/jCmb1bBQ2dwNS5XN8lVU2dy/iiqb5auwsrnJpCubn5crm9ufYVg23ymbeXl5RpNZd7TJPHazyTx2sslsO9lknrvYZJ472GReO9hk3namyZztSpO53L9sMh+XzVWT+bhsrppMQDZ/azIN2Zw3mYpszppMSDbHJtOSzb7JxGSzbzIt2RyaTEs2xyaTks2xyZRkMzSZkmzGJhOSzdhkQrIZm0xHNidNJiSbsclM2czLyyObzLqjTeaxm03msZNNZtvJJvPcxSbz3MEm89rBJvO2M03mbFeazOX+tybTkc0oj5BsBnm0ZLOXR002O3nkZPMmj6JsNnk0ZbP+QqKyWR/elM1qVKhs7kalymZ5dVU291dXZbO8Oiubmzy6svl5ubK5/dmFZfOdspmXl3c/t8msO9pkHrvZZB472WS2nWwyz11sMs8dbDKvHWwybzvTZM52pclc7+sm83HZXDWZj8vmqskEZPO3JtOQzXmTqcjmrMmEZHNsMi3Z7JtMTDb7JtOSzaHJtGRzbDIp2RybTEk2Q5MpyWZsMiHZjE0mJJuxyXRkc9JkQrIZm8yUzby8vMW5cll3VC6P3ZTLYyflsu2kXJ67KJfnDsrltYNyedsZuZztilwu97ksOrIZZRGSzSCLlmz2sqjJZieLnGzeZFGUzSaLpmzWX0BUNuvDmrJZDQqVzd2gVNksr6rK5v6qqmyWV2Vlc5NFVzY/L1c2tz+zsGy+Tdn8AQ4Z7FepmEoFAAAAAElFTkSuQmCC') no-repeat;
	background-size: 100%
}

.header .special-info .text-info .rating-container .rate-score {
	display: inline-block;
	margin-left: 5px;
	font-size: 14px;
	vertical-align: middle;
	color: #ffbf2b
}

.header .special-info .text-info .rate-no-score-container .rate-no-score {
	font-size: 11px!important;
	color: rgba(255,255,255,.3)!important
}

.header .special-info .text-info .category-and-date {
	margin-top: 12px;
	font-size: 11px;
	color: #fff
}

.header .special-info .text-info .tags,.header .special-info .text-info .length,.header .special-info .text-info .actors {
	width: 90%;
	margin-top: 6px;
	overflow: hidden;
	font-size: 11px;
	white-space: nowrap;
	text-overflow: ellipsis;
	color: #fff
}

.header .special-info .text-info .gray {
	width: 90%;
	margin-top: 10px;
	overflow: hidden;
	font-size: 11px;
	white-space: nowrap;
	text-overflow: ellipsis;
	color: rgba(255,255,255,.5)
}

.header .special-info .intro-wrapper {
	padding: 0 10px;
	margin: 27px 0 0
}

.header .special-info .intro-wrapper .intro {
	display: -webkit-box;
	overflow: hidden;
	font-size: 14px;
	line-height: 1.4em;
	word-break: break-all;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 3
}

.header .special-info .intro-wrapper .lastline-space-ellipsis {
	position: relative;
	height: 60px;
	overflow: hidden;
	font-size: 14px;
	line-height: 20px;
	word-wrap: break-word;
	word-break: break-all;
	color: #fff
}

.header .special-info .intro-wrapper .lastline-space-ellipsis::before,.header .special-info .intro-wrapper .lastline-space-ellipsis::after {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	content: attr(title);
	color: #383838
}

.header .special-info .intro-wrapper .lastline-space-ellipsis::before {
	z-index: 1;
	display: block;
	max-height: 40px;
	overflow: hidden;
	background-color: #fff
}

.header .special-info .intro-wrapper .lastline-space-ellipsis::after {
	display: -webkit-box;
	padding-right: 1em;
	-webkit-box-sizing: border-box;
	box-sizing: border-box;
	text-indent: -2em;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 3
}

.header .special-info .intro-wrapper .right-arrow {
	position: absolute;
	right: 0;
	bottom: 2px;
	width: 14px;
	height: 16px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAaBAMAAABMRsE0AAAAJ1BMVEUAAAC6urq6urq6urq6urq6urq6urq6urq6urq6urq6urq6urq6uroMZqevAAAADHRSTlMAsx7f94RHq6aZdnWUtOYTAAAANUlEQVQI12NgUGCAAEYjKKPmMFQo5QxUiM2HFkI9ZyaAadYzByECMWcEqCbAsAYqwMANFQAADl0gCHwVLs4AAAAASUVORK5CYII=');
	background-repeat: no-repeat;
	background-size: contain
}

.header .special-info .btn-area {
	display: -webkit-box;
	display: box;
	margin: 12px 20px 0 10px
}

.header .special-info .btn-area div {
	width: 50%;
	height: 34px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	line-height: 35px;
	text-align: center;
	color: #fff;
	border-radius: 5px;
	-webkit-flex: 1;
	flex: 1
}

.header .special-info .btn-area div.btn-buy {
	margin-right: 10px;
	height: 36px;
	background-color: #ff8444
}

.header .special-info .btn-area div.btn-see {
	position: relative;
	margin-right: 10px;
	color: #00a5e0;
	background-color: #fff;
	-webkit-box-ordinal-group: 1;
	box-ordinal-group: 1
}

.header .special-info .btn-area div.btn-see:after {
	position: absolute;
	top: 0;
	left: 0;
	display: block;
	width: 100%;
	height: 100%;
	content: &quot;
	&quot;;pointer-events: none;
	border: 1px solid #cacccd;
	border-radius: 5px
}

.header .special-info .btn-area div.btn-subscribe,.header .special-info .btn-area div.btn-movie-sign {
	background-color: #00a5e0;
	height: 36px;
	-webkit-box-ordinal-group: 2;
	box-ordinal-group: 2
}

.header .special-info .btn-area div.btn-subscribe i.subscribed-unsign-icon,.header .special-info .btn-area div.btn-movie-sign i.subscribed-unsign-icon {
	display: inline-block;
	width: 15px;
	height: 15px;
	margin-right: 5px;
	margin-bottom: 2px;
	vertical-align: middle;
	background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDoyNjQ3QzA4NDIxMzIxMUU1QjAzODkxRDcyOThCMUQ2RiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDoyNjQ3QzA4NTIxMzIxMUU1QjAzODkxRDcyOThCMUQ2RiI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjI2NDdDMDgyMjEzMjExRTVCMDM4OTFENzI5OEIxRDZGIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjI2NDdDMDgzMjEzMjExRTVCMDM4OTFENzI5OEIxRDZGIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+c7t0mQAAAPtJREFUeNpi+P//PwMWfPg/BBzGIU+yHkYQgQUgCzIyEAfw6mECYk8gfgZV+B+PIfgwPj3PwHYAffTsPyqQRAqGI0As8Z8wIKTnGQMWTVuhCkFy0lA+IUBQDyiO/jPQATAx0AkMP4tYyMgvpIL/QzLolOhhkRcQXwfiKlpaBLJkPRCzATEP/phCAFylsiIQ52IR9wLin1C9bXj0ww3HZxEfED+EyleTYQnRFoFwJZKaahItIckidMt+k2AJyRahW9ZGQs0LBiwkpLB2aOnBQygpYwPI1cTwK4JsaGC+NXLQgRoPkjT20AuQj1JADBpa8hSIkxnp1GRgAAgwANXb8VzsHRViAAAAAElFTkSuQmCC') no-repeat;
	background-size: 100%
}

.header .special-info .btn-area div.btn-movie-signed {
	position: relative;
	height: 34px;
	color: #ccc;
	background-color: #fff
}

.header .special-info .btn-area div.btn-movie-signed i.subscribed-icon {
	display: inline-block;
	width: 15px;
	height: 15px;
	margin-right: 5px;
	margin-bottom: 2px;
	vertical-align: middle;
	background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDoxRDE0QTBBQjIxMzIxMUU1OThEOUU0NjY2MzRCQjI0MiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDoxRDE0QTBBQzIxMzIxMUU1OThEOUU0NjY2MzRCQjI0MiI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjFEMTRBMEE5MjEzMjExRTU5OEQ5RTQ2NjYzNEJCMjQyIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjFEMTRBMEFBMjEzMjExRTU5OEQ5RTQ2NjYzNEJCMjQyIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+SDNz9wAAAMpJREFUeNpi/P//PwM9ABMDncDws4jl7NmzQ95HkUA8H4iZwT6ioSWLoZYwAnECE40t+QvEe2kRdOiWJEL5VLUIpyXUtAivJegW8QNxMC0sQbYIZMl2IF4NxJnUtgTZIiMgNoYmxalEWka0JcgW7QfiUCD+RaRlJFmCHkebiLSMZEuwpTpClpFlCa4iCGYZKGGwQS0DgQ/kWoKvrMNm2T9yLSGUYdGDkWxLiCkZkC0j2xJiqwmYZfzkWkJKfbSJ0sKQcbS5RS4ACDAAvE1LB8MNacEAAAAASUVORK5CYII=') no-repeat;
	background-size: 100%
}

.header .special-info .btn-area div.btn-movie-signed:after {
	position: absolute;
	top: 0;
	left: 0;
	display: block;
	width: 100%;
	height: 100%;
	content: &quot;
	&quot;;pointer-events: none;
	border: 1px solid #cacccd;
	border-radius: 5px
}

.header .info-grade-icon {
	position: absolute;
	top: -6px;
	left: -8px;
	width: 17px;
	height: 17px;
	background-repeat: no-repeat;
	background-size: 100%
}

.header .info-grade-icon.level1 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/index-grade-1.png?f5a87a31)
}

.header .info-grade-icon.level2 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/index-grade-2.png?14052ddc)
}

.header .info-grade-icon.level3 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/index-grade-3.png?605bb097)
}

.header .info-grade-icon.level4 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/index-grade-4.png?604d8139)
}

.header .info-grade-icon.level5 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/index-grade-5.png?30504a64)
}

.header .info-grade-icon.level6 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/index-grade-6.png?1fcb4455)
}

.header .info-grade-icon.level7 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/index-grade-7.png?4daca63d)
}

.header .info-grade-icon.level8 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/index-grade-8.png?95998328)
}

.header .info-grade-icon.level9 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/index-grade-9.png?97a8827a)
}

.header .info-grade-icon.level10 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/index-grade-10.png?5953d0ef)
}

.header .info-grade-icon.level11 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/index-grade-11.png?2d9bb827)
}

.header .info-grade-icon.level12 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/index-grade-12.png?8116e5d8)
}

.header .info-grade-icon.level13 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/index-grade-13.png?599a8021)
}

.header .info-grade-icon.level14 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/index-grade-14.png?9cc26d5c)
}

.header .desc {
	position: absolute;
	top: 53px;
	left: 20px;
	z-index: 2;
	display: -webkit-box;
	padding-right: 18px;
	overflow: hidden;
	font-size: 13px;
	line-height: 15px;
	text-overflow: ellipsis;
	word-break: break-all;
	color: rgba(255,255,255,.6);
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2
}

.header .icon-heart-animation {
	content: &quot;
	&quot;;display: none;
	position: absolute;
	top: 11px;
	left: 33px;
	display: none;
	width: 11px;
	height: 10px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAUCAMAAAC+oj0CAAAAgVBMVEX///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+xZKUEAAAAK3RSTlPMALarEYOqGgJSygFoqE0Ex2WmxMufDqJtbibDVhQPFgOpVxgNMby7MMXGFV9gjAAAAJFJREFUeF5tzNcCgkAMRNEMC0svKkVQsdf//0ADKOKS+zQ5DyGgjFSQx34KpH6cBypqAUJjUV91dqthWQ3owvq5fqukBc1jXArKuJJ4Qy+ZtcRHWgvKuBOeP/eE7ZxPIISOqU7IjIPhjoeO4dVTrVl7BpLsi1kCjIzrbdD7A1OGqzpVLv4ZtibSNgxmLwrWkcXexQQFp1q3rxcAAAAASUVORK5CYII=');
	background-size: 100% 100%;
	-webkit-animation-name: heart-beat;
	-webkit-animation-duration: 1s;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAUCAMAAAC+oj0CAAAAgVBMVEX///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+xZKUEAAAAK3RSTlPMALarEYOqGgJSygFoqE0Ex2WmxMufDqJtbibDVhQPFgOpVxgNMby7MMXGFV9gjAAAAJFJREFUeF5tzNcCgkAMRNEMC0svKkVQsdf//0ADKOKS+zQ5DyGgjFSQx34KpH6cBypqAUJjUV91dqthWQ3owvq5fqukBc1jXArKuJJ4Qy+ZtcRHWgvKuBOeP/eE7ZxPIISOqU7IjIPhjoeO4dVTrVl7BpLsi1kCjIzrbdD7A1OGqzpVLv4ZtibSNgxmLwrWkcXexQQFp1q3rxcAAAAASUVORK5CYII=');
	background-size: 100% 100%;
	-webkit-animation-fill-mode: forwards
}

.header .charm-add-animation {
	display: inline-block;
	margin-left: -3px!important;
	opacity: 0;
	color: #00a5e0
}

.header.sending-heart {
}

.header.sending-heart .charm-add-animation {
	-webkit-animation-name: charm-add-animation;
	-webkit-animation-duration: 1s;
	-webkit-animation-delay: 0s;
	-webkit-animation-fill-mode: none
}

.header.sending-heart .charm-add-one-animation {
	-webkit-animation-name: charm-add-one-animation;
	-webkit-animation-duration: 1s;
	-webkit-animation-delay: 0s;
	-webkit-animation-fill-mode: none
}

.header.sending-heart .icon-heart-animation {
	display: block
}

.star-info-container {
	position: relative;
	height: 20px;
	padding: 5px 20px 5px 34px;
	margin-top: 6px;
	font-size: 14px;
	line-height: 20px;
	background: #fff
}

.star-info-container .star-icon {
	position: absolute;
	top: 50%;
	left: 12px;
	display: inline-block;
	width: 15px;
	height: 16px;
	margin-top: -8px;
	background-size: 100% 100%
}

.star-info-container .star-title {
	display: inline-block;
	width: 200px;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis
}

.star-info-container .star-link {
	position: relative;
	float: right;
	color: #7e51d6
}

.star-info-container .star-link:after {
	position: absolute;
	top: -1px;
	right: -12px;
	font-size: 16px;
	content: &quot;
	&gt;&quot;
}

.sign-tips-wrap {
	position: fixed;
	top: 140px;
	z-index: 1000;
	display: none;
	width: 100%
}

.sign-tips-wrap .sign-tips {
	position: relative;
	width: 228px;
	height: 76px;
	margin: 0 auto;
	font-size: 14px;
	line-height: 76px;
	-webkit-animation-name: pulse;
	-webkit-animation-duration: 500ms;
	text-align: center;
	color: #929292;
	border-radius: 4px;
	background-color: rgba(0,0,0,.9)
}

.sign-tips-wrap .sign-tips #sign-order {
	font-size: 20px;
	color: #ff6f57
}

.sign-tips-wrap .sign-tips .info-grade-icon {
	position: relative;
	top: 7px;
	left: -2px;
	display: inline-block
}

.sign-tips-wrap .sign-tips #sign-levelup-em {
	font-size: 18px;
	color: #ffc869
}

.sign-tips-wrap .sign-continue-tips {
	position: relative;
	width: 228px;
	height: 140px;
	margin: 0 auto;
	font-size: 14px;
	line-height: 76px;
	-webkit-animation-name: pulse;
	-webkit-animation-duration: 500ms;
	text-align: center;
	color: #fff;
	border-radius: 3px;
	background-color: rgba(0,0,0,.85)
}

.sign-tips-wrap .sign-continue-tips .main-tips {
	font-size: 13px;
	line-height: 14px
}

.sign-tips-wrap .sign-continue-tips .second-tips {
	font-size: 12px;
	line-height: 14px;
	color: #a6a6a6
}

.sign-tips-wrap .sign-continue-tips .sign-honour-tip {
	position: absolute;
	top: 100px;
	left: 32px;
	width: 165px;
	height: 22px
}

.sign-tips-wrap .sign-continue-tips .sign-honour-tip span {
	display: inline-block;
	float: left;
	height: 16px;
	padding: 0 1px;
	margin: 0 10px;
	font-size: 13px;
	line-height: 16px;
	text-align: center;
	color: #f7a20c;
	border: 1px solid #f7a20c;
	border-radius: 2px
}

.sign-tips-wrap .sign-continue-tips .sign-honour-tip-left {
	float: left;
	width: 50px;
	height: 10px;
	margin-top: 4px;
	background-image: url(../img/honour_tip.png);
	background-size: 100%
}

.sign-tips-wrap .sign-continue-tips .sign-honour-tip-right {
	float: left;
	width: 50px;
	height: 10px;
	margin-top: 4px;
	-webkit-transform: rotate(180deg);
	background-image: url(../img/honour_tip.png);
	background-size: 100%
}

.sign-tips-wrap .sign-continue-tips #sign-star-charm-added {
	position: absolute;
	top: -22px;
	right: 0;
	left: 0;
	display: none;
	font-size: 12px;
	text-align: center
}

.sign-tips-wrap .sign-continue-tips #sign-continue-day-list {
	height: 70px
}

.sign-tips-wrap .sign-continue-tips #sign-continue-day-list:before {
	position: absolute;
	top: 38px;
	left: 35px;
	display: block;
	width: 150px;
	height: 0;
	content: &quot;
	&quot;;border: 1px dotted #595959
}

.sign-tips-wrap .sign-continue-tips #sign-continue-day-list li {
	position: relative;
	display: inline-block;
	width: 20px;
	height: 20px;
	margin-right: 4px;
	font-size: 11px;
	line-height: 20px;
	text-align: center;
	color: #333;
	border-radius: 10px;
	background-color: #595959
}

.sign-tips-wrap .sign-continue-tips #sign-continue-day-list li.active {
	-webkit-animation-name: show;
	-webkit-animation-duration: 500ms;
	color: #fff;
	background-color: #8ac628
}

#sign-continue-tips-gt7 .sign-continue-tips {
	height: 130px
}

#sign-continue-tips-gt7 .main-tips {
	padding-top: 26px
}

#sign-continue-tips-gt7 .second-tips {
	margin-top: 20px;
	line-height: 20px
}

#sign-continue-tips-eq7 .main-tips {
	padding-top: 70px
}

#sign-continue-tips-eq7 .second-tips {
	margin-top: 16px
}

#sign-continue-tips-eq7 .sign-honour-tip {
	top: 27px
}

.top {
	position: relative;
	width: 100%;
	margin: 5px 0;
	border-bottom: 0;
	border-left: 0;
	background-color: #efeff4
}

.top .top-list-wrap {
}

.top .top-list {
	position: relative;
	z-index: 2;
	height: 31px;
	padding-left: 9px;
	line-height: 31px;
	background-color: #fff
}

.top .top-list.section-1px::after {
	display: none
}

.top .top-list:first-child.section-1px::before {
	display: none
}

.top .top-list .top-list-item:after {
	display: none
}

.top:after {
	z-index: 1;
	display: block;
	clear: both;
	content: &quot;
	&quot;
}

.top .link {
	display: block;
	width: 98%;
	padding-left: 0;
	overflow: hidden;
	font-size: 15px;
	white-space: nowrap;
	text-overflow: ellipsis;
	word-break: break-all;
	color: #000
}

.top .link:last-child {
	border-bottom: 0
}

.top .link label {
	display: inline-block;
	height: 15px;
	min-width: 13px;
	padding: 0 1px;
	font-size: 11px;
	line-height: 15px;
	-webkit-transform: translateY(-2px);
	text-align: center;
	color: #fff;
	border-radius: 2px
}

.top .link label.rec {
	background-color: #fb6e52
}

.top .link label.best {
	background-color: #fe9624
}

.top .link label.act {
	background-color: #5eb0f5
}

.top .link label.new {
	background-color: #f7668a
}

.top .link label.serial {
	width: 13px;
	padding: 0;
	-webkit-transform: translateY(2px);
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/lianzai.png?deed6ff0) no-repeat 0 0;
	background-size: 13px 15px
}

.top .link .name {
	margin-left: 1px;
	overflow: hidden;
	font-size: 15px;
	white-space: nowrap;
	text-overflow: ellipsis;
	color: #373737
}

.top .link .num {
	display: inline-block;
	float: right;
	width: 45px;
	height: 15px;
	margin: 14px 7px 0 0;
	font-size: 14px;
	line-height: 15px;
	text-indent: 19px;
	color: #a6a6a6;
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/detail-sprite.png?11008495) no-repeat -60px 0;
	background-size: 120px 40px
}

.top .top-related-wrap {
	padding-left: 9px;
	background: #fff
}

.top .top-related {
	height: 44px;
	line-height: 44px;
	pointer-events: none;
	color: #373737
}

.top .top-related:after {
	bottom: 0
}

.top .top-related:before {
	border: 0
}

.top .top-related .logo {
	position: relative;
	top: -2px;
	width: 30px;
	height: 30px;
	margin-right: 4px;
	vertical-align: middle;
	border-radius: 3px
}

.top .top-related .text {
	position: absolute;
	right: 0;
	display: inline-block;
	padding-right: 28px;
	color: #bbb
}

.top .top-related .text:after {
	position: absolute;
	top: 14px;
	right: 8px;
	width: 11px;
	height: 11px;
	content: &quot;
	&quot;;-webkit-transform: rotate(45deg);
	-webkit-transform-origin: 0 0;
	pointer-events: none;
	border-top: 1px solid #cacccd;
	border-right: 1px solid #cacccd
}

.list {
	padding: 0;
	padding-bottom: 10px;
	margin-top: 5px;
	list-style: none
}

.list li {
	position: relative;
	z-index: 1;
	padding: 14px 10px 0;
	border-radius: 2px;
	background-color: #fff
}

.list li:before,.list li:after {
	border-color: #e6e6e6;
	border-radius: 2px
}

.list li.section-1px::after {
	display: none
}

.list li:first-child.section-1px::before {
	display: none
}

.list li .list-content .face {
	position: relative;
	top: -2px;
	width: 15px;
	height: 15px
}

.list h3 {
	overflow: hidden;
	font-size: 16px;
	font-weight: 500;
	line-height: 35px;
	text-overflow: ellipsis;
	color: #373737
}

.list h3 label {
	height: 17px;
	padding: 0 2px;
	font-size: 13px;
	line-height: 17px;
	text-align: center;
	color: #fff;
	border-radius: 2px;
	background-color: #90dbaf
}

.list h3 label.cls {
	background-color: #90dbaf
}

.list h3 label.best {
	background-color: #fe9623
}

.list h3 label.pic {
	background-color: #37c99b
}

.list h3 label.act {
	background-color: #5eb0f5
}

.list h3 label.new {
	background-color: #5ad257
}

.list h3.text {
	position: relative;
	display: -webkit-box;
	margin-bottom: 2px;
	overflow: hidden;
	font-size: 17px;
	font-weight: 600;
	line-height: 20px;
	word-break: break-all;
	color: #454545;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2
}

.list h3.text.pho-list {
	margin-bottom: 8px
}

.list h3.text .post-tags {
	display: inline-block;
	height: 100%;
	line-height: 100%;
	vertical-align: top
}

.list h3.text label {
	display: inline-block;
	height: 15px;
	min-width: 13px;
	padding: 0 1px;
	margin-right: 5px;
	font-size: 11px;
	line-height: 15px
}

.list h3.text label.rec {
	background: #fb6e52
}

.list .detail-text-content {
	padding-top: 1px;
	padding-bottom: 15px
}

.list .detail-text-content .text-container {
	height: 62px
}

.list .text-container {
	max-height: 62px;
	overflow: hidden
}

.list .list-content {
	display: -webkit-box;
	padding-top: 2px;
	margin-bottom: 2px;
	overflow: hidden;
	font-size: 14px;
	line-height: 18px;
	word-break: break-all;
	color: gray;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2
}

.list p {
	display: -webkit-box;
	padding-bottom: 6px;
	margin: 6px 11px 0;
	overflow: hidden;
	font-size: 13px;
	line-height: 20px;
	letter-spacing: -.8px;
	text-overflow: ellipsis;
	word-break: break-all;
	color: gray;
	border-bottom: 1px solid #efefef;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2
}

.list .img {
	position: relative;
	width: 100%;
	height: 150px;
	max-height: 150px;
	overflow: hidden;
	line-height: 0;
	text-align: center;
	background-color: #f6f6f6
}

.list .img img {
	height: auto;
	max-width: 100%
}

.list .img img.error {
	width: 65px;
	height: 55px;
	margin: 0 0 10px 10px
}

.list .img.err-img {
	padding: 80px 0;
	text-align: center;
	background: #e5e5e5
}

.list .num {
	position: absolute;
	top: 11px;
	right: 11px;
	z-index: 2;
	display: inline-block;
	width: 28px;
	height: 28px;
	font-size: 16px;
	line-height: 28px;
	text-align: center;
	color: #fff;
	border-radius: 2px;
	background-color: rgba(0,0,0,.3)
}

.list .info {
	position: relative;
	margin-top: 2px;
	overflow: hidden;
	font-size: 12px;
	line-height: 16px;
	color: #a6a6a6
}

.list .info.noimg {
	border-top: 0
}

.list .info .nick {
	display: inline-block;
	float: left;
	max-width: 250px;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis
}

.list .info .nick .nick-vipno {
	color: #784dcf
}

.list .info .nick .single-ellipsis {
	display: block;
	float: left;
	max-width: 55px
}

.list .info .nick .single-ellipsis-long {
	display: block;
	float: left;
	max-width: 70px;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis
}

.list .info .nick .ver-middle {
	position: absolute;
	vertical-align: middle
}

.list .info .nick .split {
	margin: 0 4px
}

.list .info .nick .author {
	position: relative;
	display: inline-block;
	width: 11px;
	height: 11px;
	margin-top: -3px;
	margin-left: 2px;
	vertical-align: middle;
	background-repeat: no-repeat;
	background-size: 11px 11px
}

.list .info .nick .male {
	background-image: url(../img/ico-male.png)
}

.list .info .nick .female {
	background-image: url(../img/ico-female.png)
}

.list .info .nick .grade {
	position: relative;
	display: inline-block;
	height: 13px;
	padding: 0 1px 0 14px;
	margin-top: -3px;
	margin-right: 4px;
	margin-left: -1px;
	font-size: 10px;
	line-height: 13px;
	vertical-align: middle;
	color: #fff;
	border-radius: 2px;
	background: #f8b913 url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/index-grade-t1.png?0b6aa83b) no-repeat 2px 1px;
	background-size: 11px 11px
}

.list .info .nick .grade-t1 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/index-grade-t1.png?0b6aa83b)
}

.list .info .nick .grade-t2 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/index-grade-t2.png?0bbe16e7)
}

.list .info .nick .grade-t3 {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/index-grade-t3.png?32f47a3e)
}

.list .info .fl-right {
	float: right;
	margin-top: -1px
}

.list .info .fl-right .time {
	display: inline-block;
	height: 15px;
	line-height: 15px;
	vertical-align: middle
}

.list .info .fl-right .reply-num {
	display: inline-block;
	height: 15px;
	max-width: 45px;
	overflow: hidden;
	line-height: 15px;
	white-space: nowrap;
	text-overflow: ellipsis;
	color: #7fabd2
}

.list .info .fl-right .reply-num-text,.list .info .fl-right .read-num-text {
	display: inline-block;
	height: 15px;
	max-width: 80px;
	margin-top: 2px;
	margin-right: 5px;
	overflow: hidden;
	font-size: 10px;
	line-height: 15px;
	vertical-align: middle;
	white-space: nowrap;
	text-overflow: ellipsis
}

.list .info .fl-right .read-icon,.list .info .fl-right .like-icon,.list .info .fl-right .reply-icon {
	position: relative;
	display: inline-block;
	width: 11px;
	height: 11px;
	margin-top: 1px;
	vertical-align: middle;
	background-size: 100%
}

.list .info .fl-right .read-icon {
	position: relative;
	top: 1px;
	width: 12px;
	height: 10px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAUCAMAAACgaw2xAAAAZlBMVEUAAACsrKysrKysrKysrKysrKysrKysrKysrKzk5OSsrKysrKysrKysrKysrKysrKysrKzk5OTk5OTk5OTk5OTk5OTk5OSsrKysrKzk5OTk5OSsrKzk5OTk5OTk5OTk5OTk5OTk5OS6ts8FAAAAIXRSTlMAHPeVm+V3caH1aSwkBvy4RxFfCNC7k4qJVUknw8KCdm4dsru3AAAAr0lEQVQY052R2RKDIAxFAVndFbVVu/H/P1makJYZZ/pgXhLumUluAkvBdaeEUJ3mpKBs22vPm4b3l9ZmqBT699CipNIWFWTvIVWFRF0WmMcQphEqJCbpbAgxFiSGsVrUOQjrp9yjKA2NegK4Q20kU+CuGYaXA+DQv0rAg5gDajURmLGVjcN38Epgg1VE/bW7oP5Au2W24Hpzbt5Qt7R6xTDoJP+OeDy75KgdP+psvAEqJQxqJ4gFpgAAAABJRU5ErkJggg==')
}

.list .info .fl-right .like-icon {
	position: relative;
	top: -1px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAMAAADzapwJAAAA21BMVEWmpqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqampqYAAACmpqZDqKzUAAAASHRSTlNAeEdSddzzM7A0lB7j7bM9FA1NoKkPJ+moCXQcbTgY9gRP6tsbwz5e3ecGZSho9K1YI1YRL8n1+5cDiND534f87gj6uCqW6wB0SH4LAAAAo0lEQVR4Xm3NRQ7DQAxA0TIzMzNzGBry3P9EnZGaRMr4LyzrLewYBBVKlb6/h1wjdVvmuUigWpY4dlSQrA7HDQ2gO+a41YS2sIryZurASIm8fB73W5gtCctYDyn3Fqbpee4JgvQcZUIXNsLyGZSzFsrJNMopG2XXRdkeoLybY/w1HIxf6gHj8xswvuqMP3QonjjxLz8ud8aiQD7xhGyRf9oNAH4Tj3u/JcjtAAAAAABJRU5ErkJggg==')
}

.list .info .fl-right .reply-icon {
	top: 1px;
	width: 12px;
	height: 10px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAUCAMAAACgaw2xAAAAPFBMVEUAAACsrKzk5OSsrKysrKysrKysrKzk5OSsrKysrKysrKysrKzk5OSsrKysrKysrKysrKysrKysrKzk5OST+fY/AAAAEnRSTlMAS+cLsfO+SXqEHBLk49rYi4AFsFYbAAAAd0lEQVQY031QWw6AIAxjbDzEN97/rgYwlojQj42s0LKqEciaCBhLz3wSYgUwyVTuy/xRmCW/sfSjnarhhmCTalQt4luc1g6tIvR1abQRAanFoYFozLvf7S7YjaQbImJffT76tcQOhC0xfguNYdgPOnbMAT7FV143oI4El6U4Bo0AAAAASUVORK5CYII=')
}

.list h3.live-title {
	padding-right: 65px;
	margin-right: 0
}

.list .live-title .post-tags {
	float: left;
	margin-right: 5px;
	overflow: hidden
}

.list .live-title .post-tags label {
	margin-right: 1px
}

.list .live-img-wrapper {
	float: left;
	width: 80px;
	height: 60px
}

.list .live-img {
	width: 80px;
	height: 60px
}

.list .live.info {
	height: 72px;
	padding-top: 3px
}

.list .live .content {
	min-height: 46px;
	margin-top: -2px;
	margin-left: 90px;
	line-height: 18px;
	word-break: break-all;
	color: gray
}

.list .live .bonus-info {
	text-align: right
}

.list .play {
	position: absolute;
	top: 1px;
	left: 7px;
	width: 63px;
	height: 63px;
	-webkit-transform: scale(0.5,.5);
	border: 3px solid #fff;
	border-radius: 50%;
	background: transparent
}

.list .triangle {
	position: absolute;
	top: 50%;
	left: 50%;
	width: 29px;
	height: 32px;
	margin-top: -17px;
	margin-left: -11px;
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/play.png?a6969046) no-repeat
}

.list .act-common-wrap {
	min-height: 112px
}

.list .act-img-wrapper {
	position: absolute;
	top: 15px;
	left: 10px;
	z-index: 111;
	width: 90px;
	height: 123px;
	overflow: hidden
}

.list .act-img-wrapper img {
	position: absolute;
	top: 50%;
	left: 0;
	width: 90px;
	-webkit-transform: translateY(-50%)
}

.list .act-info {
	position: relative;
	padding: 0 0 12px;
	overflow: hidden
}

.list .act-info h3.act {
	padding-right: 0;
	padding-left: 0;
	margin: 0 0 15px;
	font-size: 17px;
	-webkit-line-clamp: 1
}

.list .act-info h3.act .post-tags {
	margin-right: 5px;
	overflow: hidden
}

.list .act-info h3.act .post-tags label {
	margin-right: 1px
}

.list .act-info .act-comm {
	height: 16px;
	padding-left: 15px;
	margin-bottom: 4px;
	overflow: hidden;
	font-size: 12px;
	line-height: 16px;
	color: gray
}

.list .act-info .activity-info-wrap&gt;div {
	margin-bottom: 6px;
	font-size: 14px;
	color: gray
}

.list .act-info .act-time {
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/act-time.png?92a17a15) no-repeat 0 2px;
	background-size: 13px 13px
}

.list .act-info .act-address {
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/act-addr.png?e065d1fb) no-repeat 0 1px;
	background-size: 13px 13px
}

.list .act-info .act-tag {
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/act-tag.png?0c4fb355) no-repeat 0 2px;
	background-size: 13px 13px
}

.list .act-info .status {
	display: inline-block;
	height: 22px;
	padding: 0 10px;
	margin: 10px 0 0;
	line-height: 22px;
	background-color: #f2f2f2
}

.list .act-info .status .split {
	display: inline-block;
	margin: 0 3px
}

.list .act-info.music-ticket .act-comm {
	padding-left: 0;
	background-image: none
}

.list .act-info.music-ticket .act-comm-wrap {
	top: 33px
}

.list .act-info.music-ticket .purchase-button {
	float: left;
	width: 88px;
	height: 25px;
	margin: 5px 0 0 10px;
	font-size: 14px;
	line-height: 25px;
	text-align: center;
	color: #fff;
	border-radius: 4px;
	background: #fe9623
}

.list .act-mini .act-img-wrapper {
	top: 12px;
	width: 60px;
	height: 80px
}

.list .act-mini .act-img-wrapper img {
	width: 100%
}

.list .act-mini .act-info {
	height: 80px;
	padding-left: 70px
}

.list .act-mini .act-info .time {
	margin-right: 14px
}

.list .act-mini .act-info .status {
	padding: 0;
	margin: 3px 0 0;
	font-size: 11px;
	background: 0 0
}

.list .act-mini .act-info .time,.list .act-mini .act-info .status {
	display: inline-block
}

.list .act-mini .act-info .people-num {
	margin-right: 2px;
	font-size: 14px;
	color: #f86912
}

.list .act-mini h3 {
	font-size: 16px;
	line-height: 20px;
	color: #000
}

.list .act-mini h3.act {
	display: -webkit-box;
	margin-top: -1px;
	margin-bottom: 9px;
	white-space: normal;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2
}

.list .act-mini h3.act .post-tags {
	float: none;
	margin-right: 2px
}

.list .act-mini .act-comm-wrap {
	font-size: 12px;
	color: gray
}

.list .act-mini .act-comm-wrap div {
	height: 14px;
	margin-bottom: 1px;
	line-height: 14px
}

.list .act-mini .tag-expire {
	position: relative;
	display: inline-block;
	width: 38px;
	height: 15px;
	margin-left: 1px;
	vertical-align: bottom;
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/act-expire.png?a7ac0640);
	background-repeat: no-repeat;
	background-size: 100%
}

.list .icons {
	height: 30px
}

.list .icons a {
	float: left;
	width: 25px;
	width: 25px;
	height: 25px;
	height: 25px;
	margin-left: 7px;
	font-size: 14px;
	line-height: 25px;
	line-height: 25px;
	border: solid 1px #e6e6e6;
	border-radius: 25px
}

.list .icons a:active {
	border-color: #d3d3d3
}

.list .icons .delete {
	width: 25px;
	-webkit-transition: width .2s ease-out;
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/delete.png?81530633) no-repeat center;
	background-size: 11px 13px
}

.list .icons .delete.delete-confirm {
	width: 43px;
	height: 25px;
	overflow: hidden;
	-webkit-transition: width .3s ease;
	text-align: center;
	color: #a6a6a6;
	background: 0 0
}

.list .icons .add-best {
	width: 25px;
	height: 25px;
	text-align: center;
	color: #fff;
	border-color: #fe9624;
	border-radius: 3px;
	background-color: #fe9624
}

.list .icons .add-best.add-best-disabled {
	color: #b8b8b8;
	border-color: #e7e7e7;
	background-color: #e7e7e7
}

.list .icons .add-best.add-best-confirm {
	color: #fff;
	border-color: #fe9624;
	background-color: #fe9624
}

.ios .header .special-info .text-info .name {
	letter-spacing: -1px
}

.ios .list .act-mini .tag-expire:before {
	top: 0;
	left: -1px
}

.loading {
	padding: 10px 0 40px;
	margin-bottom: 40px;
	font-size: 14px;
	line-height: 23px;
	text-align: center;
	vertical-align: top;
	color: #a6a6a6
}

.loading .no-content {
	width: 190px;
	margin: 20px auto 0;
	font-size: 14px;
	text-align: center;
	color: #ababab
}

.loading .no-content:before {
	display: block;
	height: 96px;
	margin: 0 0 20px 14px;
	content: &quot;
	&quot;;background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/../img/no-content.png?0657324a);
	background-repeat: no-repeat;
	background-position: center;
	background-size: 112px 96px
}

@-webkit-keyframes tips_fade {
	0% {
		-webkit-transform: translateY(-10px);
		opacity: 0
	}

	25% {
		-webkit-transform: translateY(-20px);
		opacity: 1
	}

	75% {
		-webkit-transform: translateY(-20px);
		opacity: 1
	}

	100% {
		-webkit-transform: translateY(-20px);
		opacity: 0
	}
}

@-webkit-keyframes pulse {
	0% {
		-webkit-transform: scale(1);
		transform: scale(1)
	}

	50% {
		-webkit-transform: scale(1.1);
		transform: scale(1.1)
	}

	100% {
		-webkit-transform: scale(1);
		transform: scale(1)
	}
}

@-webkit-keyframes show {
	0% {
		background-color: #595959
	}

	100% {
		background-color: #8ac628
	}
}

.ui-test-nav {
	display: none
}

body .info-grade-value-inner-bar span {
	display: none
}

body.waiting-render .wraper {
	bottom: 0
}

body.waiting-render .header .info .sign {
	display: none!important
}

.publish-btn-container {
	position: fixed;
	bottom: 0;
	left: 50%;
	z-index: 10;
	width: 100%;
	height: 43px;
	padding: 5px 0;
	margin-left: -50%;
	background: #f7f7f7
}

.publish-btn {
	position: relative;
	width: 94%;
	margin: 0 auto;
	line-height: 43px;
	text-align: center;
	border-radius: 4px;
	background: #fff;
	background: -webkit-gradient(linear,0 0,0 100%,from(#fff),to(#fafafa))
}

.publish-btn .publish-btn-icon {
	position: relative;
	top: 4px;
	display: inline-block;
	width: 20px;
	height: 20px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAMAAAC7IEhfAAAAY1BMVEUYtO3///8YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0YtO0l34IDAAAAIHRSTlMAAAIGCQoLFB0iLC1NTmRseYSGq6+7wMHCw8Tc6u3v8SEzgQMAAACkSURBVHhe7c3LDoJQEATRARUV3w9EEWT+/ysNYdFO2vQ1Ju6s9UnKssxkk+1hk5tlKVhc3L2ZJ2FRe3vqvckJslvZsvc1Q3ZmZz8O0Ln74tWVre8ApaunIzQuusIAtQPULkByJRygdASFA9QOUDtA7QCl2wconEfI7jp+nSA7hsK9hbPgBAxOQe/gNLzBJSCcho+q6uAEHAqOoOgPZV/Az/oBfAIqNC2KAKof8QAAAABJRU5ErkJggg==');
	background-size: 100% 100%
}

.publish-btn:after {
	position: absolute;
	top: 0;
	left: 0;
	width: 200%;
	height: 200%;
	content: &quot;
	&quot;;-webkit-transform: scale(0.5);
	-webkit-transform-origin: 0 0;
	pointer-events: none;
	border: 1px solid #cacccd;
	border-radius: 8px
}

.publish-btn #tab-publish {
	font-size: 17px;
	color: #00a5e0
}

.publish-btn.active #tab-publish {
	color: rgba(0,165,224,.5)
}

.publish-btn.active .publish-btn-icon {
	opacity: .5
}

body.ui-test-group.wraper .list {
	margin-top: 0;
	margin-bottom: 10px
}

body.ui-test-group.wraper .header {
	height: 95px
}

#js_bar_top.hide-top-border:before {
	display: none
}

.indexpage2,.indexpage3,.indexpage4,.indexpage5,.indexpage6,.indexpage7,.indexpage8 {
	position: absolute;
	top: 0;
	right: 0;
	bottom: 44px;
	left: 0;
	display: none
}

.indexpage1,.indexpage2,.indexpage6 {
	position: static
}

#js_bar_wraper {
	background: #efeff4
}

body.android {
	height: auto
}

#js_bar_loading {
	height: auto
}

#follow-mask {
	position: fixed;
	top: 0;
	left: 0;
	z-index: 10000;
	display: none;
	width: 100%;
	height: 100%;
	-webkit-animation-name: follow-mask-in;
	-webkit-animation-duration: 300ms;
	background-color: rgba(0,0,0,.9)
}

#follow-mask.out {
	-webkit-transition-duration: 300ms;
	-webkit-transition-property: opacity;
	opacity: 0
}

#follow-mask #follow-tips {
	position: relative;
	z-index: -1;
	width: 280px;
	height: 65px;
	padding-top: 35px;
	margin: 125px auto 0;
	line-height: 25px;
	-webkit-animation-name: follow-tips-in;
	-webkit-animation-duration: 400ms;
	text-align: center;
	color: #fff;
	border-radius: 4px;
	background-color: #2d9ae8
}

#follow-mask #follow-tips #follow-tips-num {
	font-size: 26px;
	vertical-align: text-bottom;
	color: #ffba01
}

#follow-mask #follow-tips-icon {
	width: 58px;
	height: 58px;
	margin: -130px auto;
	-webkit-animation-name: follow-tips-icon-in;
	-webkit-animation-duration: 1000ms;
	-webkit-animation-delay: 300ms;
	opacity: 0;
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/../img/follow-tips.png?bbd34155);
	background-size: 100%
}

#follow-mask #follow-sign {
	width: 200px;
	height: 33px;
	margin: 90% auto 0;
	font-size: 14px;
	line-height: 33px;
	-webkit-animation-name: breathe;
	-webkit-animation-duration: 1400ms;
	-webkit-animation-timing-function: ease-in-out;
	-webkit-animation-delay: 2000ms;
	-webkit-animation-iteration-count: infinite;
	-webkit-animation-direction: alternate;
	text-align: center;
	opacity: .4;
	color: #fff;
	border: 1px solid #fff;
	border-radius: 17px;
	box-shadow: 0 1px 2px rgba(255,255,255,.2)
}

#follow-mask #follow-mask-close {
	position: absolute;
	top: 19px;
	right: 12px;
	display: inline-block;
	width: 18px;
	height: 2px;
	font-size: 0;
	line-height: 0;
	-webkit-transform: rotate(45deg);
	vertical-align: middle;
	background: #5f5f5f
}

#follow-mask #follow-mask-close:after {
	display: block;
	width: 18px;
	height: 2px;
	content: &quot;
	.&quot;;-webkit-transform: rotate(-90deg);
	background: #5f5f5f
}

@-webkit-keyframes follow-mask-in {
	0% {
		opacity: 0
	}

	100% {
		opacity: 1
	}
}

@-webkit-keyframes follow-tips-in {
	0% {
		-webkit-transform: scale(0.3);
		opacity: 0
	}

	50% {
		-webkit-transform: scale(1.1);
		opacity: 1
	}

	100% {
		-webkit-transform: scale(1);
		opacity: 1
	}
}

@-webkit-keyframes follow-tips-icon-in {
	0% {
		-webkit-transform: scale(3.5);
		opacity: .6
	}

	45% {
		-webkit-transform: scale(0.95);
		opacity: 1
	}

	100% {
		-webkit-transform: scale(1);
		opacity: 1
	}
}

@-webkit-keyframes breathe {
	0% {
		opacity: .8;
		box-shadow: 0 1px 8px #fff
	}

	100% {
		opacity: .4;
		box-shadow: 0 1px 2px rgba(255,255,255,.2)
	}
}

@-webkit-keyframes follow-mask-out {
	0% {
		opacity: 1
	}

	100% {
		opacity: 0
	}
}

#guide-wsq-mask {
	position: fixed;
	top: 0;
	left: 0;
	z-index: 1005;
	display: none;
	width: 100%;
	height: 100%;
	-webkit-animation-name: follow-mask-in;
	-webkit-animation-duration: 300ms;
	background-color: rgba(0,0,0,.9)
}

#guide-wsq-mask.hide-anim {
	-webkit-animation-name: follow-mask-out
}

#guide-wsq-mask .wsq-pic {
	width: 100%;
	margin-top: 50px
}

#guide-wsq-mask .wsq-text {
	width: 100%;
	margin-top: 40px
}

#guide-wsq-mask .guide-wsq-center {
	position: absolute;
	top: 50%;
	left: 50%;
	width: 100%;
	height: 100%;
	-webkit-transform: translate(-50%,-50%);
	transform: translate(-50%,-50%)
}

#guide-wsq-mask .wsq-skip {
	position: absolute;
	top: 12px;
	right: 12px;
	width: 45px;
	height: 25px;
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/../img/nopack/wsq/pass.png?1ee52f5c);
	background-size: cover
}

#guide-wsq-mask .smaller {
	top: 10px;
	right: 10px;
	-webkit-transform: scale(0.9);
	transform: translate(0.9)
}

#guide-wsq-mask .guide-wsq-button {
	position: relative;
	top: -1px;
	left: 50%;
	width: 150px;
	height: 45px;
	margin-top: 26px;
	font-size: 15px;
	line-height: 45px;
	-webkit-transform: translateX(-50%);
	transform: translateX(-50%);
	text-align: center;
	color: #fff
}

#guide-wsq-mask .guide-wsq-button:after {
	position: absolute;
	top: 0;
	left: 0;
	z-index: 2;
	display: block;
	width: 200%;
	height: 200%;
	content: &quot;
	&quot;;-webkit-transform: scale(0.5);
	-webkit-transform-origin: 0 0;
	border: 1px solid #fff;
	border-radius: 12px
}

.price {
	display: -moz-box;
	display: -webkit-box;
	display: box;
	float: left;
	width: auto;
	height: 25px;
	padding: 0 6px;
	margin-top: 5px;
	overflow: hidden;
	font-size: 12px;
	line-height: 25px;
	text-overflow: ellipsis;
	word-break: break-all;
	color: #fb6e52;
	border-radius: 4px;
	background-color: #e4d8d1;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2
}

.logo-rank {
	position: absolute;
	top: 53px;
	left: 11px;
	z-index: 2;
	width: 62px;
	height: 15px;
	padding: 0 1px;
	overflow: hidden;
	font-size: 10px;
	line-height: 15px;
	-webkit-transform: translate(-1px);
	text-align: center;
	color: #fff;
	border-radius: 0 0 3px 3px;
	background-color: #fc4527
}

.logo-rank.not-in-charm-rank {
	color: #fff;
	background-color: #8e8e8e
}

.logo-rank span {
	display: block;
	width: 125%;
	-webkit-transform: scale(0.8);
	-webkit-transform-origin: 0 center
}

.focus-btn {
	position: relative;
	width: 291px;
	height: 44px;
	margin: 0 auto;
	margin-top: -54px;
	margin-bottom: 58px;
	font-size: 18px;
	line-height: 44px;
	text-align: center;
	color: #fff;
	border-radius: 4px;
	background: #0079ff
}

.focus-btn.unvote {
	background: -webkit-gradient(linear,left top,left bottom,from(#fc6156),to(#f75549),color-stop(49%,#fc6156))
}

.ios .focus-btn {
	margin-bottom: 15px
}

.list .img-gallary {
	position: relative;
	display: inline-block;
	width: 90px;
	height: 90px;
	overflow: hidden;
	border-radius: 1px;
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/barindex-default.png?280588c4);
	background-size: 100% 100%
}

.list .img-gallary.hide-bg {
	background-image: none
}

.list .img-gallary.audio-img {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/audio-background.png?618b67bb)
}

.list .img-gallary.err-img&gt;img {
	width: 100%!important;
	height: 100%!important;
	margin: 0!important
}

.list .img-gallary .share-count-tips,.list .img-gallary .pic-count-tips {
	position: absolute;
	bottom: 0;
	left: 0;
	width: 100%;
	height: 24px;
	font-size: 14px;
	line-height: 24px;
	text-align: center;
	color: #fff;
	background: rgba(0,0,0,.6)
}

.list .img-gallary .pic-count-tips {
	right: 0;
	bottom: 0;
	left: auto;
	width: auto;
	min-width: 24px;
	padding: 0 2px;
	font-size: 12px;
	border-radius: 1px
}

.list .img-gallary.activity {
	float: right
}

.pho-list-container {
	padding-bottom: 18px
}

.pho-list-container .article-item {
	position: relative;
	display: inline-block;
	overflow: hidden
}

.pho-list-container .article-item .topic-img {
	width: 100%;
	height: 100%;
	overflow: hidden;
	opacity: .7;
	-webkit-filter: blur(4px);
	filter: blur(10px)
}

.pho-list-container .article-item .share-count-tips {
	position: absolute;
	right: 0;
	bottom: 0;
	width: 70%;
	height: 20px;
	font-size: 14px;
	line-height: 20px;
	text-align: center;
	color: #fff;
	background: rgba(0,0,0,.6)
}

.pho-list-container .article-item .article-wrapper {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	padding: 15px 7px 18px;
	box-sizing: border-box;
	background-color: rgba(0,0,0,.62)
}

.pho-list-container .article-item .article-wrapper .article-word-wrapper {
	max-height: 74px;
	overflow: hidden
}

.pho-list-container .article-item .article-wrapper .article-title {
	display: -webkit-box;
	max-height: 38px;
	margin-bottom: 10px;
	overflow: hidden;
	font-size: 13px;
	font-weight: 700;
	line-height: 18px;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2
}

.pho-list-container .article-item .article-wrapper .article-title img {
	width: 1.2em;
	height: 1.2em
}

.pho-list-container .article-item .article-wrapper .article-content {
	display: -webkit-box;
	max-height: 48px;
	overflow: hidden;
	font-size: 11px;
	line-height: 15px;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 3
}

.pho-list-container .article-item .article-wrapper .article-content img {
	width: 1.2em;
	height: 1.2em
}

.pho-list-container .article-item .article-wrapper .article-content a {
	text-decoration: none;
	color: #0079ff
}

.pho-list-container .article-item .article-img {
	color: #fff
}

.pho-list-container .article-item .article-color .article-title {
	font-weight: 400;
	color: #000
}

.pho-list-container .article-item .article-color .article-content {
	color: gray
}

.pho-list-container .article-item .article-color-0 {
	background-color: #fcf4e4
}

.pho-list-container .article-item .article-color-1 {
	background-color: #e0f3ec
}

.pho-list-container .article-item .article-color-2 {
	background-color: #e4f0f7
}

.pho-list-container .article-item .article-color-3 {
	background-color: #f8e9ee
}

.pho-list-container .article-item .article-item-title {
	position: absolute;
	bottom: 0;
	left: 0;
	width: 100%;
	height: 50px;
	padding: 25px 5px 5px;
	-webkit-box-sizing: border-box;
	box-sizing: border-box;
	line-height: 25px;
	text-align: left
}

.pho-list-container .article-item .article-item-title .item-name {
	display: inline-block;
	width: 40%;
	overflow: hidden;
	font-size: 12px;
	white-space: nowrap;
	text-overflow: ellipsis
}

.pho-list-container .article-item .article-item-title .item-like {
	position: relative;
	display: block;
	float: right;
	width: 55%;
	margin-top: 1px;
	font-size: 12px;
	text-align: right;
	border: 0;
	background: transparent
}

.pho-list-container .article-item .article-item-title .item-like .like-icon {
	position: relative;
	top: 2px;
	display: inline-block;
	width: 16px;
	height: 16px;
	margin-right: 4px;
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/pho_like_grey.png?ec918767);
	background-repeat: no-repeat;
	background-size: 100%
}

.pho-list-container .article-item .article-item-title .item-like.liked .like-icon {
	-webkit-animation-name: like-animate;
	-webkit-animation-duration: 800ms;
	-webkit-animation-timing-function: ease;
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/pho_liked.png?ff329841)
}

.pho-list-container .article-item-color .article-item-title {
	color: #a6a6a6
}

.pho-list-container .article-item-color .article-item-title .like-icon {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/pho_like_grey.png?ec918767)
}

.pho-list-container .article-item-img .article-item-title {
	color: rgba(255,255,255,.7)
}

.pho-list-container .article-item-img .article-item-title .like-icon {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/pho_like.png?8798d525)
}

.list .detail-text-content .img-gallary {
	float: right;
	width: 80px;
	height: 80px;
	margin-top: 1px;
	margin-left: 12px;
	overflow: hidden;
	border-radius: 1px
}

.list .detail-text-content .img-gallary .audio-duration {
	position: absolute;
	bottom: 8px;
	display: block;
	width: 100%;
	font-size: 14px;
	text-align: center;
	text-indent: -12px;
	color: #fff
}

.list .detail-text-content .img-gallary .audio-duration .audio-gender-icon {
	position: absolute;
	top: 1px;
	right: 18px;
	width: 11px;
	height: 11px;
	background-size: 100% 100%
}

.list .detail-text-content .img-gallary .audio-duration.male .audio-gender-icon {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/ico-male.png?d4058c28)
}

.list .detail-text-content .img-gallary .audio-duration.female .audio-gender-icon {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/ico-female.png?d637984b)
}

.list .detail-text-content .img-gallary .audio-center-icon {
	position: absolute;
	top: 50%;
	left: 50%;
	width: 22px;
	height: 30px;
	margin: -15px 0 0 -11px;
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/audio-center-icon.png?f5767ae4);
	background-size: 100% 100%
}

.list .detail-text-content .img-gallary .img-mask {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: rgba(0,0,0,.4)
}

.list .detail-text-content .img-gallary .img-mask .img-type-icon {
	position: absolute;
	top: 50%;
	left: 50%;
	width: 31px;
	height: 31px;
	margin: -15px 0 0 -15px;
	background-size: 100% 100%
}

.list .detail-text-content .img-gallary .img-mask.video-img .img-type-icon {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/video-icon.png?4c063ef1)
}

.list .detail-text-content .img-gallary .img-mask.music-img .img-type-icon {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/music-icon.png?f1c0b4f7)
}

.act-info.img-gallary {
	height: 90px
}

.act-info.img-gallary h3.act {
	margin-bottom: 18px
}

.act-info.img-gallary h3 label {
	background: #f68b42
}

.act-info.img-gallary .act-price {
	padding-left: 0;
	margin-bottom: 12px
}

.act-info.img-gallary .status {
	margin-top: 0
}

.top-nav {
	position: fixed;
	top: 0;
	right: 0;
	left: 0;
	z-index: 1000;
	display: none;
	height: 44px;
	padding: 0 0 0 8px;
	margin: 0 auto;
	font-size: 14px;
	line-height: 44px;
	color: gray;
	background-color: #fff
}

.top-nav.section-1px:before {
	display: none
}

.top-nav.section-1px:after {
	bottom: 0;
	border-color: #e2e2e6
}

.top-nav.show {
	display: block
}

.top-nav .top-fans-num {
	padding-left: 3px
}

.top-nav .btn-focus {
	position: absolute;
	top: 6px;
	right: 12px;
	height: 29px;
	padding: 0 10px;
	font-size: 15px;
	line-height: 30px;
	text-align: center;
	color: #00a5e0
}

.top-nav .btn-focus.border-1px:after {
	border-color: #cacccd;
	border-radius: 4px
}

.honour,.my {
	display: inline-block;
	height: 12px;
	padding: 1px 1px 0 2px;
	margin-top: 0;
	font-size: 11px;
	line-height: 12px;
	-webkit-transform: scale(0.95);
	text-align: center;
	vertical-align: top;
	color: #f8bf43;
	background: 0 0
}

.honour.border-1px::after,.my.border-1px::after {
	border: 1px solid #f7a10c;
	border-radius: 2px
}

.honour.admin,.my.admin {
	color: #cd82a0
}

.honour.admin.border-1px::after,.my.admin.border-1px::after {
	border: 1px solid #cd82a0
}

.honour.vip,.my.vip {
	color: #f63231
}

.honour.vip.border-1px::after,.my.vip.border-1px::after {
	border: 1px solid #f63231
}

.honour.expert,.my.expert {
	color: #fe8961
}

.honour.expert.border-1px::after,.my.expert.border-1px::after {
	border: 1px solid #fe8961
}

.my {
	height: 15px;
	line-height: 15px;
	color: #68bb16
}

.my.border-1px::after {
	border: 1px solid #68bb16;
	border-radius: 2px
}

.all-tab-refresh-btn {
	position: fixed;
	right: 15px;
	bottom: 63px;
	z-index: 10;
	width: 36px;
	height: 36px;
	-webkit-transition: opacity .3s ease-out;
	background: url(../img/top-refresh.png?1417668550);
	background-size: 100% 100%
}

.all-tab-refresh-btn.hide-refresh-btn {
	pointer-events: none;
	opacity: 0
}

.top-refresh-loading {
	position: absolute;
	top: 0;
	left: 0;
	z-index: 99;
	display: none;
	width: 100%;
	height: 35px;
	background: rgba(34,34,34,.7)
}

.top-refresh-loading .spinner {
	padding-top: 6px;
	text-align: center
}

.best-top {
	display: none;
	height: 45px;
	margin-bottom: 1px;
	line-height: 45px;
	background-color: #f8f9fa
}

.best-top span {
	float: left;
	margin-left: 10px;
	font-size: 16px
}

.best-top:after {
	border-color: #e6e6e6
}

.best-top a {
	display: block
}

.best-top a:before {
	display: inline-block;
	margin-top: -3px;
	margin-right: 4px;
	content: &quot;
	&quot;;vertical-align: middle
}

.best-top.reverse a:before {
	-webkit-transform: scaleY(-1)
}

a:before {
	width: 18px;
	height: 18px;
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/inturn.png?a4777332);
	background-size: 100% 100%
}

#js_btnShowInturn {
	float: right;
	margin-right: 10px;
	font-size: 13px;
	color: gray
}

.festival-bar-top.novote .bar-single-detail:first-child:after {
	display: none
}

.festival-bar-top .bar-detail {
	float: left;
	margin-top: 10px;
	clear: left
}

.festival-bar-top .bar-detail .bar-single-detail {
	position: relative;
	display: inline-block;
	min-width: 40px;
	padding-right: 16px;
	font-size: 14px;
	color: rgba(255,255,255,.7)
}

.festival-bar-top .bar-detail .bar-single-detail:first-child:after {
	position: absolute;
	top: 4px;
	right: 8px;
	width: 1px;
	height: 58px;
	content: &quot;
	&quot;;-webkit-transform: scale(0.5,.5);
	-webkit-transform-origin: left top;
	background: rgba(255,255,255,.35)
}

.festival-bar-top .bar-detail .bar-single-detail .bar-focus-num,.festival-bar-top .bar-detail .bar-single-detail .bar-vote-num {
	padding-bottom: 7px
}

.festival-bar-top .focus-btn {
	position: absolute;
	right: 10px;
	bottom: -40px;
	width: 75px;
	height: 30px;
	font-size: 14px;
	color: #956000;
	border: 0;
	border-radius: 4px;
	background: #fbcf00;
	line-height: 30px
}

.festival-bar-top .focus-btn.has-focus {
	color: #fff;
	border: 1px solid #fff;
	background: transparent
}

.weixin-barindex .info .name-info .name {
	display: none
}

.weixin-barindex .info .sign {
	bottom: 20px
}

.weixin-barindex .info .sign .btn {
	top: -8px;
	border: 1px solid rgba(255,255,255,.5);
	background: transparent
}

.weixin-barindex .info .sign .info-grade .info-grade-score {
	position: relative;
	top: -1px;
	font-size: 12px
}

.weixin-barindex .info .sign .sign-btn.disable {
	line-height: 25px;
	border: 1px solid rgba(255,255,255,.5);
	background-color: transparent!important
}

.weixin-barindex .logo-rank {
	display: none
}

.pk-img {
	float: right;
	width: 80px;
	height: 80px;
	margin-left: 10px;
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/pk_list_bg.png?fd2191b4);
	background-repeat: no-repeat;
	background-size: 90px 90px
}

.pk-img .pk-logo-bg {
	width: 100%;
	height: 100%
}

.pk-info {
	height: 80px;
	padding-bottom: 16px
}

.pk-info .pk-text-container {
	height: 62px;
	max-height: 62px;
	overflow: hidden
}

.pk-info .pk-text-container h3 {
	position: relative;
	display: -webkit-box;
	overflow: hidden;
	font-size: 17px;
	font-weight: 500;
	line-height: 20px;
	text-overflow: ellipsis;
	word-break: break-all;
	color: #373737;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2
}

.pk-info .pk-text-container .pk-content {
	display: -webkit-box;
	margin-top: 5px;
	overflow: hidden;
	font-size: 14px;
	line-height: 18px;
	word-break: break-all;
	color: gray;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2
}

.pk-info .pk-bottom {
	position: relative;
	height: 16px;
	margin-top: 2px;
	overflow: hidden;
	font-size: 12px;
	line-height: 20px;
	color: #a6a6a6
}

.pk-info .pk-bottom .left {
	position: absolute;
	left: 0
}

.pk-info .pk-bottom .right {
	position: absolute;
	right: 0
}

.pk-info .pk-bottom .right:before {
	position: relative;
	top: 1px;
	right: 2px;
	display: inline-block;
	width: 12px;
	height: 12px;
	content: &quot;
	&quot;;background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/pk_list_logo_grey.png?add8080b);
	background-repeat: no-repeat;
	background-size: 12px 12px
}

.wechat-banner {
	position: relative;
	height: 0;
	-webkit-transition: height .5s ease-in;
	transition: height .5s ease-in
}

.wechat-banner #banner-img {
	width: 100%;
	height: 100%
}

.empty-container {
	display: none!important
}

#js_bar_main.best-wraper {
	bottom: 0
}

.charm-count-animation {
	-webkit-transform-origin: 50% 0;
	-webkit-animation-name: charm-count;
	-webkit-animation-duration: 2s;
	-webkit-animation-fill-mode: forwards
}

@-webkit-keyframes charm-count {
	50% {
		-webkit-transform: scale(1.5)
	}

	100% {
		-webkit-transform: scale(1)
	}
}

i.icon-star-heart,i.icon-star-heart-white {
	display: inline-block;
	width: 11px;
	height: 10px;
	margin-right: 3px;
	margin-left: 5px;
	font-size: 0;
	vertical-align: middle;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAUCAMAAAC+oj0CAAAAjVBMVEUApeAApeD///8ApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeAApeBEgsopAAAAL3RSTlOzAACWAXMPlZ+gBLGyYVuvQ0iOWJFfk6sNF6yMC5QMTUytEgIVFiIUpaQqISsDrmikDrcAAACTSURBVHhebczXFoIwAIPhxJa9URHEvff7P55FtAeh/1XOdxGQZSyCsMh80s+KMBBxSYJ3D5/ywzFvl3ciFi6+uZ5eK4wxTOHEoArnJp7hZWbHxEtMDapwZzjfnsHNkNcEpdVXSxLks+fWng1TRl2NJFsm0+SHSUpq5uPS6vXGLrMSjYqK/0zbARybmrXXtVZiZOwNjvUGcvqSMgMAAAAASUVORK5CYII=');
	background-size: 100% 100%
}

i.icon-star-heart-white {
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAUCAMAAAC+oj0CAAAAgVBMVEX///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+xZKUEAAAAK3RSTlPMALarEYOqGgJSygFoqE0Ex2WmxMufDqJtbibDVhQPFgOpVxgNMby7MMXGFV9gjAAAAJFJREFUeF5tzNcCgkAMRNEMC0svKkVQsdf//0ADKOKS+zQ5DyGgjFSQx34KpH6cBypqAUJjUV91dqthWQ3owvq5fqukBc1jXArKuJJ4Qy+ZtcRHWgvKuBOeP/eE7ZxPIISOqU7IjIPhjoeO4dVTrVl7BpLsi1kCjIzrbdD7A1OGqzpVLv4ZtibSNgxmLwrWkcXexQQFp1q3rxcAAAAASUVORK5CYII=')
}

.tip i.icon-star-heart,.tip i.icon-star-heart-white {
	border: 0;
	border-radius: 0
}

@-webkit-keyframes heart-beat {
	0% {
		visibility: visible
	}

	100% {
		visibility: visible;
		-webkit-transform: scale(2);
		opacity: 0
	}
}

@-webkit-keyframes charm-add-animation {
	0% {
		-webkit-transform: translate(0,0);
		opacity: 0
	}

	24% {
		opacity: 0
	}

	25% {
		-webkit-transform: translate(0,0);
		opacity: 1
	}

	100% {
		-webkit-transform: translate(0,-20px);
		transform: translate(0,0);
		opacity: 0
	}
}

@-webkit-keyframes charm-add-one-animation {
	0% {
		-webkit-transform: translate(0,0);
		opacity: 1
	}

	100% {
		-webkit-transform: translate(0,-20px);
		opacity: 0
	}
}

.ui-slide-container {
	position: relative;
	width: 240px;
	height: 240px;
	margin: 0 auto
}

.ui-slider-indicators {
	position: absolute;
	right: 0;
	bottom: 0;
	left: 0;
	z-index: 10;
	font-size: 0;
	white-space: nowrap;
	letter-spacing: -3px;
	word-wrap: normal
}

.ui-slider-indicators .dot {
	display: inline-block;
	width: 8px;
	height: 8px;
	margin-left: 5px;
	overflow: hidden;
	font-size: 12px;
	white-space: nowrap;
	text-indent: 100%;
	border-radius: 10px;
	background-color: #a6a6a6
}

.ui-slider-indicators .dot.current {
	background-color: #18b4ed
}

.teleplay,.variety {
}

.teleplay.header-cover.special.teleplay,.variety.header-cover.special.teleplay {
	height: 205px
}

.teleplay.header-cover.special.variety,.variety.header-cover.special.variety {
	height: 205px
}

.teleplay.header-cover.special .cover.mask-gray,.variety.header-cover.special .cover.mask-gray {
	height: 205px
}

.teleplay.header-cover.special .mask-white,.variety.header-cover.special .mask-white {
	display: none
}

.teleplay.header-cover.special .btn-area div.btn-see,.variety.header-cover.special .btn-area div.btn-see {
	color: #fff;
	background-color: transparent
}

.teleplay.header-cover.special .btn-area div.btn-movie-signed,.variety.header-cover.special .btn-area div.btn-movie-signed {
	position: relative;
	color: #fff;
	background-color: transparent;
	border-color: #fff;
	opacity: .5
}

.teleplay .special-info .btn-area div.btn-movie-signed,.variety .special-info .btn-area div.btn-movie-signed {
	background-color: transparent;
	height: 34px
}

.page-icon-left-wrap {
	position: fixed;
	bottom: 0;
	left: 0;
	z-index: 99;
	width: 100%
}

.page-icon-left {
	z-index: 1000;
	width: 100%;
	height: 44px;
	max-width: 800px;
	margin: 0 auto;
	line-height: 44px;
	text-align: center;
	vertical-align: middle;
	color: #00a5e0;
	background: -webkit-gradient(linear,0 0,0 100%,from(#f9f9f9),to(#e0e0e0))
}

.page-icon-left.section-1px::before {
	top: 0;
	border-color: #c7c7c7
}

.page-icon-left.section-1px::after {
	display: none
}

.page-icon-left .left {
	position: relative;
	z-index: 2;
	display: -webkit-box;
	height: 100%;
	-webkit-box-flex: 1;
	box-flex: 1
}

.page-icon-left .back {
	z-index: 3;
	width: 54px;
	height: 100%;
	background: -webkit-gradient(linear,0 0,0 100%,from(#faf5f3),to(#ebe9ec))
}

.page-icon-left .back.border-1px::after {
	border: 0;
	border-right: solid 1px #c7c7c7
}

.page-icon-left .back .arrow {
	position: absolute;
	top: 17px;
	left: 25px;
	width: 10px;
	height: 10px;
	content: &quot;
	&quot;;-webkit-transform: rotate(-45deg);
	border-top: 2px solid #828282;
	border-left: 2px solid #828282
}

.page-icon-left .more {
	position: relative;
	z-index: 2;
	float: left;
	width: 33%;
	height: 43px;
	margin-top: 1px;
	background: -webkit-gradient(linear,0 0,0 100%,from(#f9f9f9),to(#e0e0e0))
}

.page-icon-left .more:before {
	position: absolute;
	top: 0;
	left: -1px;
	width: 1px;
	height: 43px;
	content: &quot;
	\20&quot;;-webkit-transform: scaleX(0.5);
	background: #cacccd
}

.page-icon-left .more.border-1px::after {
	border: 0;
	border-left: solid 1px #c7c7c7
}

.page-icon-left .more:active {
	background: #ededed
}

.page-icon-left .more .dot {
	position: relative;
	top: 19px;
	left: 25px;
	width: 5px;
	height: 5px;
	border-radius: 5px;
	background: #828282
}

.page-icon-left .more .dot::before,.page-icon-left .more .dot::after {
	position: absolute;
	top: 0;
	display: block;
	width: 100%;
	height: 100%;
	font-size: 0;
	line-height: 0;
	content: &quot;
	&quot;;border-radius: 5px;
	background: #828282
}

.page-icon-left .more .dot::before {
	left: -9px
}

.page-icon-left .more .dot::after {
	left: 9px
}

.page-icon-left .bottom-btn-list {
	float: left;
	width: 66%
}

.page-icon-left .bottom-btn-list:after {
	width: 1px;
	height: 44px;
	content: &quot;
	\20&quot;;-webkit-transform: scaleX(0.5);
	background: #cacccd
}

.page-icon-left .bottom-btn-list&gt;a {
	display: -webkit-box;
	text-align: center;
	-webkit-box-flex: 1
}

.page-icon-left .bottom-btn-list .item {
	position: relative
}

.page-icon-left .bottom-btn-list&gt;.item:last-child:before {
	position: absolute;
	top: 0;
	left: -1px;
	width: 1px;
	height: 44px;
	content: &quot;
	\20&quot;;-webkit-transform: scaleX(0.5);
	background: #cacccd
}

.top-list-wrap:empty {
	display: none
}

.hof-wrapper {
	position: relative;
	width: 100%;
	min-height: 500px;
	padding: 40px 0 20px;
	box-sizing: border-box
}

.bg {
	background-color: #bedaf2
}

.hof-container {
	position: relative;
	margin: 40px 10px 10px;
	border-radius: 10px;
	background-color: #f4f8fb
}

.hof-container.active {
	background-color: #c5c5c5
}

.hof-container .cap {
	position: absolute;
	top: -33px;
	left: -4px;
	z-index: 2;
	width: 100px;
	height: 57px
}

.hof-container .top-bg {
	height: 25px;
	padding-right: 10px;
	font-size: 12px;
	line-height: 25px;
	text-align: right;
	color: #fff;
	border-top-left-radius: 10px;
	border-top-right-radius: 10px
}

.hof-container .avatar-container {
	position: relative;
	display: -webkit-box;
	padding: 18px 4px
}

.hof-container .avatar-container .user {
	-webkit-box-flex: 1;
	width: 0
}

.hof-container .avatar-container .user .placeholder {
	visibility: hidden
}

.hof-container .avatar-container .user .name {
	margin-top: 7px;
	overflow: hidden;
	font-size: 13px;
	text-align: center;
	white-space: nowrap;
	text-overflow: ellipsis;
	word-break: break-all;
	color: #000
}

.hof-container .avatar-container .user .info {
	padding-top: 5px;
	font-size: 10px;
	text-align: center;
	color: rgba(0,0,0,.5)
}

.hof-container .avatar-container .user .info i {
	display: inline-block;
	width: 10px;
	height: 10px;
	margin-right: 2px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAeCAYAAAA/xX6fAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3FpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo3M2MxMzJmNC1hMDMyLWNhNDItYWYzZC1jNzBkNzA4N2U2MWMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MzkyNTExQzQ4QjY3MTFFNDgzRjFEOUVFQTZBMDRFMjkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MzkyNTExQzM4QjY3MTFFNDgzRjFEOUVFQTZBMDRFMjkiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjJlMzczNzU0LTEyN2EtYTg0Ni05MDBlLTYxOTg1ZDMwODU4NCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpkZTJkNjRjZi0xZjNmLWJlNDktOTU5ZS0zYzExNGU3ZTY0NWUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7wdj6LAAAB3UlEQVR42tyWu0vDUBTGm/pCUXw/NkUcFEQUX4g4iEUXX6uLi+Lk4Kh0EBcHBfEvcHERdXASlGy6CIX6AHFxELStiiK+0GqJ34UvEOpN28hNBwM/Tnpvmu+ek3u+RDMMw5POIzPRpK7rdlPFoBacgi+7i3w+368x7x8WOQNuQICCNU7+7FSwDqyAMFgD9WDLyX2cChYADWyDCbAP2sCwW4LfjJ+Mq4wjbgmaxyvjAWODW4LljPcW4Sc3n2EP46VlLANcuSU4CqLgmL+buJF0lYK5bPIl0Ax2wTPnJhmXwaMFUeYPGEcQDFpvppnWhglhCxugNIH4C+gGZyAfXIPCJAsWTtQJ1wnGZzifQEyUcR10UMzcMEXsSxmi1JsgC4zJSppjlhCr0QQ4r+ZYBIyDC4etc8jzbJlghLFS4cvB7M8TmeAdY4VCwVbGgEwwpDjDPNAC3sB5OjLs4oYJYj/EZIJhxRn2Mh7ZNf6t4gz74gzeVlBFhiWgHRiW1rAtqYoMh2jq4vk9SAUxIRr1XVGGprPsJTPvkJkhvLUMYY7jVWA2RaOfBgMgRjv0SM2bImJF/Yo2zSKq5k+W4ULcy3RHOD1opLlHU/jmEU0+BfyyC7R0f3l7PWk+/r/gjwADAN0nc+OVFAFkAAAAAElFTkSuQmCC');
	background-size: 10px 10px
}

.hof-container .avatar-container .user .avatar {
	position: relative;
	width: 50px;
	height: 50px;
	margin: 0 auto
}

.hof-container .avatar-container .user .avatar img {
	position: relative;
	width: 50px;
	height: 50px;
	border-radius: 25px
}

.hof-container .avatar-container .user .avatar:after {
	position: absolute;
	top: 0;
	left: 0;
	width: 200%;
	height: 200%;
	box-sizing: border-box;
	content: &quot;
	&quot;;-webkit-transform: scale(0.5,.5);
	-webkit-transform-origin: left top;
	border: 1px solid rgba(0,0,0,.17);
	border-radius: 50px
}

#admin_container {
	margin-top: 0;
	box-shadow: 0 0 1px 1px #aaabd0
}

#admin_container .cap {
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/qz_icon.png?ad65233f) no-repeat center center;
	background-size: 100px 57px
}

#admin_container .top-bg {
	background-color: #b4bbda
}

#expert_container {
	box-shadow: 0 0 1px 1px #d6aab6
}

#expert_container .cap {
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/daren_icon.png?82c4277c) no-repeat center center;
	background-size: 100px 57px
}

#expert_container .top-bg {
	background-color: #debbc7
}

#fans_container {
	margin-bottom: 0;
	box-shadow: 0 0 1px 1px #e0c16a
}

#fans_container .cap {
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/tgf_icon.png?10239259) no-repeat center center;
	background-size: 100px 57px
}

#fans_container .top-bg {
	background-color: #e8c684
}

.no-user {
	padding: 36px 0;
	text-align: center
}

.no-user .title {
	font-size: 14px
}

.no-user .desc {
	padding: 15px 0 7px;
	font-size: 11px;
	color: rgba(0,0,0,.5)
}

.apply-admin {
	width: 50px;
	height: 50px;
	margin: 0 auto;
	line-height: 50px;
	text-align: center;
	color: #18b4ed;
	border: 1px dotted #18b4ed;
	border-radius: 50px;
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/apply.png?5b50f983) no-repeat center center;
	background-size: 50px;
	text-align: center
}

.apply-btn {
	background: 0 0
}

.hof-load-tip {
	position: absolute;
	top: 100px;
	left: 50%;
	width: 140px;
	margin-top: 100px;
	margin-left: -70px;
	font-size: 14px;
	text-align: center;
	color: #a6a6a6
}

.list-ad-mark,.list-ad-mark-top {
	width: 27px;
	padding: 1px 2px;
	font-size: 10px;
	line-height: 15px;
	text-align: center;
	color: #fff;
	border-radius: 2px;
	background: #6ab147
}

.list-ad-mark {
	position: absolute;
	left: 10px;
	bottom: 15px
}

.ad-ribbon {
	position: absolute;
	top: 10px;
	right: -19px;
	display: inline-block;
	width: 50px;
	padding: 0 10px;
	font-size: 12px;
	line-height: 16px;
	-webkit-transform: rotate(45deg);
	transform: rotate(45deg);
	text-align: center;
	color: #fff;
	background: #6ab147
}

.gdt-ad-wrapper,#js_detail_list li.gdt-ad .gdt-ad-wrapper {
	position: relative;
	overflow: hidden
}

.gdt-ad-wrapper .ad-banner,#js_detail_list li.gdt-ad .gdt-ad-wrapper .ad-banner {
	height: 40px;
	margin-bottom: 12px
}

#js_bar_list li.gdt-ad {
	display: none
}

#js_bar_list li.gdt-ad .text-container {
	height: 82px;
	max-height: 82px
}

#js_bar_list li.gdt-ad .ad-img {
	width: 90px;
	height: 90px
}

#js_detail_list li.gdt-ad {
	display: none;
	overflow: hidden
}

#js_detail_list li.gdt-ad .gdt-ad-wrapper .top_right {
	padding-top: 10px;
	overflow: hidden;
	font-size: 12px;
	line-height: 16px;
	vertical-align: baseline!important;
	white-space: nowrap;
	text-overflow: ellipsis;
	color: #324d82!important
}

#js_detail_list li.gdt-ad .gdt-ad-wrapper .ad-title {
	max-width: 210px
}

#recommend-list li.gdt-ad {
	display: none;
	overflow: hidden;
	position: relative
}

#recommend-list li.gdt-ad .groupbody {
	width: 100%;
	margin-right: 2px;
	overflow: hidden;
	font-size: 14px;
	vertical-align: text-bottom;
	white-space: nowrap;
	text-overflow: ellipsis;
	word-break: break-all;
	color: #a6a6a6
}

#recommend-list li.gdt-ad .ad-banner {
	width: 325px;
	height: 50px;
	margin: 15px
}

.gdt-ad-banner {
	margin: 20px auto;
	width: 100%;
	position: relative
}

.gdt-ad-banner .ad-title {
	margin: 0 10px;
	color: #a6a6a6;
	font-size: 14px;
	width: 95%;
	text-align: center;
	position: relative;
	z-index: 1
}

.gdt-ad-banner .ad-title .ad-title-txt {
	margin: 0 auto;
	width: 100px;
	background-color: #F8F8F8;
	z-index: 10;
	position: relative
}

.gdt-ad-banner .ad-title .ad-title-dashed {
	border-top: 1px dashed #a6a6a6;
	position: relative;
	top: -7px
}

.gdt-ad-banner .ad-mark {
	height: 18px;
	line-height: 18px;
	font-size: 14px;
	color: #fff;
	font-weight: 700;
	background-color: #2b61c1;
	padding: 1px 5px 0;
	position: absolute;
	right: 10px;
	top: 26px
}

.gdt-ad-banner .ad-banner-img-wrapper {
	margin: 30px 10px 0;
	height: 166px
}

.gdt-ad-banner .ad-banner-img-wrapper .ad-banner-img {
	width: 100%;
	-webkit-tap-highlight-color: rgba(0,0,0,.35);
	tap-highlight-color: rgba(0,0,0,.35)
}

.ad-mark-new {
	position: relative;
	top: -3px;
	display: inline-block;
	height: 15px;
	min-width: 13px;
	padding: 0 1px;
	margin-right: 5px;
	font-size: 11px;
	line-height: 15px;
	text-align: center;
	color: #fff;
	border-radius: 2px;
	background: #6ab147
}

.list-ad-mark-top {
	float: right
}

.rank_tab_container {
	-webkit-overflow-scrolling: touch
}

.rank_tab_container .container_inner {
	overflow: hidden;
	background: #f8f9fa
}

.rank_tab_container .section-1px::after,.rank_tab_container .section-1px::before {
	left: 15px;
	border-color: #ddd
}

.rank_tab_container .rank_qz_info.section-1px::after,.rank_tab_container .rank_info.section-1px::before {
	left: 0
}

.rank_tab_container .weixin_bar_rank_wrap.section-1px::before {
	left: 0
}

.rank_tab_container .rank_header {
	min-height: 35px;
	margin-top: 20px;
	box-sizing: border-box;
	background: #fff
}

.rank_tab_container .rank_header.section-1px::before {
	display: none
}

.rank_tab_container .rank_header .bar_rank_avatar {
	float: left;
	width: 45px;
	height: 45px;
	border-radius: 99px
}

.rank_tab_container .rank_header .bar_rank_wrap {
	padding-top: 5px;
	margin-left: 58px
}

.rank_tab_container .rank_header .bar_rank_wrap .bar_rank_name {
	margin: 0 0 5px;
	font-size: 16px
}

.rank_tab_container .rank_header .bar_rank_wrap .bar_rank_info {
	font-size: 13px;
	line-height: 18px;
	color: #a6a6a6
}

.rank_tab_container .rank_header .bar-rank-detail {
	height: 50px;
	padding-top: 20px
}

.rank_tab_container .rank_header .bar-rank-detail .dev-wrapper {
	float: left;
	width: 50%;
	box-sizing: border-box;
	text-align: center
}

.rank_tab_container .rank_header .bar-rank-detail .dev-wrapper p {
	font-size: 13px;
	line-height: 1.5em;
	color: #a6a6a6
}

.rank_tab_container .rank_header .bar-rank-detail .dev-wrapper {
	position: relative;
	z-index: 1
}

.rank_tab_container .rank_header .bar-rank-detail .dev-wrapper .bar-news {
	position: absolute;
	top: 0;
	left: 0;
	z-index: 2;
	display: block;
	padding: 5px 18px 5px 10px;
	font-size: 12px;
	color: #fff;
	border-radius: 0 0 20px/30px 0;
	background-color: red
}

.rank_tab_container .rank_header .bar-rank-detail .dev-wrapper+.dev-wrapper {
	border-left: 1px solid #ccc
}

.rank_tab_container .rank_header .bar-rank-detail .dev-wrapper .info-words {
	display: inline-block;
	min-width: 50px;
	padding: 5px;
	font-size: 20px;
	color: #18b4ed
}

.rank_tab_container .rank_header .bar-rank-detail .dev-wrapper .info-words.non-mqq {
	color: #000!important
}

.rank_tab_container .rank_header .bar-rank-detail .dev-wrapper .tap {
	padding: 5px;
	border-radius: 5px;
	background-color: #ebeef0
}

.rank_tab_container .rank_header .weixin_bar_rank_wrap .bar_qr_code_jump:after {
	display: none!important
}

.rank_tab_container .rank_header .weixin_bar_rank_wrap .weixin_bar_rank_item {
	position: relative;
	padding: 14px 15px;
	line-height: 21px
}

.rank_tab_container .rank_header .weixin_bar_rank_wrap .weixin_bar_rank_item .rank_rignt_icon {
	position: relative;
	top: 4px;
	float: right;
	width: 9px;
	height: 13px;
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/arrow-left.png?b768ec1b);
	background-size: 100% 100%
}

.rank_tab_container .rank_header .weixin_bar_rank_wrap .weixin_bar_rank_item .bar_qr_code_icon {
	position: relative;
	top: -4px;
	width: 25px;
	height: 25px;
	float: right;
	margin-right: 10px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyBAMAAADsEZWCAAAAG1BMVEWmpqb///+mpqampqampqampqampqampqampqaTqulgAAAACHRSTlMAAAECAwQFBofThCYAAAFPSURBVHhejdMxb8IwEIbhD9NKjJgpo3MsHhFQKSMqIDF2AImRnwJRae9n1+eLSKw4Ud/10fkUY0Bz0PJy3O6PUwJgMLG2tCEU3PQ0DMaMmx6t1BiSe1+2l/NhbV4ysVIQzzuEjJzmEjnx7bzeXqbtTPmS3Yn5513FBSnCEpGKVxXz71tWTkHiaZSInvY0Kkhmvq4qJhV4voWZb4gA6czusFmuwbVI+j0rSDKWypUJMEC8g0aoVGm6GxVN9nQEifhBsXMApD90bGI1Fa0v5JYO2HSFui+kbqWQPRkpG7EUwtueBFiyUhFkbsKWj73riUy6eBP3jABVRmSLA0CIGSqjLMa+lJzZfKLJdaW9Ueie3osflXZPoYJYX3xGpMWgWDltJkvGhVUWWWlfyP8l3eOHxDkQ8gIgJ54JRqTuSpHc6KhIj3Hx+l+IOUjG6Z7S2j8JfSwNcpxHJwAAAABJRU5ErkJggg==');
	background-size: 100% 100%
}

.rank_tab_container .rank_header .weixin_bar_rank_wrap .weixin_bar_rank_item .sub_rignt_icon {
	position: relative;
	top: 2px;
	float: right;
	width: 9px;
	height: 13px;
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/arrow-left.png?b768ec1b);
	background-size: 100% 100%
}

.rank_tab_container .rank_header .weixin_bar_rank_wrap .weixin_bar_rank_item.relative-group-item.section-1px:after {
	display: none
}

.rank_tab_container .rank_header .weixin_bar_rank_wrap .weixin_bar_rank_item .relative-group-num {
	float: right;
	margin-right: 10px;
	color: gray
}

.rank_tab_container .rank_header .weixin_bar_rank_wrap .weixin_bar_rank_item:after {
	z-index: 1;
	display: block;
	clear: both;
	content: &quot;
	&quot;
}

.rank_tab_container .rank_header .weixin_bar_rank_wrap .weixin_bar_rank_item .weixin_bar_info_text {
	padding-right: 10px
}

.rank_tab_container .rank_header .weixin_bar_rank_wrap .weixin_bar_rank_item .weixin_bar_info_content {
	float: right;
	max-width: 65%;
	line-height: 21px;
	text-align: right;
	color: gray
}

.rank_tab_container .rank_header .weixin_bar_rank_wrap .weixin_bar_rank_item .weixin_bar_info_content.weixin_bar_intro {
	display: -webkit-box;
	overflow: hidden;
	word-break: normal;
	word-wrap: break-word
}

.rank_tab_container .rank_header .star_bar_rank_wrap {
	margin-top: -20px
}

.rank_tab_container .rank_header .weixin_bar_rank_wrap.star_bar_rank_wrap .bar_qr_code_jump:after {
	display: block!important
}

.rank_tab_container .subscribe_info {
	height: 14px;
	padding: 15px;
	margin: 10px 0 60px;
	line-height: 14px;
	background: #fff
}

.rank_tab_container .subscribe_info.section-1px:after,.rank_tab_container .subscribe_info.section-1px:before {
	left: 0
}

.rank_tab_container .subscribe_info .sub_bar_title {
	float: left;
	font-size: 15px
}

.rank_tab_container .subscribe_info .sub_info {
	float: right;
	margin-right: 10px;
	font-size: 13px;
	color: #a6a6a6
}

.rank_tab_container .subscribe_info .sub_rignt_icon {
	position: relative;
	top: 1px;
	float: right;
	width: 9px;
	height: 13px;
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/arrow-left.png?b768ec1b);
	background-size: 100% 100%
}

.rank_tab_container .subscribe_info .sub_new_icon {
	display: inline-block;
	width: 33px;
	height: 16px;
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/sub_new_icon.png?928beea2);
	background-size: 100% 100%
}

.rank_tab_container .rank_qz_info_size {
	height: 105px;
	padding: 15px
}

.rank_tab_container .rank_qz_info_hidden::before {
	display: none
}

.rank_tab_container .rank_qz_info {
	margin-bottom: 15px;
	box-sizing: border-box;
	background: #fff
}

.rank_tab_container .rank_qz_info h4 {
	float: left;
	font-size: 16px
}

.rank_tab_container .rank_qz_info .qz_count {
	float: right;
	margin-right: 10px;
	font-size: 16px;
	color: gray
}

.rank_tab_container .rank_qz_info .rank_qz_list {
	width: 100%;
	padding-top: 20px;
	clear: left
}

.rank_tab_container .rank_qz_info .rank_qz_list:after {
	z-index: 1;
	display: block;
	clear: both;
	content: &quot;
	&quot;
}

.rank_tab_container .rank_qz_info .rank_qz_list&gt;li {
	float: left;
	width: 14%;
	height: 40px;
	max-width: 40px;
	max-height: 40px;
	margin-right: 2.8%
}

.rank_tab_container .rank_qz_info .rank_qz_list&gt;li.rank_require_btn_container {
	position: relative;
	margin-right: 0
}

.rank_tab_container .rank_qz_info .rank_qz_list&gt;li.rank_require_btn_container:after {
	position: absolute;
	top: 0;
	left: 0;
	width: 200%;
	height: 200%;
	box-sizing: border-box;
	content: &quot;
	&quot;;-webkit-transform: scale(0.5,.5);
	-webkit-transform-origin: left top;
	border: 1px dashed #a6a6a6;
	border-radius: 999px
}

.rank_tab_container .rank_qz_info .rank_qz_list&gt;li&gt;img,.rank_tab_container .rank_qz_info .rank_qz_list .rank_require_btn {
	display: block;
	width: 100%;
	height: 100%;
	color: #18b4ed;
	border-width: 0;
	border-radius: 999px
}

.rank_tab_container .rank_qz_info .rank_qz_list #requireQzBtn {
	color: #a6a6a6
}

.rank_tab_container .rank_qz_info .rank_qz_list .rank_require_btn {
	height: 40px;
	font-size: 13px;
	background: #fff
}

.rank_tab_container .rank_qz_info .sub_rignt_icon {
	position: relative;
	top: 1px;
	float: right;
	width: 9px;
	height: 13px;
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/arrow-left.png?b768ec1b);
	background-size: 100% 100%
}

.rank_tab_container .rank_info {
	padding: 15px 0 0;
	box-sizing: border-box;
	background: #fff
}

.rank_tab_container .rank_info h4 {
	padding: 0 15px;
	font-size: 16px
}

.rank_tab_container .rank_info .pub_rank_title {
	font-size: 15px
}

.rank_tab_container .rank_info .pub_rank_info {
	float: right;
	font-size: 15px;
	color: gray
}

.rank_tab_container .rank_info .pub_rank_info.current {
	color: #00a5e0
}

.rank_tab_container .rank_info .pub_rank_info.sep {
	padding: 0 5px
}

.rank_tab_container .rank_info .rank-info {
	float: right;
	margin-left: 10px;
	color: gray
}

.rank_tab_container .rank_info .rank_info_container {
	text-align: center
}

.rank_tab_container .rank_info .rank_info_container .left_info,.rank_tab_container .rank_info .rank_info_container .right_info {
	position: relative;
	display: inline-block;
	width: 48%;
	height: 60px;
	padding: 16px 0 0;
	box-sizing: border-box
}

.rank_tab_container .rank_info .rank_info_container .left_info .info_text,.rank_tab_container .rank_info .rank_info_container .right_info .info_text {
	font-size: 12px;
	color: #a6a6a6
}

.rank_tab_container .rank_info .rank_info_container .left_info .today_topic_sum,.rank_tab_container .rank_info .rank_info_container .left_info .rank_num,.rank_tab_container .rank_info .rank_info_container .right_info .today_topic_sum,.rank_tab_container .rank_info .rank_info_container .right_info .rank_num {
	margin-bottom: 8px;
	font-size: 25px
}

.rank_tab_container .rank_info .rank_info_container .left_info .rank_num,.rank_tab_container .rank_info .rank_info_container .right_info .rank_num {
	color: #f28c48
}

.rank_tab_container .rank_info .rank_info_container .left_info:after {
	position: absolute;
	top: 19px;
	right: -2px;
	width: 1px;
	height: 80px;
	content: &quot;
	&quot;;-webkit-transform: scale(0.5,.5);
	-webkit-transform-origin: left top;
	background: #ddd
}

.rank_tab_container .rank_list {
	padding: 15px 0;
	box-sizing: border-box;
	background: #fff
}

.rank_tab_container .rank_list.star-category {
	padding-top: 0
}

.rank_tab_container .rank_list.star-category .rank_bar_list&gt;li .rank_bar_face {
	border-radius: 50%
}

.rank_tab_container .rank_list.section-1px::before {
	display: none
}

.rank_tab_container .rank_list.section-1px::after {
	left: 0
}

.rank_tab_container .rank_list .rank_bar_list&gt;li {
	height: 40px;
	padding: 10px 0;
	clear: left
}

.rank_tab_container .rank_list .rank_bar_list&gt;li.active {
	background-color: #d4d4d4
}

.rank_tab_container .rank_list .rank_bar_list&gt;li.bottom_bar_item {
	position: relative;
	margin-top: 10px
}

.rank_tab_container .rank_list .rank_bar_list&gt;li.bottom_bar_item:after {
	position: absolute;
	top: -15px;
	left: 27px;
	width: 2px;
	height: 22px;
	content: &quot;
	&quot;;background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/vertical-dashed.png?cd4edfe0);
	background-size: 100% 100%
}

.rank_tab_container .rank_list .rank_bar_list&gt;li.current_bar_item .rank_grade,.rank_tab_container .rank_list .rank_bar_list&gt;li.current_bar_item .rank_bar_name {
	color: #f28c48
}

.rank_tab_container .rank_list .rank_bar_list&gt;li .rank_icon,.rank_tab_container .rank_list .rank_bar_list&gt;li .rank_grade {
	display: block;
	float: left;
	width: 55px;
	height: 30px;
	margin-top: 7px
}

.rank_tab_container .rank_list .rank_bar_list&gt;li .rank_icon&gt;i {
	display: block;
	width: 22px;
	height: 22px;
	margin: 0 auto;
	background-size: 100% 100%
}

.rank_tab_container .rank_list .rank_bar_list&gt;li .rank_icon.ri_1&gt;i {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/bar-rank1.png?fadd83f0)
}

.rank_tab_container .rank_list .rank_bar_list&gt;li .rank_icon.ri_2&gt;i {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/bar-rank2.png?c19e96e2)
}

.rank_tab_container .rank_list .rank_bar_list&gt;li .rank_icon.ri_3&gt;i {
	background-image: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/bar-rank3.png?ccff9603)
}

.rank_tab_container .rank_list .rank_bar_list&gt;li .rank_grade {
	font-size: 25px;
	text-align: center
}

.rank_tab_container .rank_list .rank_bar_list&gt;li .rank_bar_face {
	float: left;
	width: 40px;
	height: 40px;
	border-radius: 4px
}

.rank_tab_container .rank_list .rank_bar_list&gt;li .rank_bar_info_wrap {
	margin-left: 105px
}

.rank_tab_container .rank_list .rank_bar_list&gt;li .rank_bar_info_wrap .rank_bar_name {
	padding-top: 2px;
	font-size: 16px
}

.rank_tab_container .rank_list .rank_bar_list&gt;li .rank_bar_info_wrap .rank_bar_today_topic {
	margin-top: 6px;
	font-size: 13px;
	color: #a6a6a6
}

.rank_tab_container #js_rank_loading {
	position: fixed;
	top: 0;
	display: none;
	width: 100%;
	height: 100%;
	padding-top: 20px;
	padding-bottom: 20px;
	margin-top: 2px;
	overflow: visible;
	opacity: 1;
	color: #ccc;
	background: #f8f8f8
}

.rank_tab_container .show_more_rank_bar {
	display: none;
	padding: 15px 0;
	font-size: 16px;
	text-align: center;
	text-indent: 20px;
	color: #18b4ed;
	background: #fff
}

.rank_tab_container .show_more_rank_bar.section-1px:before,.rank_tab_container .show_more_rank_bar.section-1px:after {
	left: 0
}

.sub_mask_top,.sub_mask_bottom {
	position: absolute;
	left: 0;
	width: 100%;
	background: rgba(0,0,0,.75)
}

.sub_mask_top {
	top: 0;
	height: 75px
}

.sub_mask_bottom {
	top: 120px;
	bottom: 0;
	padding-top: 15px
}

.sub_mask_bottom .mask_icon {
	width: 237px;
	height: 38px;
	margin: 0 auto;
	background: url(http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/sub-mask-icon.png?49982558);
	background-size: 100% 100%
}

.subcribe_focus_btn {
	position: fixed;
	bottom: 0;
	left: 0;
	z-index: 99px;
	display: none;
	width: 100%;
	height: 50px;
	font-size: 18px;
	line-height: 50px;
	text-align: center;
	color: #00a5e0;
	background: #fff
}

.unsubscribe_btn_wrapper {
	display: none;
	margin: 35px 15px;
	background-color: #fff
}

.unsubscribe_btn_wrapper .unsubscribe_btn {
	display: block;
	height: 44px;
	box-sizing: border-box;
	font-size: 15px;
	line-height: 44px;
	text-align: center;
	color: #fff;
	border-radius: 5px;
	background: -webkit-gradient(linear,0 0,0 100%,from(#fc6156),to(#f75549))
}

.unsubscribe_btn_wrapper .unsubscribe_btn:active {
	color: rgba(255,255,255,.5);
	background: #e2574d
}

#js_bar_main .rank_tab_container {
	margin-bottom: 15px
}

.star-header-wrap {
	height: 94px;
	position: relative
}

.star-header-wrap .star-cover {
	position: absolute;
	height: 94px;
	width: 100%;
	background-size: 100%;
	background-position: center;
	background-repeat: no-repeat
}

.star-header-wrap .star-gray-cover {
	position: absolute;
	height: 94px;
	width: 100%;
	background-color: rgba(0,0,0,.6)
}

.star-header-wrap .star-info-wrap {
	position: relative;
	color: #fff
}

.star-header-wrap .star-info-wrap .star-pic {
	height: 60px;
	width: 60px;
	border-radius: 5px;
	border: 2px solid #fff;
	position: absolute;
	top: 15px;
	left: 13px
}

.star-header-wrap .star-info-wrap .star-info {
	margin-left: 86px;
	margin-top: 17px;
	float: left
}

.star-header-wrap .star-info-wrap .star-info .star-name {
	font-size: 20px
}

.star-header-wrap .star-info-wrap .star-info .star-nums,.star-header-wrap .star-info-wrap .star-info .star-category {
	font-size: 12px;
	margin-top: 6px
}

.cur-rank-info {
	position: relative
}

.cur-rank-info li {
	background-color: #fff;
	padding: 10px 0;
	clear: left
}

.cur-rank-info li.sending-heart .charm-add-animation {
	-webkit-animation-name: charm-add-animation;
	-webkit-animation-duration: 1s;
	-webkit-animation-delay: 0s;
	-webkit-animation-fill-mode: none
}

.cur-rank-info li.sending-heart .charm-add-one-animation {
	-webkit-animation-name: charm-add-one-animation;
	-webkit-animation-duration: 1s;
	-webkit-animation-delay: 0s;
	-webkit-animation-fill-mode: none
}

.cur-rank-info li.sending-heart .icon-heart-animation {
	display: block
}

.cur-rank-info li .rank-grade {
	display: block;
	float: left;
	width: 55px;
	height: 30px;
	margin-top: 7px;
	font-size: 25px;
	text-align: center;
	color: #f28c48
}

.cur-rank-info li .rank-bar-info-wrap {
	margin-left: 55px
}

.cur-rank-info li .rank-cur-charm {
	color: #f28c48;
	font-size: 16px
}

.cur-rank-info li .rank-target {
	font-size: 13px;
	color: #777;
	margin-top: 6px;
	width: 70%;
	line-height: 18px
}

.cur-rank-info li .btn-send-heart {
	position: absolute;
	top: 14px;
	right: 15px;
	min-width: 62px;
	padding: 1px 8px 0;
	display: inline-block;
	height: 28px;
	box-sizing: border-box;
	font-size: 13px;
	line-height: 28px;
	text-align: center;
	color: #fff;
	border-radius: 3px;
	background-color: #00a5e0
}

.cur-rank-info li .btn-send-heart:active {
	background-color: #1fbaf3
}

.icon-heart-animation {
	content: ' ';
	display: none;
	position: absolute;
	top: 10px;
	left: 25px;
	width: 11px;
	height: 10px;
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAUCAMAAAC+oj0CAAAAgVBMVEX///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+xZKUEAAAAK3RSTlPMALarEYOqGgJSygFoqE0Ex2WmxMufDqJtbibDVhQPFgOpVxgNMby7MMXGFV9gjAAAAJFJREFUeF5tzNcCgkAMRNEMC0svKkVQsdf//0ADKOKS+zQ5DyGgjFSQx34KpH6cBypqAUJjUV91dqthWQ3owvq5fqukBc1jXArKuJJ4Qy+ZtcRHWgvKuBOeP/eE7ZxPIISOqU7IjIPhjoeO4dVTrVl7BpLsi1kCjIzrbdD7A1OGqzpVLv4ZtibSNgxmLwrWkcXexQQFp1q3rxcAAAAASUVORK5CYII=');
	background-size: 100% 100%;
	-webkit-animation-name: heart-beat;
	-webkit-animation-duration: 1s;
	-webkit-animation-fill-mode: forwards
}

.charm-add-animation {
	display: inline-block;
	color: #00a5e0;
	opacity: 0
}

@-webkit-keyframes charm-add-animation {
	0% {
		-webkit-transform: translate(0,0);
		opacity: 0
	}

	24% {
		opacity: 0
	}

	25% {
		-webkit-transform: translate(0,0);
		opacity: 1
	}

	100% {
		-webkit-transform: translate(0,-20px);
		transform: translate(0,0);
		opacity: 0
	}
}

@-webkit-keyframes charm-add-one-animation {
	0% {
		-webkit-transform: translate(0,0);
		opacity: 1
	}

	100% {
		-webkit-transform: translate(0,-20px);
		opacity: 0
	}
}

@-webkit-keyframes heart-beat {
	0% {
		visibility: visible
	}

	100% {
		visibility: visible;
		-webkit-transform: scale(2);
		opacity: 0
	}
}

html,body,ul,ol,img,p,input,button {
	padding: 0;
	margin: 0
}

html,body {
	font-family: -apple-system-font,&quot;
	Helvetica Neue&quot;,Helvetica,STHeiTi,sans-serif;-webkit-user-select: none;
	-webkit-overflow-scrolling: touch;
	-webkit-user-drag: none
}

a,a:active,a:focus,button,button:active,input,input:focus,select,select:focus,textarea,textarea:focus {
	outline: 0;
	-webkit-tap-highlight-color: rgba(255,255,255,0)
}

a {
	text-decoration: none;
	color: #0079ff
}

ol,ul {
	list-style: none
}

.ui-app {
	cursor: none
}

.ui-app * {
	-webkit-box-sizing: border-box;
	box-sizing: border-box;
	-webkit-tap-highlight-color: transparent;
	-webkit-tap-highlight-color: transparent;
	-webkit-touch-callout: none
}

.ui-top-bar,.ui-bottom-bar {
	position: fixed;
	right: 0;
	left: 0;
	z-index: 3;
	width: 100%;
	height: 43px;
	line-height: 43px;
	text-align: center
}

.ui-flexbox,.ui-bottom-bar {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex
}

.ui-top-bar {
	top: 0;
	background-position: bottom left
}

.ui-bottom-bar {
	bottom: 0;
	background-position: top left
}

.ui-bottom-bar~.ui-page&gt;.ui-page-content {
	border-bottom: 48px solid transparent
}

.ui-top-bar~.ui-page&gt;.ui-page-content {
	margin-top: -1px
}

body.android .ui-top-bar~.ui-page&gt;.ui-page-content {
	margin-top: -1px;
	border-top: 53px solid transparent
}

.ui-page {
	position: absolute;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	z-index: 2;
	display: none;
	width: 100%;
	overflow: auto;
	overflow-x: hidden;
	-webkit-overflow-scrolling: touch
}

.ui-page.js-active {
	display: block;
	margin-top: 53px
}

body.android .ui-page.js-active {
	margin-top: 0
}

.ui-page-content {
	z-index: 2
}

.js-no-overflow-scrolling .ui-page {
	position: static;
	margin-top: 52px
}

.ui-bottom-bar-button {
	text-align: center;
	color: #6f6f74;
	border: 0;
	outline: 0;
	background-color: transparent
}

.ui-flex-1,.ui-bottom-bar-button {
	display: block;
	-webkit-box-flex: 1;
	-webkit-flex: 1;
	flex: 1
}

.ui-bottom-bar-button .ui-icon {
	display: block;
	padding: 2px 0;
	font-size: 24px;
	text-align: center;
	color: #6f6f74;
	background-color: transparent
}

.ui-bottom-bar-button .ui-label {
	display: block;
	font-size: 14px
}

.ui-bottom-bar-button.js-active {
	color: #157dfb
}

.ui-bottom-bar-button.js-active .ui-icon {
	color: #157dfb
}

@media screen and (-webkit-min-device-pixel-ratio:2) {
	.ui-1dpx-t,.ui-1dpx-b,.ui-1dpx-tb {
		background-size: 100% 1px
	}

	.ui-1dpx-t {
		border-top: 0;
		background: -webkit-gradient(linear,left bottom,left top,color-stop(0.5,transparent),color-stop(0.5,#c8c7cc),to(#c8c7cc)) left top repeat-x
	}

	.ui-1dpx-b {
		border-bottom: 0;
		background: -webkit-gradient(linear,left top,left bottom,color-stop(0.5,transparent),color-stop(0.5,#c8c7cc),to(#c8c7cc)) left bottom repeat-x
	}

	.ui-1dpx-tb {
		border-top: 0;
		border-bottom: 0;
		background: -webkit-gradient(linear,left bottom,left top,color-stop(0.5,transparent),color-stop(0.5,#c8c7cc),to(#c8c7cc)) left top repeat-x,-webkit-gradient(linear,left top,left bottom,color-stop(0.5,transparent),color-stop(0.5,#c8c7cc),to(#c8c7cc)) left bottom repeat-x
	}
}

.ui-ignore-space {
	font-size: 0
}

.ui-no-wrap {
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis
}

.ui-red {
	color: #fa3c3c!important
}

.ui-gray {
	color: gray!important
}

.ui-red-dot,.ui-blue-dot {
	width: 8px;
	height: 8px;
	border-radius: 8px
}

.ui-red-dot {
	background: #fa3c3c!important
}

.ui-blue-dot {
	background: #0079ff!important
}

.ui-right {
	float: right!important
}

.ui-left {
	float: left!important
}

.ui-block {
	display: block!important
}

.ui-inline-block {
	display: inline-block!important
}

.ui-red-counter,.ui-blue-counter {
	position: absolute;
	top: -9px;
	right: -9px;
	z-index: 3;
	height: 18px;
	min-width: 18px;
	padding: 0 4px;
	font-size: 13px;
	line-height: 18px;
	text-align: center;
	color: #fff;
	border-radius: 18px
}

.ui-red-counter {
	background: #fa3c3c
}

.ui-blue-counter {
	background: #2087fc
}

.ui-panel {
	padding: 10px 12px;
	margin-bottom: 20px;
	font-size: 15px;
	border-width: 1px 0;
	border-style: solid;
	border-color: #e5e5eb;
	background: #fff
}

.ui-button {
	display: inline-block;
	padding: 6px 10px;
	margin: 4px 0;
	-webkit-box-sizing: border-box;
	font-size: 13px;
	line-height: 15px;
	text-align: center;
	text-decoration: none;
	color: #0079ff;
	border: 1px solid #0079ff;
	border-radius: 3px;
	background-color: transparent;
	-webkit-appearance: none
}

.ui-button:active {
	color: #fff;
	background: #0079ff
}

.ui-button[disabled] {
	pointer-events: none;
	opacity: .6;
	color: #a6a6a6;
	border-color: #a6a6a6;
	background: #fff
}

.ui-button.ui-block {
	display: block;
	width: 100%;
	height: 44px;
	padding: 10px;
	font-size: 17px;
	line-height: 17px
}

.ui-info {
	color: #fff;
	border-color: #0079ff;
	background: #0079ff
}

.ui-info:active {
	opacity: .4
}

.ui-success {
	color: #fff;
	border-color: #46ca5e;
	background: #46ca5e
}

.ui-success:active {
	opacity: .4;
	border-color: #46ca5e;
	background: #46ca5e
}

.ui-danger {
	color: #fc4226;
	border-color: #fc4226
}

.ui-danger:active {
	color: #fff;
	background: #fc4226
}

.ui-groupbutton {
	display: table;
	margin: 12px auto;
	font-size: 1em;
	white-space: nowrap;
	border-color: transparent
}

.ui-groupbutton .ui-button {
	position: relative;
	display: table-cell;
	float: left;
	min-width: 98px;
	margin: 0;
	border-width: 1px 0 1px 1px;
	border-radius: 0
}

.ui-groupbutton .ui-button:active {
	color: #0079ff;
	background-color: #d3e5f9
}

.ui-groupbutton .ui-button.js-active {
	color: #fff;
	background: #0079ff
}

.ui-groupbutton .ui-button:first-child {
	border-right-width: 0;
	border-radius: 5px 0 0 5px
}

.ui-groupbutton .ui-button:last-child {
	border-width: 1px;
	border-radius: 0 5px 5px 0
}

.ui-groupbutton.ui-gray .ui-button {
	color: gray;
	border-color: gray
}

.ui-groupbutton.ui-gray .ui-button:active {
	background-color: #e6e6e6
}

.ui-groupbutton.ui-gray .ui-button.js-active {
	color: #fff;
	background: gray
}

.ui-caption {
	padding: 0 12px;
	margin: 18px 0 5px;
	font-size: 14px;
	line-height: 24px;
	color: #7c7b83;
	text-shadow: 0 1px rgba(255,255,255,.2);
	-wbkit-text-shadow: 0 1px rgba(255,255,255,.2)
}

.ui-list {
	padding-bottom: 1px;
	margin-bottom: 20px;
	border-width: 1px 0;
	border-style: solid;
	border-color: #e5e5eb;
	background: #fff;
	background: #fff
}

.ui-item {
	position: relative;
	height: 72px;
	margin-top: 0;
	margin-left: 0;
	overflow: hidden;
	font-size: 17px;
	font-weight: 500;
	line-height: 20px;
	list-style-type: none;
	color: #000;
	border: 1px solid transparent
}

.hovered.ui-item {
	background: #f0f0f0
}

.ui-item+.ui-item {
	border-top: 1px solid transparent
}

.ui-item+.ui-item a:before,.ui-spliter-ios .ui-item+.ui-item a .my-item-right:before {
	position: absolute;
	top: 0;
	display: block;
	width: 100%;
	height: 1px;
	content: &quot;
	&quot;;-webkit-transform: scale3d(1,.5,1);
	background: #dfdfdf
}

.android .ui-item+.ui-item a:before,.android .ui-spliter-ios .ui-item+.ui-item a .my-item-right:before {
	-webkit-transform: scale(1,.5)
}

.ui-spliter-ios .ui-item+.ui-item a:before {
	display: none
}

.android .ui-item+.ui-item a .my-item-right:before {
	-webkit-transform: scale(1,.5)
}

.hovered.ui-item .my-item-right:before {
}

.hovered.ui-item+.ui-item .my-item-right:before {
	background-color: transparent!important
}

.my-item-right,.my-item-left {
	padding-top: 13px;
	padding-bottom: 8px
}

.ui-item&gt;a {
	position: relative;
	display: block;
	padding: 0;
	margin-left: 6px;
	color: #000
}

.ui-item&gt;a.js-active {
	z-index: 1;
	color: #fff;
	background: #2087fc
}

.ui-item:after {
	position: absolute;
	top: -1px;
	bottom: -1px;
	z-index: 2;
	width: 42px;
	font-size: 32px;
	line-height: 42px;
	text-align: center;
	color: #fff
}

.ui-item.deleting:after {
	right: 0;
	line-height: 40px;
	content: &quot;
	Delete&quot;;background: #fd3c31
}

.ui-item .ui-right {
	position: absolute;
	right: 34px;
	font-size: 14px;
	text-align: right
}

.ui-item .ui-red-dot {
	position: absolute;
	top: 16px
}

.ui-list.arrow-right&gt;.ui-item:after,.ui-item.arrow-right:after {
	position: absolute;
	top: 14px;
	right: 15px;
	width: 8px;
	height: 8px;
	margin-top: 20px;
	content: &quot;
	&quot;;-webkit-transform: rotate(45deg);
	color: #dbdbdb;
	border-width: 2px 2px 0 0;
	border-style: solid
}

.ui-list.arrow-right&gt;.ui-item.js-active:after,.ui-item.arrow-right.js-active:after {
	color: #fff
}

.ui-list.icon-left .ui-icon {
	position: absolute;
	top: 6px;
	left: 8px;
	width: 25px;
	height: 25px
}

.ui-list.icon-left&gt;.ui-item {
	padding-left: 40px
}

.ui-title-item {
	position: relative;
	padding: 0 12px;
	margin: 0;
	font-size: 14px;
	line-height: 26px;
	color: #000;
	border: 1px solid #e5e5eb;
	border-width: 1px 0;
	background: #f5f5f5;
	box-shadow: none
}

.ui-title-item:first-child {
	border-top-width: 0
}

.ui-tab {
	display: none
}

.ui-tab.js-active {
	display: block
}

.ui-carousel {
	position: relative;
	-webkit-transform: translate(0,0)
}

.ui-carousel-inner {
	position: relative;
	width: 100%;
	height: 100%;
	overflow: hidden
}

.ui-carousel-item {
	position: relative;
	z-index: 9;
	display: none;
	-webkit-transition: .2s ease-in-out left;
	transition: .2s ease-in-out left;
	-webkit-transform: translate(0,0)
}

.ui-carousel-inner&gt;.js-show,.ui-carousel-inner&gt;.js-active,.ui-carousel-inner&gt;.js-next,.ui-carousel-inner&gt;.js-prev {
	display: block
}

.ui-carousel-inner&gt;.js-active {
	left: 0;
	z-index: 10
}

.ui-carousel-inner&gt;.js-show,.ui-carousel-inner&gt;.js-next,.ui-carousel-inner&gt;.js-prev {
	position: absolute;
	top: 0;
	width: 100%
}

.ui-carousel-inner&gt;.js-next {
	left: 100%
}

.ui-carousel-inner&gt;.js-prev {
	left: -100%
}

.ui-carousel-inner&gt;.js-next.js-left,.ui-carousel-inner&gt;.js-prev.js-right {
	left: 0
}

.ui-carousel-inner&gt;.js-active.js-left {
	left: -100%
}

.ui-carousel-inner&gt;.js-active.js-right {
	left: 100%
}

.ui-carousel-indicators {
	position: absolute;
	bottom: 10px;
	left: 50%;
	z-index: 11;
	width: 60%;
	padding-left: 0;
	margin-left: -30%;
	list-style: none;
	-webkit-transform: translate(0,0);
	text-align: center
}

.ui-carousel-indicators li {
	display: inline-block;
	width: 8px;
	height: 8px;
	margin: 1px;
	border-radius: 8px;
	background-color: rgba(255,255,255,.3)
}

.ui-carousel-indicators li.js-active {
	background-color: #fff
}

@media screen and (-webkit-min-device-pixel-ratio:2) {
}
 </body>
</html>