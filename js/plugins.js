// All JS/jQuery plugins go here

/*!
 * urlInternal - v1.0 - 10/7/2009
 * http://benalman.com/projects/jquery-urlinternal-plugin/
 * 
 * Copyright © 2009 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */

// Script: jQuery urlInternal: Easily test URL internal-, external or fragment-ness
// 
// *Version: 1.0, Last updated: 10/7/2009*
// 
// Project Home - http://benalman.com/projects/jquery-urlinternal-plugin/
// GitHub       - http://github.com/cowboy/jquery-urlinternal/
// Source       - http://github.com/cowboy/jquery-urlinternal/raw/master/jquery.ba-urlinternal.js
// (Minified)   - http://github.com/cowboy/jquery-urlinternal/raw/master/jquery.ba-urlinternal.min.js (1.7kb)
// 
// About: License
// 
// Copyright © 2009 "Cowboy" Ben Alman,
// Dual licensed under the MIT and GPL licenses.
// http://benalman.com/about/license/
// 
// About: Examples
// 
// This working example, complete with fully commented code, illustrates a few
// ways in which this plugin can be used.
// 
// http://benalman.com/code/projects/jquery-urlinternal/examples/urlinternal/
// 
// About: Support and Testing
// 
// Information about what version or versions of jQuery this plugin has been
// tested with, what browsers it has been tested in, and where the unit tests
// reside (so you can test it yourself).
// 
// jQuery Versions - 1.3.2
// Browsers Tested - Internet Explorer 6-8, Firefox 2-3.7, Safari 3-4, Chrome, Opera 9.6-10.
// Unit Tests      - http://benalman.com/code/projects/jquery-urlinternal/unit/
// 
// About: Release History
// 
// 1.0 - (10/7/2009) Initial release

(function($){
  '$:nomunge'; // Used by YUI compressor.
  
  // Some convenient shortcuts.
  var undefined,
    TRUE = !0,
    FALSE = !1,
    loc = window.location,
    aps = Array.prototype.slice,
    
    matches = loc.href.match( /^((https?:\/\/.*?\/)?[^#]*)#?.*$/ ),
    loc_fragbase = matches[1] + '#',
    loc_hostbase = matches[2],
    
    // Method references.
    jq_elemUrlAttr,
    jq_urlInternalHost,
    jq_urlInternalRegExp,
    jq_isUrlInternal,
    jq_isUrlExternal,
    jq_isUrlFragment,
    
    // Reused strings.
    str_elemUrlAttr = 'elemUrlAttr',
    str_href = 'href',
    str_src = 'src',
    str_urlInternal = 'urlInternal',
    str_urlExternal = 'urlExternal',
    str_urlFragment = 'urlFragment',
    
    url_regexp,
    
    // Used by jQuery.elemUrlAttr.
    elemUrlAttr_cache = {};
  
  // Why write the same function twice? Let's curry! Mmmm, curry..
  
  function curry( func ) {
    var args = aps.call( arguments, 1 );
    
    return function() {
      return func.apply( this, args.concat( aps.call( arguments ) ) );
    };
  };
  
  // Section: Methods
  // 
  // Method: jQuery.isUrlInternal
  // 
  // Test whether or not a URL is internal. Non-navigating URLs (ie. #anchor,
  // javascript:, mailto:, news:, tel:, im: or non-http/https protocol://
  // links) are not considered internal.
  // 
  // Usage:
  // 
  // > jQuery.isUrlInternal( url );
  // 
  // Arguments:
  // 
  //   url - (String) a URL to test the internal-ness of.
  // 
  // Returns:
  // 
  //  (Boolean) true if the URL is internal, false if external, or undefined if
  //  the URL is non-navigating.
  
  $.isUrlInternal = jq_isUrlInternal = function( url ) {
    
    // non-navigating: url is nonexistent or a fragment
    if ( !url || jq_isUrlFragment( url ) ) { return undefined; }
    
    // internal: url is absolute-but-internal (see $.urlInternalRegExp)
    if ( url_regexp.test(url) ) { return TRUE; }
    
    // external: url is absolute (begins with http:// or https:// or //)
    if ( /^(?:https?:)?\/\//i.test(url) ) { return FALSE; }
    
    // non-navigating: url begins with scheme:
    if ( /^[a-z\d.-]+:/i.test(url) ) { return undefined; }
    
    return TRUE;
  };
  
  // Method: jQuery.isUrlExternal
  // 
  // Test whether or not a URL is external. Non-navigating URLs (ie. #anchor,
  // mailto:, javascript:, or non-http/https protocol:// links) are not
  // considered external.
  // 
  // Usage:
  // 
  // > jQuery.isUrlExternal( url );
  // 
  // Arguments:
  // 
  //   url - (String) a URL to test the external-ness of.
  // 
  // Returns:
  // 
  //  (Boolean) true if the URL is external, false if internal, or undefined if
  //  the URL is non-navigating.
  
  $.isUrlExternal = jq_isUrlExternal = function( url ) {
    var result = jq_isUrlInternal( url );
    
    return typeof result === 'boolean'
      ? !result
      : result;
  };
  
  // Method: jQuery.isUrlFragment
  // 
  // Test whether or not a URL is a fragment in the context of the current page,
  // meaning the URL can either begin with # or be a partial URL or full URI,
  // but when it is navigated to, only the document.location.hash will change,
  // and the page will not reload.
  // 
  // Usage:
  // 
  // > jQuery.isUrlFragment( url );
  // 
  // Arguments:
  // 
  //   url - (String) a URL to test the fragment-ness of.
  // 
  // Returns:
  // 
  //  (Boolean) true if the URL is a fragment, false otherwise.
  
  $.isUrlFragment = jq_isUrlFragment = function( url ) {
    var matches = ( url || '' ).match( /^([^#]?)([^#]*#).*$/ );
    
    // url *might* be a fragment, since there were matches.
    return !!matches && (
      
      // url is just a fragment.
      matches[2] === '#'
      
      // url is absolute and contains a fragment, but is otherwise the same URI.
      || url.indexOf( loc_fragbase ) === 0
      
      // url is relative, begins with '/', contains a fragment, and is otherwise
      // the same URI.
      || ( matches[1] === '/' ? loc_hostbase + matches[2] === loc_fragbase
      
      // url is relative, but doesn't begin with '/', contains a fragment, and
      // is otherwise the same URI. This isn't even remotely efficient, but it's
      // significantly less code than parsing everything. Besides, it will only
      // even be tested on url values that contain '#', aren't absolute, and
      // don't begin with '/', which is not going to be many of them.
      : !/^https?:\/\//i.test( url ) && $('<a href="' + url + '"/>')[0].href.indexOf( loc_fragbase ) === 0 )
    );
  };
  
  // Method: jQuery.fn.urlInternal
  // 
  // Filter a jQuery collection of elements, returning only elements that have
  // an internal URL (as determined by <jQuery.isUrlInternal>). If URL cannot
  // be determined, remove the element from the collection.
  // 
  // Usage:
  // 
  // > jQuery('selector').urlInternal( [ attr ] );
  // 
  // Arguments:
  // 
  //  attr - (String) Optional name of an attribute that will contain a URL to
  //    test internal-ness against. See <jQuery.elemUrlAttr> for a list of
  //    default attributes.
  // 
  // Returns:
  // 
  //  (jQuery) A filtered jQuery collection of elements.
  
  // Method: jQuery.fn.urlExternal
  // 
  // Filter a jQuery collection of elements, returning only elements that have
  // an external URL (as determined by <jQuery.isUrlExternal>). If URL cannot
  // be determined, remove the element from the collection.
  // 
  // Usage:
  // 
  // > jQuery('selector').urlExternal( [ attr ] );
  // 
  // Arguments:
  // 
  //  attr - (String) Optional name of an attribute that will contain a URL to
  //    test external-ness against. See <jQuery.elemUrlAttr> for a list of
  //    default attributes.
  // 
  // Returns:
  // 
  //  (jQuery) A filtered jQuery collection of elements.
  
  // Method: jQuery.fn.urlFragment
  // 
  // Filter a jQuery collection of elements, returning only elements that have
  // an fragment URL (as determined by <jQuery.isUrlFragment>). If URL cannot
  // be determined, remove the element from the collection.
  // 
  // Note that in most browsers, selecting $("a[href^=#]") is reliable, but this
  // doesn't always work in IE6/7! In order to properly test whether a URL
  // attribute's value is a fragment in the context of the current page, you can
  // either make your selector a bit more complicated.. or use .urlFragment!
  // 
  // Usage:
  // 
  // > jQuery('selector').urlFragment( [ attr ] );
  // 
  // Arguments:
  // 
  //  attr - (String) Optional name of an attribute that will contain a URL to
  //    test external-ness against. See <jQuery.elemUrlAttr> for a list of
  //    default attributes.
  // 
  // Returns:
  // 
  //  (jQuery) A filtered jQuery collection of elements.
  
  function fn_filter( str, attr ) {
    return this.filter( ':' + str + (attr ? '(' + attr + ')' : '') );
  };
  
  $.fn[ str_urlInternal ] = curry( fn_filter, str_urlInternal );
  $.fn[ str_urlExternal ] = curry( fn_filter, str_urlExternal );
  $.fn[ str_urlFragment ] = curry( fn_filter, str_urlFragment );
  
  // Section: Selectors
  // 
  // Selector: :urlInternal
  // 
  // Filter a jQuery collection of elements, returning only elements that have
  // an internal URL (as determined by <jQuery.isUrlInternal>). If URL cannot
  // be determined, remove the element from the collection.
  // 
  // Usage:
  // 
  // > jQuery('selector').filter(':urlInternal');
  // > jQuery('selector').filter(':urlInternal(attr)');
  // 
  // Arguments:
  // 
  //  attr - (String) Optional name of an attribute that will contain a URL to
  //    test internal-ness against. See <jQuery.elemUrlAttr> for a list of
  //    default attributes.
  // 
  // Returns:
  // 
  //  (jQuery) A filtered jQuery collection of elements.
  
  // Selector: :urlExternal
  // 
  // Filter a jQuery collection of elements, returning only elements that have
  // an external URL (as determined by <jQuery.isUrlExternal>). If URL cannot
  // be determined, remove the element from the collection.
  // 
  // Usage:
  // 
  // > jQuery('selector').filter(':urlExternal');
  // > jQuery('selector').filter(':urlExternal(attr)');
  // 
  // Arguments:
  // 
  //  attr - (String) Optional name of an attribute that will contain a URL to
  //    test external-ness against. See <jQuery.elemUrlAttr> for a list of
  //    default attributes.
  // 
  // Returns:
  // 
  //  (jQuery) A filtered jQuery collection of elements.
  
  // Selector: :urlFragment
  // 
  // Filter a jQuery collection of elements, returning only elements that have
  // an fragment URL (as determined by <jQuery.isUrlFragment>). If URL cannot
  // be determined, remove the element from the collection.
  // 
  // Note that in most browsers, selecting $("a[href^=#]") is reliable, but this
  // doesn't always work in IE6/7! In order to properly test whether a URL
  // attribute's value is a fragment in the context of the current page, you can
  // either make your selector a bit more complicated.. or use :urlFragment!
  // 
  // Usage:
  // 
  // > jQuery('selector').filter(':urlFragment');
  // > jQuery('selector').filter(':urlFragment(attr)');
  // 
  // Arguments:
  // 
  //  attr - (String) Optional name of an attribute that will contain a URL to
  //    test fragment-ness against. See <jQuery.elemUrlAttr> for a list of
  //    default attributes.
  // 
  // Returns:
  // 
  //  (jQuery) A filtered jQuery collection of elements.
  
  function fn_selector( func, elem, i, match ) {
    var a = match[3] || jq_elemUrlAttr()[ ( elem.nodeName || '' ).toLowerCase() ] || '';
    
    return a ? !!func( elem.getAttribute( a ) ) : FALSE;
  };
  
  $.expr[':'][ str_urlInternal ] = curry( fn_selector, jq_isUrlInternal );
  $.expr[':'][ str_urlExternal ] = curry( fn_selector, jq_isUrlExternal );
  $.expr[':'][ str_urlFragment ] = curry( fn_selector, jq_isUrlFragment );
  
  // Section: Support methods
  // 
  // Method: jQuery.elemUrlAttr
  // 
  // Get the internal "Default URL attribute per tag" list, or augment the list
  // with additional tag-attribute pairs, in case the defaults are insufficient.
  // 
  // In the <jQuery.fn.urlInternal> and <jQuery.fn.urlExternal> methods, as well
  // as the <:urlInternal> and <:urlExternal> selectors, this list is used to
  // determine which attribute contains the URL to be modified, if an "attr"
  // param is not specified.
  // 
  // Default Tag-Attribute List:
  // 
  //  a      - href
  //  base   - href
  //  iframe - src
  //  img    - src
  //  input  - src
  //  form   - action
  //  link   - href
  //  script - src
  // 
  // Usage:
  // 
  // > jQuery.elemUrlAttr( [ tag_attr ] );
  // 
  // Arguments:
  // 
  //  tag_attr - (Object) An object containing a list of tag names and their
  //    associated default attribute names in the format { tag: 'attr', … } to
  //    be merged into the internal tag-attribute list.
  // 
  // Returns:
  // 
  //  (Object) An object containing all stored tag-attribute values.
  
  // Only define function and set defaults if function doesn't already exist, as
  // the jQuery BBQ plugin will provide this method as well.
  $[ str_elemUrlAttr ] || ($[ str_elemUrlAttr ] = function( obj ) {
    return $.extend( elemUrlAttr_cache, obj );
  })({
    a: str_href,
    base: str_href,
    iframe: str_src,
    img: str_src,
    input: str_src,
    form: 'action',
    link: str_href,
    script: str_src
  });
  
  jq_elemUrlAttr = $[ str_elemUrlAttr ];
  
  // Method: jQuery.urlInternalHost
  // 
  // Constructs the regular expression that matches an absolute-but-internal
  // URL from the current page's protocol, hostname and port, allowing for any
  // number of optional hostnames. For example, if the current page is
  // http://benalman.com/test or http://www.benalman.com/test, specifying an
  // argument of "www" would yield this pattern:
  // 
  // > /^(?:http:)?\/\/(?:(?:www)\.)?benalman.com\//i
  // 
  // This pattern will match URLs beginning with both http://benalman.com/ and
  // http://www.benalman.com/. If the current page is http://benalman.com/test,
  // http://www.benalman.com/test or http://foo.benalman.com/test, specifying
  // arguments "www", "foo" would yield this pattern:
  // 
  // > /^(?:http:)?\/\/(?:(?:www|foo)\.)?benalman.com\//i
  // 
  // This pattern will match URLs beginning with http://benalman.com/,
  // http://www.benalman.com/ and http://foo.benalman.com/.
  // 
  // Not specifying any alt_hostname will disable any alt-hostname matching.
  // 
  // Note that the plugin is initialized by default to an alt_hostname of "www".
  // Should you need more control, <jQuery.urlInternalRegExp> may be used to
  // completely customize the absolute-but-internal matching pattern.
  // 
  // Usage:
  // 
  // > jQuery.urlInternalHost( [ alt_hostname [, alt_hostname ] … ] );
  // 
  // Arguments:
  // 
  //  alt_hostname - (String) An optional alternate hostname to use when testing
  //    URL absolute-but-internal-ness. 
  // 
  // Returns:
  // 
  //  (RegExp) The absolute-but-internal pattern, as a RegExp.
  
  $.urlInternalHost = jq_urlInternalHost = function( alt_hostname ) {
    alt_hostname = alt_hostname
      ? '(?:(?:' + Array.prototype.join.call( arguments, '|' ) + ')\\.)?'
      : '';
    
    var re = new RegExp( '^' + alt_hostname + '(.*)', 'i' ),
      pattern = '^(?:' + loc.protocol + ')?//'
        + loc.hostname.replace(re, alt_hostname + '$1').replace( /\\?\./g, '\\.' )
        + (loc.port ? ':' + loc.port : '') + '/';
    
    return jq_urlInternalRegExp( pattern );
  };
    
  // Method: jQuery.urlInternalRegExp
  // 
  // Set or get the regular expression that matches an absolute-but-internal
  // URL.
  // 
  // Usage:
  // 
  // > jQuery.urlInternalRegExp( [ re ] );
  // 
  // Arguments:
  // 
  //  re - (String or RegExp) The regular expression pattern. If not passed,
  //    nothing is changed.
  // 
  // Returns:
  // 
  //  (RegExp) The absolute-but-internal pattern, as a RegExp.
  
  $.urlInternalRegExp = jq_urlInternalRegExp = function( re ) {
    if ( re ) {
      url_regexp = typeof re === 'string'
        ? new RegExp( re, 'i' )
        : re;
    }
    
    return url_regexp;
  };
  
  // Initialize url_regexp with a reasonable default.
  jq_urlInternalHost( 'www' );
  
})(jQuery);


/*
 * jQuery Address Plugin v1.4
 * http://www.asual.com/jquery/address/
 *
 * Copyright © 2009-2010 Rostislav Hristov
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * Date: 2011-05-04 14:22:12 +0300 (Wed, 04 May 2011)
 */
(function ($) {

    $.address = (function () {

        var _trigger = function(name) {
                $($.address).trigger(
                    $.extend($.Event(name), 
                        (function() {
                            var parameters = {},
                                parameterNames = $.address.parameterNames();
                            for (var i = 0, l = parameterNames.length; i < l; i++) {
                                parameters[parameterNames[i]] = $.address.parameter(parameterNames[i]);
                            }
                            return {
                                value: $.address.value(),
                                path: $.address.path(),
                                pathNames: $.address.pathNames(),
                                parameterNames: parameterNames,
                                parameters: parameters,
                                queryString: $.address.queryString()
                            };
                        }).call($.address)
                    )
                );
            },
            _bind = function(value, data, fn) {
                $().bind.apply($($.address), Array.prototype.slice.call(arguments));
                return $.address;
            },
            _supportsState = function() {
                return (_h.pushState && _opts.state !== UNDEFINED);
            },
            _hrefState = function() {
                return ('/' + _l.pathname.replace(new RegExp(_opts.state), '') + 
                    _l.search + (_hrefHash() ? '#' + _hrefHash() : '')).replace(_re, '/');
            },
            _hrefHash = function() {
                var index = _l.href.indexOf('#');
                return index != -1 ? _crawl(_l.href.substr(index + 1), FALSE) : '';
            },
            _href = function() {
                return _supportsState() ? _hrefState() : _hrefHash();
            },
            _window = function() {
                try {
                    return top.document !== UNDEFINED ? top : window;
                } catch (e) { 
                    return window;
                }
            },
            _js = function() {
                return 'javascript';
            },
            _strict = function(value) {
                value = value.toString();
                return (_opts.strict && value.substr(0, 1) != '/' ? '/' : '') + value;
            },
            _crawl = function(value, direction) {
                if (_opts.crawlable && direction) {
                    return (value !== '' ? '!' : '') + value;
                }
                return value.replace(/^\!/, '');
            },
            _cssint = function(el, value) {
                return parseInt(el.css(value), 10);
            },
            _search = function(el) {
                var url, s;
                for (var i = 0, l = el.childNodes.length; i < l; i++) {
                    try {
                        if ('src' in el.childNodes[i] && el.childNodes[i].src) {
                            url = String(el.childNodes[i].src);
                        }
                    } catch (e) {
                        // IE Invalid pointer problem with base64 encoded images
                    }
                    s = _search(el.childNodes[i]);
                    if (s) {
                        url = s;
                    }
                }
                return url;
            },
            _listen = function() {
                if (!_silent) {
                    var hash = _href(),
                        diff = _value != hash;
                    if (diff) {
                        if (_msie && _version < 7) {
                            _l.reload();
                        } else {
                            if (_msie && _version < 8 && _opts.history) {
                                _st(_html, 50);
                            }
                            _value = hash;
                            _update(FALSE);
                        }
                    }
                }
            },
            _update = function(internal) {
                _trigger(CHANGE);
                _trigger(internal ? INTERNAL_CHANGE : EXTERNAL_CHANGE);
                _st(_track, 10);
            },
            _track = function() {
                if (_opts.tracker !== 'null' && _opts.tracker !== null) {
                    var fn = $.isFunction(_opts.tracker) ? _opts.tracker : _t[_opts.tracker],
                        value = (_l.pathname + _l.search + 
                                ($.address && !_supportsState() ? $.address.value() : ''))
                                .replace(/\/\//, '/').replace(/^\/$/, '');
                    if ($.isFunction(fn)) {
                        fn(value);
                    } else if ($.isFunction(_t.urchinTracker)) {
                        _t.urchinTracker(value);
                    } else if (_t.pageTracker !== UNDEFINED && $.isFunction(_t.pageTracker._trackPageview)) {
                        _t.pageTracker._trackPageview(value);
                    } else if (_t._gaq !== UNDEFINED && $.isFunction(_t._gaq.push)) {
                        _t._gaq.push(['_trackPageview', decodeURI(value)]);
                    }
                }
            },
            _html = function() {
                var src = _js() + ':' + FALSE + ';document.open();document.writeln(\'<html><head><title>' + 
                    _d.title.replace('\'', '\\\'') + '</title><script>var ' + ID + ' = "' + encodeURIComponent(_href()) + 
                    (_d.domain != _l.hostname ? '";document.domain="' + _d.domain : '') + 
                    '";</' + 'script></head></html>\');document.close();';
                if (_version < 7) {
                    _frame.src = src;
                } else {
                    _frame.contentWindow.location.replace(src);
                }
            },
            _options = function() {
                if (_url && _qi != -1) {
                    var param, params = _url.substr(_qi + 1).split('&');
                    for (i = 0; i < params.length; i++) {
                        param = params[i].split('=');
                        if (/^(autoUpdate|crawlable|history|strict|wrap)$/.test(param[0])) {
                            _opts[param[0]] = (isNaN(param[1]) ? /^(true|yes)$/i.test(param[1]) : (parseInt(param[1], 10) !== 0));
                        }
                        if (/^(state|tracker)$/.test(param[0])) {
                            _opts[param[0]] = param[1];
                        }
                    }
                    _url = null;
                }
                _value = _href();
            },
            _load = function() {
                if (!_loaded) {
                    _loaded = TRUE;
                    _options();
                    var complete = function() {
                            _enable.call(this);
                            _unescape.call(this);
                        },
                        body = $('body').ajaxComplete(complete);
                    complete();
                    if (_opts.wrap) {
                        var wrap = $('body > *')
                            .wrapAll('<div style="padding:' + 
                                (_cssint(body, 'marginTop') + _cssint(body, 'paddingTop')) + 'px ' + 
                                (_cssint(body, 'marginRight') + _cssint(body, 'paddingRight')) + 'px ' + 
                                (_cssint(body, 'marginBottom') + _cssint(body, 'paddingBottom')) + 'px ' + 
                                (_cssint(body, 'marginLeft') + _cssint(body, 'paddingLeft')) + 'px;" />')
                            .parent()
                            .wrap('<div id="' + ID + '" style="height:100%;overflow:auto;position:relative;' + 
                                (_webkit && !window.statusbar.visible ? 'resize:both;' : '') + '" />');
                        $('html, body')
                            .css({
                                height: '100%',
                                margin: 0,
                                padding: 0,
                                overflow: 'hidden'
                            });
                        if (_webkit) {
                            $('<style type="text/css" />')
                                .appendTo('head')
                                .text('#' + ID + '::-webkit-resizer { background-color: #fff; }');
                        }
                    }
                    if (_msie && _version < 8) {
                        var frameset = _d.getElementsByTagName('frameset')[0];
                        _frame = _d.createElement((frameset ? '' : 'i') + 'frame');
                        if (frameset) {
                            frameset.insertAdjacentElement('beforeEnd', _frame);
                            frameset[frameset.cols ? 'cols' : 'rows'] += ',0';
                            _frame.noResize = TRUE;
                            _frame.frameBorder = _frame.frameSpacing = 0;
                        } else {
                            _frame.style.display = 'none';
                            _frame.style.width = _frame.style.height = 0;
                            _frame.tabIndex = -1;
                            _d.body.insertAdjacentElement('afterBegin', _frame);
                        }
                        _st(function() {
                            $(_frame).bind('load', function() {
                                var win = _frame.contentWindow;
                                _value = win[ID] !== UNDEFINED ? win[ID] : '';
                                if (_value != _href()) {
                                    _update(FALSE);
                                    _l.hash = _crawl(_value, TRUE);
                                }
                            });
                            if (_frame.contentWindow[ID] === UNDEFINED) {
                                _html();
                            }
                        }, 50);
                    }

                    _st(function() {
                        _trigger('init');
                        _update(FALSE);
                    }, 1);

                    if (!_supportsState()) {
                        if ((_msie && _version > 7) || (!_msie && ('on' + HASH_CHANGE) in _t)) {
                            if (_t.addEventListener) {
                                _t.addEventListener(HASH_CHANGE, _listen, FALSE);
                            } else if (_t.attachEvent) {
                                _t.attachEvent('on' + HASH_CHANGE, _listen);
                            }
                        } else {
                            _si(_listen, 50);
                        }
                    }
                }
            },
            _enable = function() {
                var el, 
                    elements = $('a'), 
                    length = elements.size(),
                    delay = 1,
                    index = -1,
                    fn = function() {
                        if (++index != length) {
                            el = $(elements.get(index));
                            if (el.is('[rel*="address:"]')) {
                                el.address();
                            }
                            _st(fn, delay);
                        }
                    };
                _st(fn, delay);
            },
            _popstate = function() {
                if (_value != _href()) {
                    _value = _href();
                    _update(FALSE);
                }
            },
            _unload = function() {
                if (_t.removeEventListener) {
                    _t.removeEventListener(HASH_CHANGE, _listen, FALSE);
                } else if (_t.detachEvent) {
                    _t.detachEvent('on' + HASH_CHANGE, _listen);
                }
            },
            _unescape = function() {
                if (_opts.crawlable) {
                    var base = _l.pathname.replace(/\/$/, ''),
                        fragment = '_escaped_fragment_';
                    if ($('body').html().indexOf(fragment) != -1) {
                        $('a[href]:not([href^=http]), a[href*="' + document.domain + '"]').each(function() {
                            var href = $(this).attr('href').replace(/^http:/, '').replace(new RegExp(base + '/?$'), '');
                            if (href === '' || href.indexOf(fragment) != -1) {
                                $(this).attr('href', '#' + href.replace(new RegExp('/(.*)\\?' + fragment + '=(.*)$'), '!$2'));
                            }
                        });
                    }
                }
            },
            UNDEFINED,
            ID = 'jQueryAddress',
            STRING = 'string',
            HASH_CHANGE = 'hashchange',
            INIT = 'init',
            CHANGE = 'change',
            INTERNAL_CHANGE = 'internalChange',
            EXTERNAL_CHANGE = 'externalChange',
            TRUE = true,
            FALSE = false,
            _opts = {
                autoUpdate: TRUE, 
                crawlable: FALSE,
                history: TRUE, 
                strict: TRUE,
                wrap: FALSE
            },
            _browser = $.browser, 
            _version = parseFloat($.browser.version),
            _mozilla = _browser.mozilla,
            _msie = _browser.msie,
            _opera = _browser.opera,
            _webkit = _browser.webkit || _browser.safari,
            _supported = FALSE,
            _t = _window(),
            _d = _t.document,
            _h = _t.history, 
            _l = _t.location,
            _si = setInterval,
            _st = setTimeout,
            _re = /\/{2,9}/g,
            _agent = navigator.userAgent,            
            _frame,
            _form,
            _url = _search(document),
            _qi = _url ? _url.indexOf('?') : -1,
            _title = _d.title, 
            _silent = FALSE,
            _loaded = FALSE,
            _justset = TRUE,
            _juststart = TRUE,
            _updating = FALSE,
            _listeners = {}, 
            _value = _href();
            
        if (_msie) {
            _version = parseFloat(_agent.substr(_agent.indexOf('MSIE') + 4));
            if (_d.documentMode && _d.documentMode != _version) {
                _version = _d.documentMode != 8 ? 7 : 8;
            }
            var pc = _d.onpropertychange;
            _d.onpropertychange = function() {
                if (pc) {
                    pc.call(_d);
                }
                if (_d.title != _title && _d.title.indexOf('#' + _href()) != -1) {
                    _d.title = _title;
                }
            };
        }
        
        _supported = 
            (_mozilla && _version >= 1) || 
            (_msie && _version >= 6) ||
            (_opera && _version >= 9.5) ||
            (_webkit && _version >= 523);
            
        if (_supported) {
            if (_opera) {
                history.navigationMode = 'compatible';
            }
            if (document.readyState == 'complete') {
                var interval = setInterval(function() {
                    if ($.address) {
                        _load();
                        clearInterval(interval);
                    }
                }, 50);
            } else {
                _options();
                $(_load);
            }
            $(window).bind('popstate', _popstate).bind('unload', _unload);            
        } else if (!_supported && _hrefHash() !== '') {
            _l.replace(_l.href.substr(0, _l.href.indexOf('#')));
        } else {
            _track();
        }

        return {
            bind: function(type, data, fn) {
                return _bind(type, data, fn);
            },
            init: function(fn) {
                return _bind(INIT, fn);
            },
            change: function(fn) {
                return _bind(CHANGE, fn);
            },
            internalChange: function(fn) {
                return _bind(INTERNAL_CHANGE, fn);
            },
            externalChange: function(fn) {
                return _bind(EXTERNAL_CHANGE, fn);
            },
            baseURL: function() {
                var url = _l.href;
                if (url.indexOf('#') != -1) {
                    url = url.substr(0, url.indexOf('#'));
                }
                if (/\/$/.test(url)) {
                    url = url.substr(0, url.length - 1);
                }
                return url;
            },
            autoUpdate: function(value) {
                if (value !== UNDEFINED) {
                    _opts.autoUpdate = value;
                    return this;
                }
                return _opts.autoUpdate;
            },
            crawlable: function(value) {
                if (value !== UNDEFINED) {
                    _opts.crawlable = value;
                    return this;
                }
                return _opts.crawlable;
            },
            history: function(value) {
                if (value !== UNDEFINED) {
                    _opts.history = value;
                    return this;
                }
                return _opts.history;
            },
            state: function(value) {
                if (value !== UNDEFINED) {
                    _opts.state = value;
                    var hrefState = _hrefState();
                    if (_opts.state !== UNDEFINED) {
                        if (_h.pushState) {
                            if (hrefState.substr(0, 3) == '/#/') {
                                _l.replace(_opts.state.replace(/^\/$/, '') + hrefState.substr(2));
                            }
                        } else if (hrefState != '/' && hrefState.replace(/^\/#/, '') != _hrefHash()) {
                            _st(function() {
                                _l.replace(_opts.state.replace(/^\/$/, '') + '/#' + hrefState);
                            }, 1);
                        }
                    }
                    return this;
                }
                return _opts.state;
            },
            strict: function(value) {
                if (value !== UNDEFINED) {
                    _opts.strict = value;
                    return this;
                }
                return _opts.strict;
            },
            tracker: function(value) {
                if (value !== UNDEFINED) {
                    _opts.tracker = value;
                    return this;
                }
                return _opts.tracker;
            },
            wrap: function(value) {
                if (value !== UNDEFINED) {
                    _opts.wrap = value;
                    return this;
                }
                return _opts.wrap;
            },
            update: function() {
                _updating = TRUE;
                this.value(_value);
                _updating = FALSE;
                return this;
            },
            title: function(value) {
                if (value !== UNDEFINED) {
                    _st(function() {
                        _title = _d.title = value;
                        if (_juststart && _frame && _frame.contentWindow && _frame.contentWindow.document) {
                            _frame.contentWindow.document.title = value;
                            _juststart = FALSE;
                        }
                        if (!_justset && _mozilla) {
                            _l.replace(_l.href.indexOf('#') != -1 ? _l.href : _l.href + '#');
                        }
                        _justset = FALSE;
                    }, 50);
                    return this;
                }
                return _d.title;
            },
            value: function(value) {
                if (value !== UNDEFINED) {
                    value = _strict(value);
                    if (value == '/') {
                        value = '';
                    }
                    if (_value == value && !_updating) {
                        return;
                    }
                    _justset = TRUE;
                    _value = value;
                    if (_opts.autoUpdate || _updating) {
                        _update(TRUE);
                        if (_supportsState()) {
                            _h[_opts.history ? 'pushState' : 'replaceState']({}, '', 
                                    _opts.state.replace(/\/$/, '') + (_value === '' ? '/' : _value));
                        } else {
                            _silent = TRUE;
                            if (_webkit) {
                                if (_opts.history) {
                                    _l.hash = '#' + _crawl(_value, TRUE);
                                } else {
                                    _l.replace('#' + _crawl(_value, TRUE));
                                }
                            } else if (_value != _href()) {
                                if (_opts.history) {
                                    _l.hash = '#' + _crawl(_value, TRUE);
                                } else {
                                    _l.replace('#' + _crawl(_value, TRUE));
                                }
                            }
                            if ((_msie && _version < 8) && _opts.history) {
                                _st(_html, 50);
                            }
                            if (_webkit) {
                                _st(function(){ _silent = FALSE; }, 1);
                            } else {
                                _silent = FALSE;
                            }
                        }
                    }
                    return this;
                }
                if (!_supported) {
                    return null;
                }
                return _strict(_value);
            },
            path: function(value) {
                if (value !== UNDEFINED) {
                    var qs = this.queryString(),
                        hash = this.hash();
                    this.value(value + (qs ? '?' + qs : '') + (hash ? '#' + hash : ''));
                    return this;
                }
                return _strict(_value).split('#')[0].split('?')[0];
            },
            pathNames: function() {
                var path = this.path(),
                    names = path.replace(_re, '/').split('/');
                if (path.substr(0, 1) == '/' || path.length === 0) {
                    names.splice(0, 1);
                }
                if (path.substr(path.length - 1, 1) == '/') {
                    names.splice(names.length - 1, 1);
                }
                return names;
            },
            queryString: function(value) {
                if (value !== UNDEFINED) {
                    var hash = this.hash();
                    this.value(this.path() + (value ? '?' + value : '') + (hash ? '#' + hash : ''));
                    return this;
                }
                var arr = _value.split('?');
                return arr.slice(1, arr.length).join('?').split('#')[0];
            },
            parameter: function(name, value, append) {
                var i, params;
                if (value !== UNDEFINED) {
                    var names = this.parameterNames();
                    params = [];
                    value = value ? value.toString() : '';
                    for (i = 0; i < names.length; i++) {
                        var n = names[i],
                            v = this.parameter(n);
                        if (typeof v == STRING) {
                            v = [v];
                        }
                        if (n == name) {
                            v = (value === null || value === '') ? [] : 
                                (append ? v.concat([value]) : [value]);
                        }
                        for (var j = 0; j < v.length; j++) {
                            params.push(n + '=' + v[j]);
                        }
                    }
                    if ($.inArray(name, names) == -1 && value !== null && value !== '') {
                        params.push(name + '=' + value);
                    }
                    this.queryString(params.join('&'));
                    return this;
                }
                value = this.queryString();
                if (value) {
                    var r = [];
                    params = value.split('&');
                    for (i = 0; i < params.length; i++) {
                        var p = params[i].split('=');
                        if (p[0] == name) {
                            r.push(p.slice(1).join('='));
                        }
                    }
                    if (r.length !== 0) {
                        return r.length != 1 ? r : r[0];
                    }
                }
            },
            parameterNames: function() {
                var qs = this.queryString(),
                    names = [];
                if (qs && qs.indexOf('=') != -1) {
                    var params = qs.split('&');
                    for (var i = 0; i < params.length; i++) {
                        var name = params[i].split('=')[0];
                        if ($.inArray(name, names) == -1) {
                            names.push(name);
                        }
                    }
                }
                return names;
            },
            hash: function(value) {
                if (value !== UNDEFINED) {
                    this.value(_value.split('#')[0] + (value ? '#' + value : ''));
                    return this;
                }
                var arr = _value.split('#');
                return arr.slice(1, arr.length).join('#');                
            }
        };
    })();
    
    $.fn.address = function(fn) {
        if (!$(this).attr('address')) {
            var f = function(e) {
                if (e.shiftKey || e.ctrlKey || e.metaKey) {
                    return true;
                }
                if ($(this).is('a')) {
                    var value = fn ? fn.call(this) : 
                        /address:/.test($(this).attr('rel')) ? $(this).attr('rel').split('address:')[1].split(' ')[0] : 
                        $.address.state() !== undefined && $.address.state() != '/' ? 
                                $(this).attr('href').replace(new RegExp('^(.*' + $.address.state() + '|\\.)'), '') : 
                                $(this).attr('href').replace(/^(#\!?|\.)/, '');
                    $.address.value(value);
                    e.preventDefault();
                }
            };
            $(this).click(f).live('click', f).live('submit', function(e) {
                if ($(this).is('form')) {
                    var action = $(this).attr('action'),
                        value = fn ? fn.call(this) : (action.indexOf('?') != -1 ? action.replace(/&$/, '') : action + '?') + 
                            $(this).serialize();
                    $.address.value(value);
                    e.preventDefault();
                }
            }).attr('address', true);
        }
        return this;
    };
    
})(jQuery);
