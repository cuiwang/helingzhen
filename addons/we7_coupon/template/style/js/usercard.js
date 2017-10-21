angular.module('userCardApp', ['wapeditorApp']).controller('MainCtrl', [
  '$scope',
  'widget',
  'config',
  'serviceBase',
  'serviceUserCardBase',
  'serviceSubmit',
  'serviceCommon',
  '$sanitize',
  function ($scope, widget, config, serviceBase, serviceUserCardBase, serviceSubmit, serviceCommon, $sanitize) {
    $scope.modules = [];
    $scope.editors = [];
    //全部插入模块数据
    $scope.activeModules = serviceBase.initActiveModules(config.activeModules);
    //当前活动的模块
    $scope.activeItem = {};
    //当前活动模块的索引
    $scope.activeIndex = 0;
    //模块流水id
    $scope.index = config.activeModules ? config.activeModules.length : 0;
    $scope.submit = {
      'params': {},
      'html': ''
    };
    $scope.newcard = config.newcard;
    $scope.fansFields = config.fansFields;
    // 监听事件区域
    $scope.$on('serviceBase.editors.update', function (event, editors) {
      $scope.editors = editors;
    });
    $scope.$on('serviceBase.activeItem.update', function (event, activeItem) {
      $scope.activeItem = activeItem;
    });
    $scope.$on('serviceBase.activeModules.update', function (event, activeModules) {
      $scope.activeModules = activeModules;
    });
    // base区域
    $scope.addItem = function (type) {
      serviceBase.addItem(type);
    };
    $scope.editItem = function (index) {
      serviceUserCardBase.editItem(index);
    };
    $scope.deleteItem = function (index) {
      serviceBase.deleteItem(index);
    };
    $scope.init = function (modules, showModules) {
      $scope.modules = serviceBase.setModules(modules, showModules);
      if ($scope.activeModules.length > 0) {
        var activeModulesKey = [];
        angular.forEach($scope.activeModules, function (module, index) {
          if (module) {
            activeModulesKey.push(module.id);
          }
        });
      }
      angular.forEach($scope.modules, function (module, index) {
        if (module.defaultshow && $.inArray(module.id, activeModulesKey) == -1) {
          serviceBase.addItem(module.id);
        }
      });
    };
    $scope.url = function (segment) {
      return serviceCommon.url(segment);
    };
    $scope.tomedia = function (url) {
      return serviceCommon.tomedia(url);
    };
    $scope.submit = function (event) {
      $scope.submit = serviceSubmit.submit();
      $scope.$apply('submit');
      $(event.target).parents('form').submit();
    };
    $scope.addFields = function () {
      serviceUserCardBase.addFields();
    };
    $scope.removeFields = function (item) {
      serviceUserCardBase.removeFields(item);
    };
    $scope.addNums = function () {
      serviceUserCardBase.addNums();
    };
    $scope.removeNums = function (item) {
      serviceUserCardBase.removeNums(item);
    };
    $scope.addRecharges = function () {
      serviceUserCardBase.addRecharges();
    };
    $scope.removeRecharges = function (item) {
      serviceUserCardBase.removeRecharges(item);
    };
    $scope.addTimes = function () {
      serviceUserCardBase.addTimes();
    };
    $scope.removeTimes = function (item) {
      serviceUserCardBase.removeTimes(item);
    };
    $scope.selectCoupon = function () {
      serviceUserCardBase.selectCoupon();
    };
    $scope.clearCoupon = function () {
      serviceUserCardBase.clearCoupon();
    };
    $scope.addThumb = function (type) {
      serviceUserCardBase.addThumb(type);
    };
    $scope.addBgThumb = function () {
      serviceUserCardBase.addBgThumb();
    };
    $('.single-submit').on('click', function (event) {
      $scope.submit(event);
    });
    $scope.init(null, [
      'cardBasic',
      'cardActivity',
      'cardNums',
      'cardTimes',
      'cardRecharge'
    ]);
    $scope.activeModules[1].params.discounts = config.discounts;
    $scope.editItem(0);
  }
]);
// test，填写对应文件类型内容

// test，填写对应文件类型内容

angular.module('userCardApp').service('serviceUserCardBase', [
  '$rootScope',
  'serviceBase',
  function ($rootScope, serviceBase) {
    var serviceUserCardBase = {};
    serviceUserCardBase.triggerActiveItem = function (index) {
      $('.app-side .editor').css('marginTop', '0');
      serviceBase.triggerActiveItem(index);
    };
    serviceUserCardBase.editItem = function (index) {
      //保存数据到数组结构中
      //切换当前选中的模块到activeItem
      var activeModules = serviceBase.getBaseData('activeModules');
      if (typeof index == 'string') {
        angular.forEach(activeModules, function (active) {
          if (active.id == index) {
            index = active.index;
          }
          ;
        });
      }
      serviceBase.editItem(index);
    };
    serviceUserCardBase.addFields = function () {
      var activeItem = serviceBase.getBaseData('activeItem');
      activeItem.params.fields.push({
        title: '',
        require: 1,
        bind: '',
        issystem: 0
      });
      serviceBase.setBaseData('activeItem', activeItem);
      $rootScope.$broadcast('serviceBase.activeItem.update', activeItem);
    };
    serviceUserCardBase.removeFields = function (item) {
      if (item.bind == 'mobile' || item.bind == 'realname') {
        return false;
      }
      var activeItem = serviceBase.getBaseData('activeItem');
      activeItem.params.fields = _.without(activeItem.params.fields, item);
      serviceBase.setBaseData('activeItem', activeItem);
      $rootScope.$broadcast('serviceBase.activeItem.update', activeItem);
    };
    serviceUserCardBase.addNums = function () {
      var activeItem = serviceBase.getBaseData('activeItem');
      activeItem.params.nums.push({
        recharge: '',
        num: ''
      });
    };
    serviceUserCardBase.removeNums = function (item) {
      var activeItem = serviceBase.getBaseData('activeItem');
      activeItem.params.nums = _.without(activeItem.params.nums, item);
      serviceBase.setBaseData('activeItem', activeItem);
      $rootScope.$broadcast('serviceBase.activeItem.update', activeItem);
    };
    serviceUserCardBase.addRecharges = function () {
      var activeItem = serviceBase.getBaseData('activeItem');
      activeItem.params.recharges.push({
        condition: '',
        back: '',
        backtype: '0',
        backunit: '\u5143'
      });
      serviceBase.setBaseData('activeItem', activeItem);
      $rootScope.$broadcast('serviceBase.activeItem.update', activeItem);
    };
    serviceUserCardBase.removeRecharges = function (item) {
      var activeItem = serviceBase.getBaseData('activeItem');
      activeItem.params.recharges = _.without(activeItem.params.recharges, item);
      serviceBase.setBaseData('activeItem', activeItem);
      $rootScope.$broadcast('serviceBase.activeItem.update', activeItem);
    };
    serviceUserCardBase.addTimes = function () {
      var activeItem = serviceBase.getBaseData('activeItem');
      activeItem.params.times.push({
        recharge: '',
        time: ''
      });
      serviceBase.setBaseData('activeItem', activeItem);
      $rootScope.$broadcast('serviceBase.activeItem.update', activeItem);
    };
    serviceUserCardBase.removeTimes = function (item) {
      var activeItem = serviceBase.getBaseData('activeItem');
      activeItem.params.times = _.without(activeItem.params.times, item);
      serviceBase.setBaseData('activeItem', activeItem);
      $rootScope.$broadcast('serviceBase.activeItem.update', activeItem);
    };
    serviceUserCardBase.selectCoupon = function () {
      var activeItem = serviceBase.getBaseData('activeItem');
      util.coupon(function (coupon) {
        activeItem.params.grant.coupon = [];
        angular.forEach(coupon, function (single) {
          activeItem.params.grant.coupon.push({
            couponTitle: single.title,
            coupon: single.id
          });
        });
        serviceBase.setBaseData('activeItem', activeItem);
        $rootScope.$apply();
        $rootScope.$broadcast('serviceBase.activeItem.update', activeItem);
      }, { 'multiple': true });
    };
    serviceUserCardBase.clearCoupon = function () {
      var activeItem = serviceBase.getBaseData('activeItem');
      activeItem.params.grant.coupon = [];
      serviceBase.setBaseData('activeItem', activeItem);
      $rootScope.$broadcast('serviceBase.activeItem.update', activeItem);
    };
    serviceUserCardBase.addThumb = function (type) {
      var activeItem = serviceBase.getBaseData('activeItem');
      require(['fileUploader'], function (uploader) {
        uploader.show(function (img) {
          activeItem.params[type] = img.url;
          serviceBase.setBaseData('activeItem', activeItem);
          $rootScope.$apply();
          $rootScope.$broadcast('serviceBase.activeItem.update', activeItem);
        }, {
          'direct': true,
          'multiple': false
        });
      });
    };
    serviceUserCardBase.addBgThumb = function () {
      var activeItem = serviceBase.getBaseData('activeItem');
      require(['fileUploader'], function (uploader) {
        uploader.show(function (img) {
          activeItem.params.background.image = img.url;
          serviceBase.setBaseData('activeItem', activeItem);
          $rootScope.$apply();
          $rootScope.$broadcast('serviceBase.activeItem.update', activeItem);
        }, {
          'direct': true,
          'multiple': false
        });
      });
    };
    return serviceUserCardBase;
  }
]);
angular.module('userCardApp').controller('CardActivityCtrl', [
  '$scope',
  function ($scope) {
    $scope.$watch('activeItem.params.grant_rate', function (newVal, oldVal) {
      newVal += '';
      if (newVal.match(/^([1-9]\d*(\.(\d)?)?|0(\.(\d)?)?)?$/)) {
        $scope.activeItem.params.grant_rate = newVal;
      } else {
        $scope.activeItem.params.grant_rate = oldVal;
      }
    });
  }
]);
angular.module('userCardApp').controller('CardBasicCtrl', [
  '$scope',
  'config',
  function ($scope, config) {
    $scope.creditnames = config.creditnames;
    $scope.siteroot = config.siteroot;
    $scope.recharge_src = $scope.siteroot + '/app/resource/images/sum-recharge.png';
    $scope.scanpay_src = $scope.siteroot + '/app/resource/images/scan-pay.png';
  }
]);
angular.module('userCardApp').controller('CardNumsCtrl', [
  '$scope',
  function ($scope) {
  }
]);
angular.module('userCardApp').controller('CardRechargeCtrl', [
  '$scope',
  function ($scope) {
  }
]);
angular.module('userCardApp').controller('CardTimesCtrl', [
  '$scope',
  function ($scope) {
  }
]);