/*	
 *	jQuery mmenu 1.3.1
 *	
 *	Copyright (c) 2013 Fred Heusschen
 *	www.frebsite.nl
 *
 *	Dual licensed under the MIT and GPL licenses.
 *	http://en.wikipedia.org/wiki/MIT_License
 *	http://en.wikipedia.org/wiki/GNU_General_Public_License
 */


(function( $ ) {

	//	Global nodes
	var $wndw = null,
		$html = null,
		$body = null,
		$page = null,
		$blck = null;

	var $scrollTopNode = null;

	//	Global vars
	var _serialnr = 0;


	$.fn.mmenu = function( opts )
	{
		if ( !$wndw )
		{
			$wndw = $(window);
			$html = $('html');
			$body = $('body');
		}

		opts = extendOptions( opts );
		opts = $.extend( true, {}, $.fn.mmenu.defaultOptions, opts );
		opts = complementOptions( opts );

		$html[ opts.configuration.hardwareAcceleration ? 'addClass' : 'removeClass' ]( cls( 'accelerated' ) );

		return this.each(
			function()
			{

				//	STORE VARIABLES
				var $menu 		= $(this),
					_opened 	= false,
					_direction	= ( opts.slidingSubmenus ) ? 'horizontal' : 'vertical';

				_serialnr++;



				//	INIT PAGE, MENU, LINKS & LABELS
				$page = _initPage( $page, opts.configuration );
				$blck = _initBlocker( $blck, $menu, opts.configuration );
				$menu = _initMenu( $menu, opts.configuration );
				_initSubmenus( $menu, _direction, _serialnr );
				_initLinks( $menu, opts.onClick, opts.configuration );
				_initCounters( $menu, opts.counters, opts.configuration );
				_initSearch( $menu, opts.searchfield );
				_initOpenClose( $menu, $page );


				//	BIND EVENTS
				var $subs = $menu.find( 'ul' );
				$menu.add( $subs )
					.bind(
						evt( 'toggle' ) + ' ' + evt( 'open' ) + ' ' + evt( 'close' ),
						function( e )
						{
							e.preventDefault();
							e.stopPropagation();
						}
					);

				//	menu-events
				$menu
					.bind(
						evt( 'toggle' ),
						function( e )
						{
							return $menu.triggerHandler( evt( _opened ? 'close' : 'open' ) );							
						}
					)
					.bind(
						evt( 'open' ),
						function( e )
						{	
							//$(".market_bottom").addClass("good_list_bottom");
							if ( _opened )
							{
								return false;
							}
							_opened = true;
							return openMenu( $menu, opts.position );
						}
					)
					.bind(
						evt( 'close' ),
						function( e )
						{	
							//$(".market_bottom").removeClass("good_list_bottom");
							if ( !_opened )
							{
								return false;
							}
							_opened = false;
							return closeMenu( $menu, opts );
						}
					);


				//	submenu-events
				if ( _direction == 'horizontal' )
				{
					$subs
						.bind(
							evt( 'toggle' ),
							function( e )
							{								
								return $(this).triggerHandler( evt( 'open' ) );
							}
						)
						.bind(
							evt( 'open' ),
							function( e )
							{
								return openSubmenuHorizontal( $(this), opts );
							}
						)
						.bind(
							evt( 'close' ),
							function( e )
							{
								return closeSubmenuHorizontal( $(this), opts );
							}
						);
				}
				else
				{
					$subs
						.bind(
							evt( 'toggle' ),
							function( e )
							{
								var $t = $(this);
								return $t.triggerHandler( evt( ( $t.parent().hasClass( cls( 'opened' ) ) ) ? 'close' : 'open' ) );
							}
						)
						.bind(
							evt( 'open' ),
							function( e )
							{
								$(this).parent().addClass( cls( 'opened' ) );
								return 'open';
							}
						)
						.bind(
							evt( 'close' ),
							function( e )
							{
								$(this).parent().removeClass( cls( 'opened' ) );
								return 'close';
							}
						);
				}
			}
		);
	};


	$.fn.mmenu.defaultOptions = {
		position		: 'left',
		slidingSubmenus	: true,
		counters		: {
			add					: false,
			count				: true
		},
		searchfield		: {
			add					: false,
			search				: true,
			showLinksOnly		: true,
			placeholder			: 'Search',
			noResults			: 'No results found.'
		},
		onClick			: {
			close				: true,
			delayPageload		: true,
			blockUI				: false
		},
		configuration	: {
			hardwareAcceleration: true,
			selectedClass		: 'Selected',
			labelClass			: 'Label',
			counterClass		: 'Counter',
			pageNodetype		: 'div',
			menuNodetype		: 'nav',
			slideDuration		: 500
		}
	};

	$.fn.mmenu.debug = function( msg )
	{
		if ( typeof console != 'undefined' && typeof console.log != 'undefined' )
		{
			console.log( 'MMENU: ' + msg );
		}
	};
	$.fn.mmenu.deprecated = function( depr, repl )
	{
		if ( typeof console != 'undefined' && typeof console.warn != 'undefined' )
		{
			console.warn( 'MMENU: ' + depr + ' is deprecated, use ' + repl + ' instead.' );
		}
	};


	function extendOptions( o )
	{
		if ( typeof o == 'string' )
		{
			if ( o == 'left' || o == 'right' )
			{
				o = {
					position: o
				};
			}
		}
		else if ( typeof o != 'object' )
		{
			o = {};
		}

		//	DEPRECATED
		if ( typeof o.addCounters != 'undefined' )
		{
			$.fn.mmenu.deprecated( 'addCounters-option', 'counters.add-option' );
			o.counters = {
				add: o.addCounters
			};
		}
		if ( typeof o.closeOnClick != 'undefined' )
		{
			$.fn.mmenu.deprecated( 'closeOnClick-option', 'onClick.close-option' );
			o.onClick = {
				close: o.closeOnClick
			};
		}
		//	/DEPRECATED

		//	Counters
		if ( typeof o.counters == 'boolean' )
		{
			o.counters = {
				add		: o.counters,
				count	: o.counters
			};
		}
		else if ( typeof o.counters != 'object' )
		{
			o.counters = {};
		}

		//	OnClick
		if ( typeof o.onClick == 'boolean' )
		{
			o.onClick = {
				close	: o.onClick
			};
		}
		else if ( typeof o.onClick != 'object' )
		{
			o.onClick = {};
		}

		//	Search
		if ( typeof o.searchfield == 'boolean' )
		{
			o.searchfield = {
				add		: o.searchfield,
				search	: o.searchfield
			};
		}
		else if ( typeof o.searchfield == 'string' )
		{
			o.searchfield = {
				add			: true,
				search		: true,
				placeholder	: o.searchfield
			};
		}
		else if ( typeof o.searchfield != 'object' )
		{
			o.searchfield = {};
		}

		return o;
	}
	function complementOptions( o )
	{
		if ( typeof o.onClick.delayPageload == 'boolean' )
		{
			o.onClick.delayPageload = ( o.onClick.delayPageload ) ? o.configuration.slideDuration : 0;
		}
		return o;
	}

	function _initPage( $p, conf )
	{
		if ( !$p )
		{
			$p = $('> ' + conf.pageNodetype, $body);
			if ( $p.length > 1 )
			{
				$p = $p.wrapAll( '<' + conf.pageNodetype + ' />' ).parent();
			}
			$p.addClass( cls( 'page' ) );
		}
		return $p;
	}

	function _initMenu( $m, conf )
	{
		if ( !$m.is( conf.menuNodetype ) )
		{
			$m = $( '<' + conf.menuNodetype + ' />' ).append( $m );
		}
	//	$_dummy = $( '<div class="mmenu-dummy" />' ).insertAfter( $m ).hide();
		$m.addClass( cls( '' ).slice( 0, -1 ) ).prependTo( 'body' );

		//	Refactor selected class
		$('li.' + conf.selectedClass, $m).removeClass( conf.selectedClass ).addClass( cls( 'selected' ) );

		//	Refactor label class
		$('li.' + conf.labelClass, $m).removeClass( conf.labelClass ).addClass( cls( 'label' ) );

		return $m;
	}

	function _initSubmenus( $m, direction, serial )
	{
		$m.addClass( cls( direction ) );

		$( 'ul ul', $m )
			.addClass( cls( 'submenu' ) )
			.each(
				function( i )
				{
					var $t = $(this)
						$a = $t.parent().find( '> a, > span' ),
						id = $t.attr( 'id' ) || cls( 's' + serial + '-' + i );

					$t.attr( 'id', id );

					var $btn = $( '<a class="' + cls( 'subopen' ) + '" href="#' + id + '" />' ).insertBefore( $a );
					if ( !$a.is( 'a' ) )
					{
						$btn.addClass( cls( 'fullsubopen' ) );
					}

					if ( direction == 'horizontal' )
					{
						var $p = $t.parent().parent(),
							id = $p.attr( 'id' ) || cls( 'p' + serial + '-' + i );
	
						$p.attr( 'id', id );
						$t.prepend( '<li class="' + cls( 'subtitle' ) + '"><a class="' + cls( 'subclose' ) + '" href="#' + id + '">' + $a.text() + '</a></li>' );
					}
				}
			);

		if ( direction == 'horizontal' )
		{
			//	Add opened-classes
			$('li.' + cls( 'selected' ), $m)
				.parents( 'li.' + cls( 'selected' ) ).removeClass( cls( 'selected' ) )
				.end().each(
					function()
					{
						var $t = $(this),
							$u = $t.find( '> ul' );
	
						if ( $u.length )
						{
							$t.parent().addClass( cls( 'subopened' ) );
							$u.addClass( cls( 'opened' ) );
						}
					}
				)
				.parent().addClass( cls( 'opened' ) )
				.parents( 'ul' ).addClass( cls( 'subopened' ) );

			if ( !$('ul.' + cls( 'opened' ), $m).length )
			{
				$('ul', $m).not( '.' + cls( 'submenu' ) ).addClass( cls( 'opened' ) );
			}

			//	Rearrange markup
			$('ul ul', $m).appendTo( $m );

		}
		else
		{
			//	Replace Selected-class with opened-class in parents from .Selected
			$('li.' + cls( 'selected' ), $m)
				.addClass( cls( 'opened' ) )
				.parents( '.' + cls( 'selected' ) ).removeClass( cls( 'selected' ) );
		}
	}
	function _initBlocker( $b, $m, conf )
	{
		if ( !$b )
		{
			$b = $( '<div id="' + cls( 'blocker' ) + '" />' ).appendTo( $body );
		}

		click( $b,
			function()
			{
				$m.trigger( evt( 'close' ) );
			}
		);
		return $b;
	}
	function _initLinks( $m, onClick, conf )
	{
		if ( onClick.close )
		{
			var $a = $('a', $m)
				.not( '.' + cls( 'subopen' ) )
				.not( '.' + cls( 'subclose' ) );

			click( $a,
				function()
				{
					var $t = $(this),
						href = $t.attr( 'href' );
	
					$m.trigger( evt( 'close' ) );
					$a.parent().removeClass( cls( 'selected' ) );
					$t.parent().addClass( cls( 'selected' ) );

					if ( onClick.blockUI && href.slice( 0, 1 ) != '#' )
					{
						$html.addClass( cls( 'blocking' ) );
					}

					if ( href != '#' )
					{
						setTimeout(
							function()
							{
								window.location.href = href;
							}, onClick.delayPageload
						);
					}
				}
			);
		}
	}
	function _initCounters( $m, counters, conf )
	{
		//	Refactor counter class
		$('em.' + conf.counterClass, $m).removeClass( conf.counterClass ).addClass( cls( 'counter' ) );

		//	Add counters
		if ( counters.add )
		{
			$('.' + cls( 'submenu' ), $m).each(
				function()
				{
					var $s = $(this),
						id = $s.attr( 'id' );
	
					if ( id && id.length )
					{
						var $c = $( '<em class="' + cls( 'counter' ) + '" />' ),
							$a = $('a.' + cls( 'subopen' ), $m).filter( '[href="#' + id + '"]' );

						if ( !$a.parent().find( 'em.' + cls( 'counter' ) ).length )
						{
							$a.before( $c );
						}
					}
				}
			);
		}

		//	Bind count event
		if ( counters.count )
		{
			$('em.' + cls( 'counter' ), $m).each(
				function()
				{
					var $c = $(this),
						$s = $('ul' + $c.next().attr( 'href' ), $m);

					$c.bind(
						evt( 'count' ),
						function( e )
						{
							e.preventDefault();
							e.stopPropagation();

							var $lis = $s.children()
								.not( '.' + cls( 'label' ) )
								.not( '.' + cls( 'subtitle' ) )
								.not( '.' + cls( 'noresult' ) )
								.not( '.' + cls( 'noresults' ) );

							$c.html( $lis.length );
						}
					);
				}
			).trigger( evt( 'count' ) );
		}
	}
	function _initSearch( $m, search )
	{
		if ( search.add )
		{
			var $s = $( '<div class="' + cls( 'search' ) + '" />' ).prependTo( $m );
			$s.append( '<input placeholder="' + search.placeholder + '" type="text" autocomplete="off" />' );

			if ( search.noResults )
			{
				$('ul', $m).not( '.' + cls( 'submenu' ) ).append( '<li class="' + cls( 'noresults' ) + '">' + search.noResults + '</li>' );
			}
		}

		if ( search.search )
		{
			var $s = $('div.' + cls( 'search' ), $m),
				$i = $('input', $s);

			var $labels = $('li.' + cls( 'label' ), $m),
				$counters = $('em.' + cls( 'counter' ), $m),
				$items = $('li', $m)
					.not( '.' + cls( 'subtitle' ) )
					.not( '.' + cls( 'label' ) )
					.not( '.' + cls( 'noresults' ) );

			var _searchText = '> a';
			if ( !search.showLinksOnly )
			{
				_searchText += ', > span';
			}

			$i.bind(
				evt( 'keyup' ),
				function()
				{
					var query = $i.val().toLowerCase();

					//	search through items
					$items.add( $labels ).addClass( cls( 'noresult' ) );
					$items.each(
						function()
						{
							var $t = $(this);
							if ( $(_searchText, $t).text().toLowerCase().indexOf( query ) > -1 )
							{
								$t.add( $t.prevAll( '.' + cls( 'label' ) ).first() ).removeClass( cls( 'noresult' ) );
							}
						}
					);

					//	update parent for submenus
					$( $('ul.' + cls( 'submenu' ), $m).get().reverse() ).each(
						function()
						{
							var $t = $(this),
								$p = null,
								id = $t.attr( 'id' ),
								$i = $t.find( 'li' )
									.not( '.' + cls( 'subtitle' ) )
									.not( '.' + cls( 'label' ) )
									.not( '.' + cls( 'noresult' ) );

							if ( id && id.length )
							{
								var $p = $('a.' + cls( 'subopen' ), $m).filter( '[href="#' + id + '"]' ).parent();
							}
							if ( $i.length )
							{
								if ( $p )
								{
									$p.removeClass( cls( 'noresult' ) );
									$p.removeClass( cls( 'nosubresult' ) );
								}
							}
							else
							{
								$t.trigger( evt( 'close' ) );
								if ( $p )
								{
									$p.addClass( cls( 'nosubresult' ) );
								}
							}
						}
					);

					//	show/hide no results message
					$m[ $items.not( '.' + cls( 'noresult' ) ).length ? 'removeClass' : 'addClass' ]( cls( 'noresults' ) );

					//	update counters
					$counters.trigger( evt( 'count' ) );
				}
			);
		}
	}
	function _initOpenClose( $m, $p )
	{
		//	toggle menu
		var id = $m.attr( 'id' );
		if ( id && id.length )
		{
			click( 'a[href="#' + id + '"]',
				function()
				{
					$m.trigger( evt( 'toggle' ) );
				}
			);
		}

		//	close menu
		var id = $p.attr( 'id' );
		if ( id && id.length )
		{
			click( 'a[href="#' + id + '"]',
				function()
				{
					$m.trigger( evt( 'close' ) );
				}
			);
		}

		//	open submenu
		click( $('a.' + cls( 'subopen' ) + ', ' + 'a.' + cls( 'subclose' ), $m),
			function()
			{
				$( $(this).attr( 'href' ) ).trigger( evt( 'toggle' ) );
			}
		);
	}

	function openMenu( $m, p )
	{
		var _scrollTop = findScrollTop();

		//	resize page
		var _w = 0;
		$wndw.bind(
			evt( 'resize' ),
			function( e )
			{
				var nw = $wndw.width();
				if ( nw != _w )
				{
					_w = nw;
					$page
						.attr( 'style', $page.data( dta( 'style' ) ) )
						.width( nw );
				}
			}
		);

		//	store style and position
		$page
			.data( dta( 'style' ), $page.attr( 'style' ) || '' )
			.data( dta( 'scrollTop' ), _scrollTop )
			.width( $page.outerWidth() )
			.css( 'top', -_scrollTop );

		//	open
		$m.addClass( cls( 'opened' ) );
		$html.addClass( cls( 'opened' ) ).addClass( cls( p ) );
		setTimeout(
			function()
			{
				//	opened
				$html.addClass( cls( 'opening' ) );
			}, 25
		);

		return 'open';
	}
	function closeMenu( $m, o )
	{
		//	close
		$html.removeClass( cls( 'opening' ) );
		setTimeout(
			function()
			{
				//	closed
				$html.removeClass( cls( 'opened' ) ).removeClass( cls( o.position ) );
				$m.removeClass( cls( 'opened' ) );

				//	restore style and position
				$page.attr( 'style', $page.data( dta( 'style' ) ) );
				$wndw.unbind( evt( 'resize' ) );
				if ( $scrollTopNode )
				{
					$scrollTopNode.scrollTop( $page.data( dta( 'scrollTop' ) ) );
				}
			}, o.configuration.slideDuration + 25
		);

		return 'close';
	}

	function openSubmenuHorizontal( $t, o )
	{
		$t.prevAll( 'ul' ).addClass( cls( 'subopened' ) );
		$t.nextAll( 'ul' ).removeClass( cls( 'subopened' ) );
		$t.removeClass( cls( 'subopened' ) ).addClass( cls( 'opened' ) );
		setTimeout(
			function()
			{
				$t.nextAll( 'ul' ).removeClass( cls( 'opened' ) );
			}, o.configuration.slideDuration + 25
		);
		return 'open';
	}
	function closeSubmenuHorizontal( $t, o )
	{
		$t.prevAll( 'ul.' + cls( 'opened' ) ).first().trigger( evt( 'open' ) );
		return 'close';
	}

	function findScrollTop()
	{
		if ( !$scrollTopNode )
		{
			if ( $('html').scrollTop() != 0 )
			{
				$scrollTopNode = $('html');
			}
			else if ( $('body').scrollTop() != 0 )
			{
				$scrollTopNode = $('body');
			}
		}
		return ( $scrollTopNode ) ? $scrollTopNode.scrollTop() - 1 : 0;
	}

	function click( $b, fn )
	{
		if ( typeof $b == 'string' )
		{
			$b = $( $b );
		}
		$b.bind(
			evt( 'click' ),
			function( e )
			{
				e.preventDefault();
				e.stopPropagation();

				fn.call( this, e );
			}
		);
	}

	function cls( c )
	{
		return 'mmenu-' + c;
	}
	function dta( d )
	{
		return 'mmenu-' + d;
	}
	function evt( e )
	{
		return e + '.mmenu';
	}

})( jQuery );