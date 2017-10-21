define(function() {
	var newcard = false;
	var creditnames = {
		'credit1': '积分',
		'credit2': '余额'
	};
	var discounts = {
		"6366": {
			"groupid": "6366",
			"title": "\u9ed8\u8ba4\u4f1a\u5458\u7ec4",
			"credit": "0",
			"condition_1": null,
			"discount_1": null,
			"condition_2": null,
			"discount_2": null
		}
	};
	var fansFields = {
		"realname": {
			"title": "\u771f\u5b9e\u59d3\u540d",
			"bind": "realname"
		},
		"nickname": {
			"title": "\u6635\u79f0",
			"bind": "nickname"
		},
		"avatar": {
			"title": "\u5934\u50cf",
			"bind": "avatar"
		},
		"qq": {
			"title": "QQ\u53f7",
			"bind": "qq"
		},
		"mobile": {
			"title": "\u624b\u673a\u53f7\u7801",
			"bind": "mobile"
		},
		"vip": {
			"title": "VIP\u7ea7\u522b",
			"bind": "vip"
		},
		"gender": {
			"title": "\u6027\u522b",
			"bind": "gender"
		},
		"birthyear": {
			"title": "\u51fa\u751f\u751f\u65e5",
			"bind": "birthyear"
		},
		"constellation": {
			"title": "\u661f\u5ea7",
			"bind": "constellation"
		},
		"zodiac": {
			"title": "\u751f\u8096",
			"bind": "zodiac"
		},
		"telephone": {
			"title": "\u56fa\u5b9a\u7535\u8bdd",
			"bind": "telephone"
		},
		"idcard": {
			"title": "\u8bc1\u4ef6\u53f7\u7801",
			"bind": "idcard"
		},
		"studentid": {
			"title": "\u5b66\u53f7",
			"bind": "studentid"
		},
		"grade": {
			"title": "\u73ed\u7ea7",
			"bind": "grade"
		},
		"address": {
			"title": "\u90ae\u5bc4\u5730\u5740",
			"bind": "address"
		},
		"zipcode": {
			"title": "\u90ae\u7f16",
			"bind": "zipcode"
		},
		"nationality": {
			"title": "\u56fd\u7c4d",
			"bind": "nationality"
		},
		"resideprovince": {
			"title": "\u5c45\u4f4f\u5730\u5740",
			"bind": "resideprovince"
		},
		"graduateschool": {
			"title": "\u6bd5\u4e1a\u5b66\u6821",
			"bind": "graduateschool"
		},
		"company": {
			"title": "\u516c\u53f8",
			"bind": "company"
		},
		"education": {
			"title": "\u5b66\u5386",
			"bind": "education"
		},
		"occupation": {
			"title": "\u804c\u4e1a",
			"bind": "occupation"
		},
		"position": {
			"title": "\u804c\u4f4d",
			"bind": "position"
		},
		"revenue": {
			"title": "\u5e74\u6536\u5165",
			"bind": "revenue"
		},
		"affectivestatus": {
			"title": "\u60c5\u611f\u72b6\u6001",
			"bind": "affectivestatus"
		},
		"lookingfor": {
			"title": " \u4ea4\u53cb\u76ee\u7684",
			"bind": "lookingfor"
		},
		"bloodtype": {
			"title": "\u8840\u578b",
			"bind": "bloodtype"
		},
		"height": {
			"title": "\u8eab\u9ad8",
			"bind": "height"
		},
		"weight": {
			"title": "\u4f53\u91cd",
			"bind": "weight"
		},
		"alipay": {
			"title": "\u652f\u4ed8\u5b9d\u5e10\u53f7",
			"bind": "alipay"
		},
		"msn": {
			"title": "MSN",
			"bind": "msn"
		},
		"email": {
			"title": "\u7535\u5b50\u90ae\u7bb1",
			"bind": "email"
		},
		"taobao": {
			"title": "\u963f\u91cc\u65fa\u65fa",
			"bind": "taobao"
		},
		"site": {
			"title": "\u4e3b\u9875",
			"bind": "site"
		},
		"bio": {
			"title": "\u81ea\u6211\u4ecb\u7ecd",
			"bind": "bio"
		},
		"interest": {
			"title": "\u5174\u8da3\u7231\u597d",
			"bind": "interest"
		}
	};

	var activeModules = {
		'cardBasic': {
			'id': 'cardBasic',
			'name': '会员卡基本设置',
			'issystem': true,
			'params': {
				'title': '会员卡',
				'color': {
					'title': '#333',
					'rank': '#333',
					'name': '#333',
					'number': '#333'
				},
				'card_level': {
					'type': 1,
				},
				'card_label': {
					'type': 1,
					'title': '会员卡标题'
				},
				'description': '1、本卡采取记名消费方式\n2、持卡人可享受会员专属优惠\n3、本卡不能与其他优惠活动同时使用\n4、持卡人可用卡内余额进行消费',
				'background': {
					'type': 'system',
					'image': util.tomedia('images/global/card/6.png')
				},
				'logo': util.tomedia('http://www.baidu.com/img/bdlogo.gif'),
				'format_type': 1,
				'format': 'WQ2015*****#####***',
				'fields': [{
						'title': '姓名',
						'require': 1,
						'bind': 'realname'
					},
					{
						'title': '手机',
						'require': 1,
						'bind': 'mobile'
					}
				],
				'grant': {
					'credit1': 0,
					'credit2': 0,
					'coupon': [],
				},
				'grant_rate': 0,
				'offset_rate': 0,
				'offset_max': 0
			}
		},
		'cardActivity': {
			'id': 'cardActivity',
			'name': '消费优惠设置',
			'issystem': true,
			'params': {
				'discount_type': 0,
				'discount_style': 1,
				'discounts': [],
				'content': '',
				'bgColor': ''
			}
		},
		'cardNums': {
			'id': 'cardNums',
			'name': '会员卡次数设置',
			'issystem': true,
			'params': {
				'nums_status': 0,
				'nums_style': 1,
				'nums_text': '可用次数',
				'nums': [{
						'recharge': 100,
						'num': 5
					},
					{
						'recharge': 200,
						'num': 10
					}
				]
			}
		},
		'cardTimes': {
			'id': 'cardTimes',
			'name': '会员卡计时设置',
			'issystem': true,
			'params': {
				'times_status': 0,
				'times_style': 1,
				'times_text': '截至日期',
				'times': [{
						'recharge': 100,
						'time': 5
					},
					{
						'recharge': 200,
						'time': 10
					}
				]
			}
		},
		'cardRecharge': {
			'id': 'cardRecharge',
			'name': '充值优惠设置',
			'issystem': true,
			'params': {
				'recharge_type': 0,
				'recharge_style': 1,
				'grant_rate_switch': 1,
				'recharges': [{
						'condition': '',
						'back': '',
						'backtype': '0',
						'backunit': '元'
					},
					{
						'condition': '',
						'back': '',
						'backtype': '0',
						'backunit': '元'
					},
					{
						'condition': '',
						'back': '',
						'backtype': '0',
						'backunit': '元'
					},
					{
						'condition': '',
						'back': '',
						'backtype': '0',
						'backunit': '元'
					}
				],
				'content': '',
				'bgColor': ''
			}
		}
	};
	return {
		newcard,
		activeModules,
		creditnames,
		discounts,
		fansFields
	};
});
