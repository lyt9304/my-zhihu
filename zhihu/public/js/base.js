/**
 * Created by lyt9304 on 16/10/4.
 */

;(function(){
	'use strict'

	angular.module('my-zhihu', [
			'ui.router'
		])
		.config(function(
			$interpolateProvider,
			$stateProvider,
			$urlRouterProvider
		){
			$interpolateProvider.startSymbol('[:');
			$interpolateProvider.endSymbol(':]');

			$urlRouterProvider.otherwise('/home');

			$stateProvider
				.state('home', {
					url: '/home',
					//template: '<h1>首页</h1>'
					templateUrl: 'home.tpl'
					// 1. 一开始会在script中寻找id为这个的template
					// 2. 如果没有就会找0.0.0.0:1024/home.tpl
				})
				.state('login', {
					url: '/login',
					//template: '<h1>登录</h1>'
					templateUrl: 'login.tpl'
				})

		})
		.controller('TestController', function($scope){
			$scope.name = "Bob";
		})

})();