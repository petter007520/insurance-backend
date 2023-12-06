(function() {
    var t = this,
    e = t._,
    n = {},
    i = Array.prototype,
    r = Object.prototype,
    s = Function.prototype,
    a = i.push,
    o = i.slice,
    l = i.concat,
    u = r.toString,
    c = r.hasOwnProperty,
    h = i.forEach,
    d = i.map,
    f = i.reduce,
    p = i.reduceRight,
    m = i.filter,
    g = i.every,
    v = i.some,
    y = i.indexOf,
    b = i.lastIndexOf,
    w = Array.isArray,
    $ = Object.keys,
    x = s.bind,
    C = function(t) {
        return t instanceof C ? t: this instanceof C ? void(this._wrapped = t) : new C(t)
    };
    "undefined" != typeof exports ? ("undefined" != typeof module && module.exports && (exports = module.exports = C), exports._ = C) : t._ = C,
    C.VERSION = "1.6.0";
    var k = C.each = C.forEach = function(t, e, i) {
        if (null == t) return t;
        if (h && t.forEach === h) t.forEach(e, i);
        else if (t.length === +t.length) {
            for (var r = 0,
            s = t.length; s > r; r++) if (e.call(i, t[r], r, t) === n) return
        } else for (var a = C.keys(t), r = 0, s = a.length; s > r; r++) if (e.call(i, t[a[r]], a[r], t) === n) return;
        return t
    };
    C.map = C.collect = function(t, e, n) {
        var i = [];
        return null == t ? i: d && t.map === d ? t.map(e, n) : (k(t,
        function(t, r, s) {
            i.push(e.call(n, t, r, s))
        }), i)
    };
    var T = "Reduce of empty array with no initial value";
    C.reduce = C.foldl = C.inject = function(t, e, n, i) {
        var r = arguments.length > 2;
        if (null == t && (t = []), f && t.reduce === f) return i && (e = C.bind(e, i)),
        r ? t.reduce(e, n) : t.reduce(e);
        if (k(t,
        function(t, s, a) {
            r ? n = e.call(i, n, t, s, a) : (n = t, r = !0)
        }), !r) throw new TypeError(T);
        return n
    },
    C.reduceRight = C.foldr = function(t, e, n, i) {
        var r = arguments.length > 2;
        if (null == t && (t = []), p && t.reduceRight === p) return i && (e = C.bind(e, i)),
        r ? t.reduceRight(e, n) : t.reduceRight(e);
        var s = t.length;
        if (s !== +s) {
            var a = C.keys(t);
            s = a.length
        }
        if (k(t,
        function(o, l, u) {
            l = a ? a[--s] : --s,
            r ? n = e.call(i, n, t[l], l, u) : (n = t[l], r = !0)
        }), !r) throw new TypeError(T);
        return n
    },
    C.find = C.detect = function(t, e, n) {
        var i;
        return S(t,
        function(t, r, s) {
            return e.call(n, t, r, s) ? (i = t, !0) : void 0
        }),
        i
    },
    C.filter = C.select = function(t, e, n) {
        var i = [];
        return null == t ? i: m && t.filter === m ? t.filter(e, n) : (k(t,
        function(t, r, s) {
            e.call(n, t, r, s) && i.push(t)
        }), i)
    },
    C.reject = function(t, e, n) {
        return C.filter(t,
        function(t, i, r) {
            return ! e.call(n, t, i, r)
        },
        n)
    },
    C.every = C.all = function(t, e, i) {
        e || (e = C.identity);
        var r = !0;
        return null == t ? r: g && t.every === g ? t.every(e, i) : (k(t,
        function(t, s, a) {
            return (r = r && e.call(i, t, s, a)) ? void 0 : n
        }), !!r)
    };
    var S = C.some = C.any = function(t, e, i) {
        e || (e = C.identity);
        var r = !1;
        return null == t ? r: v && t.some === v ? t.some(e, i) : (k(t,
        function(t, s, a) {
            return r || (r = e.call(i, t, s, a)) ? n: void 0
        }), !!r)
    };
    C.contains = C.include = function(t, e) {
        return null == t ? !1 : y && t.indexOf === y ? -1 != t.indexOf(e) : S(t,
        function(t) {
            return t === e
        })
    },
    C.invoke = function(t, e) {
        var n = o.call(arguments, 2),
        i = C.isFunction(e);
        return C.map(t,
        function(t) {
            return (i ? e: t[e]).apply(t, n)
        })
    },
    C.pluck = function(t, e) {
        return C.map(t, C.property(e))
    },
    C.where = function(t, e) {
        return C.filter(t, C.matches(e))
    },
    C.findWhere = function(t, e) {
        return C.find(t, C.matches(e))
    },
    C.max = function(t, e, n) {
        if (!e && C.isArray(t) && t[0] === +t[0] && t.length < 65535) return Math.max.apply(Math, t);
        var i = -1 / 0,
        r = -1 / 0;
        return k(t,
        function(t, s, a) {
            var o = e ? e.call(n, t, s, a) : t;
            o > r && (i = t, r = o)
        }),
        i
    },
    C.min = function(t, e, n) {
        if (!e && C.isArray(t) && t[0] === +t[0] && t.length < 65535) return Math.min.apply(Math, t);
        var i = 1 / 0,
        r = 1 / 0;
        return k(t,
        function(t, s, a) {
            var o = e ? e.call(n, t, s, a) : t;
            r > o && (i = t, r = o)
        }),
        i
    },
    C.shuffle = function(t) {
        var e, n = 0,
        i = [];
        return k(t,
        function(t) {
            e = C.random(n++),
            i[n - 1] = i[e],
            i[e] = t
        }),
        i
    },
    C.sample = function(t, e, n) {
        return null == e || n ? (t.length !== +t.length && (t = C.values(t)), t[C.random(t.length - 1)]) : C.shuffle(t).slice(0, Math.max(0, e))
    };
    var D = function(t) {
        return null == t ? C.identity: C.isFunction(t) ? t: C.property(t)
    };
    C.sortBy = function(t, e, n) {
        return e = D(e),
        C.pluck(C.map(t,
        function(t, i, r) {
            return {
                "value": t,
                "index": i,
                "criteria": e.call(n, t, i, r)
            }
        }).sort(function(t, e) {
            var n = t.criteria,
            i = e.criteria;
            if (n !== i) {
                if (n > i || void 0 === n) return 1;
                if (i > n || void 0 === i) return - 1
            }
            return t.index - e.index
        }), "value")
    };
    var E = function(t) {
        return function(e, n, i) {
            var r = {};
            return n = D(n),
            k(e,
            function(s, a) {
                var o = n.call(i, s, a, e);
                t(r, o, s)
            }),
            r
        }
    };
    C.groupBy = E(function(t, e, n) {
        C.has(t, e) ? t[e].push(n) : t[e] = [n]
    }),
    C.indexBy = E(function(t, e, n) {
        t[e] = n
    }),
    C.countBy = E(function(t, e) {
        C.has(t, e) ? t[e]++:t[e] = 1
    }),
    C.sortedIndex = function(t, e, n, i) {
        n = D(n);
        for (var r = n.call(i, e), s = 0, a = t.length; a > s;) {
            var o = s + a >>> 1;
            n.call(i, t[o]) < r ? s = o + 1 : a = o
        }
        return s
    },
    C.toArray = function(t) {
        return t ? C.isArray(t) ? o.call(t) : t.length === +t.length ? C.map(t, C.identity) : C.values(t) : []
    },
    C.size = function(t) {
        return null == t ? 0 : t.length === +t.length ? t.length: C.keys(t).length
    },
    C.first = C.head = C.take = function(t, e, n) {
        return null == t ? void 0 : null == e || n ? t[0] : 0 > e ? [] : o.call(t, 0, e)
    },
    C.initial = function(t, e, n) {
        return o.call(t, 0, t.length - (null == e || n ? 1 : e))
    },
    C.last = function(t, e, n) {
        return null == t ? void 0 : null == e || n ? t[t.length - 1] : o.call(t, Math.max(t.length - e, 0))
    },
    C.rest = C.tail = C.drop = function(t, e, n) {
        return o.call(t, null == e || n ? 1 : e)
    },
    C.compact = function(t) {
        return C.filter(t, C.identity)
    };
    var _ = function(t, e, n) {
        return e && C.every(t, C.isArray) ? l.apply(n, t) : (k(t,
        function(t) {
            C.isArray(t) || C.isArguments(t) ? e ? a.apply(n, t) : _(t, e, n) : n.push(t)
        }), n)
    };
    C.flatten = function(t, e) {
        return _(t, e, [])
    },
    C.without = function(t) {
        return C.difference(t, o.call(arguments, 1))
    },
    C.partition = function(t, e) {
        var n = [],
        i = [];
        return k(t,
        function(t) { (e(t) ? n: i).push(t)
        }),
        [n, i]
    },
    C.uniq = C.unique = function(t, e, n, i) {
        C.isFunction(e) && (i = n, n = e, e = !1);
        var r = n ? C.map(t, n, i) : t,
        s = [],
        a = [];
        return k(r,
        function(n, i) { (e ? i && a[a.length - 1] === n: C.contains(a, n)) || (a.push(n), s.push(t[i]))
        }),
        s
    },
    C.union = function() {
        return C.uniq(C.flatten(arguments, !0))
    },
    C.intersection = function(t) {
        var e = o.call(arguments, 1);
        return C.filter(C.uniq(t),
        function(t) {
            return C.every(e,
            function(e) {
                return C.contains(e, t)
            })
        })
    },
    C.difference = function(t) {
        var e = l.apply(i, o.call(arguments, 1));
        return C.filter(t,
        function(t) {
            return ! C.contains(e, t)
        })
    },
    C.zip = function() {
        for (var t = C.max(C.pluck(arguments, "length").concat(0)), e = new Array(t), n = 0; t > n; n++) e[n] = C.pluck(arguments, "" + n);
        return e
    },
    C.object = function(t, e) {
        if (null == t) return {};
        for (var n = {},
        i = 0,
        r = t.length; r > i; i++) e ? n[t[i]] = e[i] : n[t[i][0]] = t[i][1];
        return n
    },
    C.indexOf = function(t, e, n) {
        if (null == t) return - 1;
        var i = 0,
        r = t.length;
        if (n) {
            if ("number" != typeof n) return i = C.sortedIndex(t, e),
            t[i] === e ? i: -1;
            i = 0 > n ? Math.max(0, r + n) : n
        }
        if (y && t.indexOf === y) return t.indexOf(e, n);
        for (; r > i; i++) if (t[i] === e) return i;
        return - 1
    },
    C.lastIndexOf = function(t, e, n) {
        if (null == t) return - 1;
        var i = null != n;
        if (b && t.lastIndexOf === b) return i ? t.lastIndexOf(e, n) : t.lastIndexOf(e);
        for (var r = i ? n: t.length; r--;) if (t[r] === e) return r;
        return - 1
    },
    C.range = function(t, e, n) {
        arguments.length <= 1 && (e = t || 0, t = 0),
        n = arguments[2] || 1;
        for (var i = Math.max(Math.ceil((e - t) / n), 0), r = 0, s = new Array(i); i > r;) s[r++] = t,
        t += n;
        return s
    };
    var M = function() {};
    C.bind = function(t, e) {
        var n, i;
        if (x && t.bind === x) return x.apply(t, o.call(arguments, 1));
        if (!C.isFunction(t)) throw new TypeError;
        return n = o.call(arguments, 2),
        i = function() {
            if (! (this instanceof i)) return t.apply(e, n.concat(o.call(arguments)));
            M.prototype = t.prototype;
            var r = new M;
            M.prototype = null;
            var s = t.apply(r, n.concat(o.call(arguments)));
            return Object(s) === s ? s: r
        }
    },
    C.partial = function(t) {
        var e = o.call(arguments, 1);
        return function() {
            for (var n = 0,
            i = e.slice(), r = 0, s = i.length; s > r; r++) i[r] === C && (i[r] = arguments[n++]);
            for (; n < arguments.length;) i.push(arguments[n++]);
            return t.apply(this, i)
        }
    },
    C.bindAll = function(t) {
        var e = o.call(arguments, 1);
        if (0 === e.length) throw new Error("bindAll must be passed function names");
        return k(e,
        function(e) {
            t[e] = C.bind(t[e], t)
        }),
        t
    },
    C.memoize = function(t, e) {
        var n = {};
        return e || (e = C.identity),
        function() {
            var i = e.apply(this, arguments);
            return C.has(n, i) ? n[i] : n[i] = t.apply(this, arguments)
        }
    },
    C.delay = function(t, e) {
        var n = o.call(arguments, 2);
        return setTimeout(function() {
            return t.apply(null, n)
        },
        e)
    },
    C.defer = function(t) {
        return C.delay.apply(C, [t, 1].concat(o.call(arguments, 1)))
    },
    C.throttle = function(t, e, n) {
        var i, r, s, a = null,
        o = 0;
        n || (n = {});
        var l = function() {
            o = n.leading === !1 ? 0 : C.now(),
            a = null,
            s = t.apply(i, r),
            i = r = null
        };
        return function() {
            var u = C.now();
            o || n.leading !== !1 || (o = u);
            var c = e - (u - o);
            return i = this,
            r = arguments,
            0 >= c ? (clearTimeout(a), a = null, o = u, s = t.apply(i, r), i = r = null) : a || n.trailing === !1 || (a = setTimeout(l, c)),
            s
        }
    },
    C.debounce = function(t, e, n) {
        var i, r, s, a, o, l = function() {
            var u = C.now() - a;
            e > u ? i = setTimeout(l, e - u) : (i = null, n || (o = t.apply(s, r), s = r = null))
        };
        return function() {
            s = this,
            r = arguments,
            a = C.now();
            var u = n && !i;
            return i || (i = setTimeout(l, e)),
            u && (o = t.apply(s, r), s = r = null),
            o
        }
    },
    C.once = function(t) {
        var e, n = !1;
        return function() {
            return n ? e: (n = !0, e = t.apply(this, arguments), t = null, e)
        }
    },
    C.wrap = function(t, e) {
        return C.partial(e, t)
    },
    C.compose = function() {
        var t = arguments;
        return function() {
            for (var e = arguments,
            n = t.length - 1; n >= 0; n--) e = [t[n].apply(this, e)];
            return e[0]
        }
    },
    C.after = function(t, e) {
        return function() {
            return--t < 1 ? e.apply(this, arguments) : void 0
        }
    },
    C.keys = function(t) {
        if (!C.isObject(t)) return [];
        if ($) return $(t);
        var e = [];
        for (var n in t) C.has(t, n) && e.push(n);
        return e
    },
    C.values = function(t) {
        for (var e = C.keys(t), n = e.length, i = new Array(n), r = 0; n > r; r++) i[r] = t[e[r]];
        return i
    },
    C.pairs = function(t) {
        for (var e = C.keys(t), n = e.length, i = new Array(n), r = 0; n > r; r++) i[r] = [e[r], t[e[r]]];
        return i
    },
    C.invert = function(t) {
        for (var e = {},
        n = C.keys(t), i = 0, r = n.length; r > i; i++) e[t[n[i]]] = n[i];
        return e
    },
    C.functions = C.methods = function(t) {
        var e = [];
        for (var n in t) C.isFunction(t[n]) && e.push(n);
        return e.sort()
    },
    C.extend = function(t) {
        return k(o.call(arguments, 1),
        function(e) {
            if (e) for (var n in e) t[n] = e[n]
        }),
        t
    },
    C.pick = function(t) {
        var e = {},
        n = l.apply(i, o.call(arguments, 1));
        return k(n,
        function(n) {
            n in t && (e[n] = t[n])
        }),
        e
    },
    C.omit = function(t) {
        var e = {},
        n = l.apply(i, o.call(arguments, 1));
        for (var r in t) C.contains(n, r) || (e[r] = t[r]);
        return e
    },
    C.defaults = function(t) {
        return k(o.call(arguments, 1),
        function(e) {
            if (e) for (var n in e) void 0 === t[n] && (t[n] = e[n])
        }),
        t
    },
    C.clone = function(t) {
        return C.isObject(t) ? C.isArray(t) ? t.slice() : C.extend({},
        t) : t
    },
    C.tap = function(t, e) {
        return e(t),
        t
    };
    var A = function(t, e, n, i) {
        if (t === e) return 0 !== t || 1 / t == 1 / e;
        if (null == t || null == e) return t === e;
        t instanceof C && (t = t._wrapped),
        e instanceof C && (e = e._wrapped);
        var r = u.call(t);
        if (r != u.call(e)) return ! 1;
        switch (r) {
        case "[object String]":
            return t == String(e);
        case "[object Number]":
            return t != +t ? e != +e: 0 == t ? 1 / t == 1 / e: t == +e;
        case "[object Date]":
        case "[object Boolean]":
            return + t == +e;
        case "[object RegExp]":
            return t.source == e.source && t.global == e.global && t.multiline == e.multiline && t.ignoreCase == e.ignoreCase
        }
        if ("object" != typeof t || "object" != typeof e) return ! 1;
        for (var s = n.length; s--;) if (n[s] == t) return i[s] == e;
        var a = t.constructor,
        o = e.constructor;
        if (a !== o && !(C.isFunction(a) && a instanceof a && C.isFunction(o) && o instanceof o) && "constructor" in t && "constructor" in e) return ! 1;
        n.push(t),
        i.push(e);
        var l = 0,
        c = !0;
        if ("[object Array]" == r) {
            if (l = t.length, c = l == e.length) for (; l--&&(c = A(t[l], e[l], n, i)););
        } else {
            for (var h in t) if (C.has(t, h) && (l++, !(c = C.has(e, h) && A(t[h], e[h], n, i)))) break;
            if (c) {
                for (h in e) if (C.has(e, h) && !l--) break;
                c = !l
            }
        }
        return n.pop(),
        i.pop(),
        c
    };
    C.isEqual = function(t, e) {
        return A(t, e, [], [])
    },
    C.isEmpty = function(t) {
        if (null == t) return ! 0;
        if (C.isArray(t) || C.isString(t)) return 0 === t.length;
        for (var e in t) if (C.has(t, e)) return ! 1;
        return ! 0
    },
    C.isElement = function(t) {
        return ! (!t || 1 !== t.nodeType)
    },
    C.isArray = w ||
    function(t) {
        return "[object Array]" == u.call(t)
    },
    C.isObject = function(t) {
        return t === Object(t)
    },
    k(["Arguments", "Function", "String", "Number", "Date", "RegExp"],
    function(t) {
        C["is" + t] = function(e) {
            return u.call(e) == "[object " + t + "]"
        }
    }),
    C.isArguments(arguments) || (C.isArguments = function(t) {
        return ! (!t || !C.has(t, "callee"))
    }),
    "function" != typeof / . / &&(C.isFunction = function(t) {
        return "function" == typeof t
    }),
    C.isFinite = function(t) {
        return isFinite(t) && !isNaN(parseFloat(t))
    },
    C.isNaN = function(t) {
        return C.isNumber(t) && t != +t
    },
    C.isBoolean = function(t) {
        return t === !0 || t === !1 || "[object Boolean]" == u.call(t)
    },
    C.isNull = function(t) {
        return null === t
    },
    C.isUndefined = function(t) {
        return void 0 === t
    },
    C.has = function(t, e) {
        return c.call(t, e)
    },
    C.noConflict = function() {
        return t._ = e,
        this
    },
    C.identity = function(t) {
        return t
    },
    C.constant = function(t) {
        return function() {
            return t
        }
    },
    C.property = function(t) {
        return function(e) {
            return e[t]
        }
    },
    C.matches = function(t) {
        return function(e) {
            if (e === t) return ! 0;
            for (var n in t) if (t[n] !== e[n]) return ! 1;
            return ! 0
        }
    },
    C.times = function(t, e, n) {
        for (var i = Array(Math.max(0, t)), r = 0; t > r; r++) i[r] = e.call(n, r);
        return i
    },
    C.random = function(t, e) {
        return null == e && (e = t, t = 0),
        t + Math.floor(Math.random() * (e - t + 1))
    },
    C.now = Date.now ||
    function() {
        return (new Date).getTime()
    };
    var O = {
        "escape": {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#x27;"
        }
    };
    O.unescape = C.invert(O.escape);
    var F = {
        "escape": new RegExp("[" + C.keys(O.escape).join("") + "]", "g"),
        "unescape": new RegExp("(" + C.keys(O.unescape).join("|") + ")", "g")
    };
    C.each(["escape", "unescape"],
    function(t) {
        C[t] = function(e) {
            return null == e ? "": ("" + e).replace(F[t],
            function(e) {
                return O[t][e]
            })
        }
    }),
    C.result = function(t, e) {
        if (null == t) return void 0;
        var n = t[e];
        return C.isFunction(n) ? n.call(t) : n
    },
    C.mixin = function(t) {
        k(C.functions(t),
        function(e) {
            var n = C[e] = t[e];
            C.prototype[e] = function() {
                var t = [this._wrapped];
                return a.apply(t, arguments),
                L.call(this, n.apply(C, t))
            }
        })
    };
    var N = 0;
    C.uniqueId = function(t) {
        var e = ++N + "";
        return t ? t + e: e
    },
    C.templateSettings = {
        "evaluate": /<%([\s\S]+?)%>/g,
        "interpolate": /<%=([\s\S]+?)%>/g,
        "escape": /<%-([\s\S]+?)%>/g
    };
    var j = /(.)^/,
    P = {
        "'": "'",
        "\\": "\\",
        "\r": "r",
        "\n": "n",
        "	": "t",
        "\u2028": "u2028",
        "\u2029": "u2029"
    },
    I = /\\|'|\r|\n|\t|\u2028|\u2029/g;
    C.template = function(t, e, n) {
        var i;
        n = C.defaults({},
        n, C.templateSettings);
        var r = new RegExp([(n.escape || j).source, (n.interpolate || j).source, (n.evaluate || j).source].join("|") + "|$", "g"),
        s = 0,
        a = "__p+='";
        t.replace(r,
        function(e, n, i, r, o) {
            return a += t.slice(s, o).replace(I,
            function(t) {
                return "\\" + P[t]
            }),
            n && (a += "'+\n((__t=(" + n + "))==null?'':_.escape(__t))+\n'"),
            i && (a += "'+\n((__t=(" + i + "))==null?'':__t)+\n'"),
            r && (a += "';\n" + r + "\n__p+='"),
            s = o + e.length,
            e
        }),
        a += "';\n",
        n.variable || (a = "with(obj||{}){\n" + a + "}\n"),
        a = "var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};\n" + a + "return __p;\n";
        try {
            i = new Function(n.variable || "obj", "_", a)
        } catch(o) {
            throw o.source = a,
            o
        }
        if (e) return i(e, C);
        var l = function(t) {
            return i.call(this, t, C)
        };
        return l.source = "function(" + (n.variable || "obj") + "){\n" + a + "}",
        l
    },
    C.chain = function(t) {
        return C(t).chain()
    };
    var L = function(t) {
        return this._chain ? C(t).chain() : t
    };
    C.mixin(C),
    k(["pop", "push", "reverse", "shift", "sort", "splice", "unshift"],
    function(t) {
        var e = i[t];
        C.prototype[t] = function() {
            var n = this._wrapped;
            return e.apply(n, arguments),
            "shift" != t && "splice" != t || 0 !== n.length || delete n[0],
            L.call(this, n)
        }
    }),
    k(["concat", "join", "slice"],
    function(t) {
        var e = i[t];
        C.prototype[t] = function() {
            return L.call(this, e.apply(this._wrapped, arguments))
        }
    }),
    C.extend(C.prototype, {
        "chain": function() {
            return this._chain = !0,
            this
        },
        "value": function() {
            return this._wrapped
        }
    }),
    "function" == typeof define && define.amd && define("underscore", [],
    function() {
        return C
    })
}).call(this),
function(t) {
    function e() {
        return {
            "empty": !1,
            "unusedTokens": [],
            "unusedInput": [],
            "overflow": -2,
            "charsLeftOver": 0,
            "nullInput": !1,
            "invalidMonth": null,
            "invalidFormat": !1,
            "userInvalidated": !1,
            "iso": !1
        }
    }
    function n(t, e) {
        function n() {
            le.suppressDeprecationWarnings === !1 && "undefined" != typeof console && console.warn
        }
        var i = !0;
        return l(function() {
            return i && (n(), i = !1),
            e.apply(this, arguments)
        },
        e)
    }
    function i(t, e) {
        return function(n) {
            return h(t.call(this, n), e)
        }
    }
    function r(t, e) {
        return function(n) {
            return this.lang().ordinal(t.call(this, n), e)
        }
    }
    function s() {}
    function a(t) {
        k(t),
        l(this, t)
    }
    function o(t) {
        var e = v(t),
        n = e.year || 0,
        i = e.quarter || 0,
        r = e.month || 0,
        s = e.week || 0,
        a = e.day || 0,
        o = e.hour || 0,
        l = e.minute || 0,
        u = e.second || 0,
        c = e.millisecond || 0;
        this._milliseconds = +c + 1e3 * u + 6e4 * l + 36e5 * o,
        this._days = +a + 7 * s,
        this._months = +r + 3 * i + 12 * n,
        this._data = {},
        this._bubble()
    }
    function l(t, e) {
        for (var n in e) e.hasOwnProperty(n) && (t[n] = e[n]);
        return e.hasOwnProperty("toString") && (t.toString = e.toString),
        e.hasOwnProperty("valueOf") && (t.valueOf = e.valueOf),
        t
    }
    function u(t) {
        var e, n = {};
        for (e in t) t.hasOwnProperty(e) && xe.hasOwnProperty(e) && (n[e] = t[e]);
        return n
    }
    function c(t) {
        return 0 > t ? Math.ceil(t) : Math.floor(t)
    }
    function h(t, e, n) {
        for (var i = "" + Math.abs(t), r = t >= 0; i.length < e;) i = "0" + i;
        return (r ? n ? "+": "": "-") + i
    }
    function d(t, e, n, i) {
        var r = e._milliseconds,
        s = e._days,
        a = e._months;
        i = null == i ? !0 : i,
        r && t._d.setTime( + t._d + r * n),
        s && ie(t, "Date", ne(t, "Date") + s * n),
        a && ee(t, ne(t, "Month") + a * n),
        i && le.updateOffset(t, s || a)
    }
    function f(t) {
        return "[object Array]" === Object.prototype.toString.call(t)
    }
    function p(t) {
        return "[object Date]" === Object.prototype.toString.call(t) || t instanceof Date
    }
    function m(t, e, n) {
        var i, r = Math.min(t.length, e.length),
        s = Math.abs(t.length - e.length),
        a = 0;
        for (i = 0; r > i; i++)(n && t[i] !== e[i] || !n && b(t[i]) !== b(e[i])) && a++;
        return a + s
    }
    function g(t) {
        if (t) {
            var e = t.toLowerCase().replace(/(.)s$/, "$1");
            t = Je[t] || Xe[e] || e
        }
        return t
    }
    function v(t) {
        var e, n, i = {};
        for (n in t) t.hasOwnProperty(n) && (e = g(n), e && (i[e] = t[n]));
        return i
    }
    function y(e) {
        var n, i;
        if (0 === e.indexOf("week")) n = 7,
        i = "day";
        else {
            if (0 !== e.indexOf("month")) return;
            n = 12,
            i = "month"
        }
        le[e] = function(r, s) {
            var a, o, l = le.fn._lang[e],
            u = [];
            if ("number" == typeof r && (s = r, r = t), o = function(t) {
                var e = le().utc().set(i, t);
                return l.call(le.fn._lang, e, r || "")
            },
            null != s) return o(s);
            for (a = 0; n > a; a++) u.push(o(a));
            return u
        }
    }
    function b(t) {
        var e = +t,
        n = 0;
        return 0 !== e && isFinite(e) && (n = e >= 0 ? Math.floor(e) : Math.ceil(e)),
        n
    }
    function w(t, e) {
        return new Date(Date.UTC(t, e + 1, 0)).getUTCDate()
    }
    function $(t, e, n) {
        return X(le([t, 11, 31 + e - n]), e, n).week
    }
    function x(t) {
        return C(t) ? 366 : 365
    }
    function C(t) {
        return t % 4 === 0 && t % 100 !== 0 || t % 400 === 0
    }
    function k(t) {
        var e;
        t._a && -2 === t._pf.overflow && (e = t._a[me] < 0 || t._a[me] > 11 ? me: t._a[ge] < 1 || t._a[ge] > w(t._a[pe], t._a[me]) ? ge: t._a[ve] < 0 || t._a[ve] > 23 ? ve: t._a[ye] < 0 || t._a[ye] > 59 ? ye: t._a[be] < 0 || t._a[be] > 59 ? be: t._a[we] < 0 || t._a[we] > 999 ? we: -1, t._pf._overflowDayOfYear && (pe > e || e > ge) && (e = ge), t._pf.overflow = e)
    }
    function T(t) {
        return null == t._isValid && (t._isValid = !isNaN(t._d.getTime()) && t._pf.overflow < 0 && !t._pf.empty && !t._pf.invalidMonth && !t._pf.nullInput && !t._pf.invalidFormat && !t._pf.userInvalidated, t._strict && (t._isValid = t._isValid && 0 === t._pf.charsLeftOver && 0 === t._pf.unusedTokens.length)),
        t._isValid
    }
    function S(t) {
        return t ? t.toLowerCase().replace("_", "-") : t
    }
    function D(t, e) {
        return e._isUTC ? le(t).zone(e._offset || 0) : le(t).local()
    }
    function E(t, e) {
        return e.abbr = t,
        $e[t] || ($e[t] = new s),
        $e[t].set(e),
        $e[t]
    }
    function _(t) {
        delete $e[t]
    }
    function M(t) {
        var e, n, i, r, s = 0,
        a = function(t) {
            if (!$e[t] && Ce) try {
                require("./lang/" + t)
            } catch(e) {}
            return $e[t]
        };
        if (!t) return le.fn._lang;
        if (!f(t)) {
            if (n = a(t)) return n;
            t = [t]
        }
        for (; s < t.length;) {
            for (r = S(t[s]).split("-"), e = r.length, i = S(t[s + 1]), i = i ? i.split("-") : null; e > 0;) {
                if (n = a(r.slice(0, e).join("-"))) return n;
                if (i && i.length >= e && m(r, i, !0) >= e - 1) break;
                e--
            }
            s++
        }
        return le.fn._lang
    }
    function A(t) {
        return t.match(/\[[\s\S]/) ? t.replace(/^\[|\]$/g, "") : t.replace(/\\/g, "")
    }
    function O(t) {
        var e, n, i = t.match(De);
        for (e = 0, n = i.length; n > e; e++) i[e] = nn[i[e]] ? nn[i[e]] : A(i[e]);
        return function(r) {
            var s = "";
            for (e = 0; n > e; e++) s += i[e] instanceof Function ? i[e].call(r, t) : i[e];
            return s
        }
    }
    function F(t, e) {
        return t.isValid() ? (e = N(e, t.lang()), Ke[e] || (Ke[e] = O(e)), Ke[e](t)) : t.lang().invalidDate()
    }
    function N(t, e) {
        function n(t) {
            return e.longDateFormat(t) || t
        }
        var i = 5;
        for (Ee.lastIndex = 0; i >= 0 && Ee.test(t);) t = t.replace(Ee, n),
        Ee.lastIndex = 0,
        i -= 1;
        return t
    }
    function j(t, e) {
        var n, i = e._strict;
        switch (t) {
        case "Q":
            return He;
        case "DDDD":
            return Ue;
        case "YYYY":
        case "GGGG":
        case "gggg":
            return i ? qe: Ae;
        case "Y":
        case "G":
        case "g":
            return Ve;
        case "YYYYYY":
        case "YYYYY":
        case "GGGGG":
        case "ggggg":
            return i ? Ye: Oe;
        case "S":
            if (i) return He;
        case "SS":
            if (i) return Re;
        case "SSS":
            if (i) return Ue;
        case "DDD":
            return Me;
        case "MMM":
        case "MMMM":
        case "dd":
        case "ddd":
        case "dddd":
            return Ne;
        case "a":
        case "A":
            return M(e._l)._meridiemParse;
        case "X":
            return Ie;
        case "Z":
        case "ZZ":
            return je;
        case "T":
            return Pe;
        case "SSSS":
            return Fe;
        case "MM":
        case "DD":
        case "YY":
        case "GG":
        case "gg":
        case "HH":
        case "hh":
        case "mm":
        case "ss":
        case "ww":
        case "WW":
            return i ? Re: _e;
        case "M":
        case "D":
        case "d":
        case "H":
        case "h":
        case "m":
        case "s":
        case "w":
        case "W":
        case "e":
        case "E":
            return _e;
        case "Do":
            return Le;
        default:
            return n = new RegExp(Y(q(t.replace("\\", "")), "i"))
        }
    }
    function P(t) {
        t = t || "";
        var e = t.match(je) || [],
        n = e[e.length - 1] || [],
        i = (n + "").match(Qe) || ["-", 0, 0],
        r = +(60 * i[1]) + b(i[2]);
        return "+" === i[0] ? -r: r
    }
    function I(t, e, n) {
        var i, r = n._a;
        switch (t) {
        case "Q":
            null != e && (r[me] = 3 * (b(e) - 1));
            break;
        case "M":
        case "MM":
            null != e && (r[me] = b(e) - 1);
            break;
        case "MMM":
        case "MMMM":
            i = M(n._l).monthsParse(e),
            null != i ? r[me] = i: n._pf.invalidMonth = e;
            break;
        case "D":
        case "DD":
            null != e && (r[ge] = b(e));
            break;
        case "Do":
            null != e && (r[ge] = b(parseInt(e, 10)));
            break;
        case "DDD":
        case "DDDD":
            null != e && (n._dayOfYear = b(e));
            break;
        case "YY":
            r[pe] = le.parseTwoDigitYear(e);
            break;
        case "YYYY":
        case "YYYYY":
        case "YYYYYY":
            r[pe] = b(e);
            break;
        case "a":
        case "A":
            n._isPm = M(n._l).isPM(e);
            break;
        case "H":
        case "HH":
        case "h":
        case "hh":
            r[ve] = b(e);
            break;
        case "m":
        case "mm":
            r[ye] = b(e);
            break;
        case "s":
        case "ss":
            r[be] = b(e);
            break;
        case "S":
        case "SS":
        case "SSS":
        case "SSSS":
            r[we] = b(1e3 * ("0." + e));
            break;
        case "X":
            n._d = new Date(1e3 * parseFloat(e));
            break;
        case "Z":
        case "ZZ":
            n._useUTC = !0,
            n._tzm = P(e);
            break;
        case "w":
        case "ww":
        case "W":
        case "WW":
        case "d":
        case "dd":
        case "ddd":
        case "dddd":
        case "e":
        case "E":
            t = t.substr(0, 1);
        case "gg":
        case "gggg":
        case "GG":
        case "GGGG":
        case "GGGGG":
            t = t.substr(0, 2),
            e && (n._w = n._w || {},
            n._w[t] = e)
        }
    }
    function L(t) {
        var e, n, i, r, s, a, o, l, u, c, h = [];
        if (!t._d) {
            for (i = R(t), t._w && null == t._a[ge] && null == t._a[me] && (s = function(e) {
                var n = parseInt(e, 10);
                return e ? e.length < 3 ? n > 68 ? 1900 + n: 2e3 + n: n: null == t._a[pe] ? le().weekYear() : t._a[pe]
            },
            a = t._w, null != a.GG || null != a.W || null != a.E ? o = K(s(a.GG), a.W || 1, a.E, 4, 1) : (l = M(t._l), u = null != a.d ? Q(a.d, l) : null != a.e ? parseInt(a.e, 10) + l._week.dow: 0, c = parseInt(a.w, 10) || 1, null != a.d && u < l._week.dow && c++, o = K(s(a.gg), c, u, l._week.doy, l._week.dow)), t._a[pe] = o.year, t._dayOfYear = o.dayOfYear), t._dayOfYear && (r = null == t._a[pe] ? i[pe] : t._a[pe], t._dayOfYear > x(r) && (t._pf._overflowDayOfYear = !0), n = G(r, 0, t._dayOfYear), t._a[me] = n.getUTCMonth(), t._a[ge] = n.getUTCDate()), e = 0; 3 > e && null == t._a[e]; ++e) t._a[e] = h[e] = i[e];
            for (; 7 > e; e++) t._a[e] = h[e] = null == t._a[e] ? 2 === e ? 1 : 0 : t._a[e];
            h[ve] += b((t._tzm || 0) / 60),
            h[ye] += b((t._tzm || 0) % 60),
            t._d = (t._useUTC ? G: B).apply(null, h)
        }
    }
    function H(t) {
        var e;
        t._d || (e = v(t._i), t._a = [e.year, e.month, e.day, e.hour, e.minute, e.second, e.millisecond], L(t))
    }
    function R(t) {
        var e = new Date;
        return t._useUTC ? [e.getUTCFullYear(), e.getUTCMonth(), e.getUTCDate()] : [e.getFullYear(), e.getMonth(), e.getDate()]
    }
    function U(t) {
        t._a = [],
        t._pf.empty = !0;
        var e, n, i, r, s, a = M(t._l),
        o = "" + t._i,
        l = o.length,
        u = 0;
        for (i = N(t._f, a).match(De) || [], e = 0; e < i.length; e++) r = i[e],
        n = (o.match(j(r, t)) || [])[0],
        n && (s = o.substr(0, o.indexOf(n)), s.length > 0 && t._pf.unusedInput.push(s), o = o.slice(o.indexOf(n) + n.length), u += n.length),
        nn[r] ? (n ? t._pf.empty = !1 : t._pf.unusedTokens.push(r), I(r, n, t)) : t._strict && !n && t._pf.unusedTokens.push(r);
        t._pf.charsLeftOver = l - u,
        o.length > 0 && t._pf.unusedInput.push(o),
        t._isPm && t._a[ve] < 12 && (t._a[ve] += 12),
        t._isPm === !1 && 12 === t._a[ve] && (t._a[ve] = 0),
        L(t),
        k(t)
    }
    function q(t) {
        return t.replace(/\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g,
        function(t, e, n, i, r) {
            return e || n || i || r
        })
    }
    function Y(t) {
        return t.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&")
    }
    function V(t) {
        var n, i, r, s, a;
        if (0 === t._f.length) return t._pf.invalidFormat = !0,
        void(t._d = new Date(0 / 0));
        for (s = 0; s < t._f.length; s++) a = 0,
        n = l({},
        t),
        n._pf = e(),
        n._f = t._f[s],
        U(n),
        T(n) && (a += n._pf.charsLeftOver, a += 10 * n._pf.unusedTokens.length, n._pf.score = a, (null == r || r > a) && (r = a, i = n));
        l(t, i || n)
    }
    function W(t) {
        var e, n, i = t._i,
        r = We.exec(i);
        if (r) {
            for (t._pf.iso = !0, e = 0, n = Be.length; n > e; e++) if (Be[e][1].exec(i)) {
                t._f = Be[e][0] + (r[6] || " ");
                break
            }
            for (e = 0, n = Ge.length; n > e; e++) if (Ge[e][1].exec(i)) {
                t._f += Ge[e][0];
                break
            }
            i.match(je) && (t._f += "Z"),
            U(t)
        } else le.createFromInputFallback(t)
    }
    function z(e) {
        var n = e._i,
        i = ke.exec(n);
        n === t ? e._d = new Date: i ? e._d = new Date( + i[1]) : "string" == typeof n ? W(e) : f(n) ? (e._a = n.slice(0), L(e)) : p(n) ? e._d = new Date( + n) : "object" == typeof n ? H(e) : "number" == typeof n ? e._d = new Date(n) : le.createFromInputFallback(e)
    }
    function B(t, e, n, i, r, s, a) {
        var o = new Date(t, e, n, i, r, s, a);
        return 1970 > t && o.setFullYear(t),
        o
    }
    function G(t) {
        var e = new Date(Date.UTC.apply(null, arguments));
        return 1970 > t && e.setUTCFullYear(t),
        e
    }
    function Q(t, e) {
        if ("string" == typeof t) if (isNaN(t)) {
            if (t = e.weekdaysParse(t), "number" != typeof t) return null
        } else t = parseInt(t, 10);
        return t
    }
    function Z(t, e, n, i, r) {
        return r.relativeTime(e || 1, !!n, t, i)
    }
    function J(t, e, n) {
        var i = fe(Math.abs(t) / 1e3),
        r = fe(i / 60),
        s = fe(r / 60),
        a = fe(s / 24),
        o = fe(a / 365),
        l = 45 > i && ["s", i] || 1 === r && ["m"] || 45 > r && ["mm", r] || 1 === s && ["h"] || 22 > s && ["hh", s] || 1 === a && ["d"] || 25 >= a && ["dd", a] || 45 >= a && ["M"] || 345 > a && ["MM", fe(a / 30)] || 1 === o && ["y"] || ["yy", o];
        return l[2] = e,
        l[3] = t > 0,
        l[4] = n,
        Z.apply({},
        l)
    }
    function X(t, e, n) {
        var i, r = n - e,
        s = n - t.day();
        return s > r && (s -= 7),
        r - 7 > s && (s += 7),
        i = le(t).add("d", s),
        {
            "week": Math.ceil(i.dayOfYear() / 7),
            "year": i.year()
        }
    }
    function K(t, e, n, i, r) {
        var s, a, o = G(t, 0, 1).getUTCDay();
        return n = null != n ? n: r,
        s = r - o + (o > i ? 7 : 0) - (r > o ? 7 : 0),
        a = 7 * (e - 1) + (n - r) + s + 1,
        {
            "year": a > 0 ? t: t - 1,
            "dayOfYear": a > 0 ? a: x(t - 1) + a
        }
    }
    function te(e) {
        var n = e._i,
        i = e._f;
        return null === n || i === t && "" === n ? le.invalid({
            "nullInput": !0
        }) : ("string" == typeof n && (e._i = n = M().preparse(n)), le.isMoment(n) ? (e = u(n), e._d = new Date( + n._d)) : i ? f(i) ? V(e) : U(e) : z(e), new a(e))
    }
    function ee(t, e) {
        var n;
        return "string" == typeof e && (e = t.lang().monthsParse(e), "number" != typeof e) ? t: (n = Math.min(t.date(), w(t.year(), e)), t._d["set" + (t._isUTC ? "UTC": "") + "Month"](e, n), t)
    }
    function ne(t, e) {
        return t._d["get" + (t._isUTC ? "UTC": "") + e]()
    }
    function ie(t, e, n) {
        return "Month" === e ? ee(t, n) : t._d["set" + (t._isUTC ? "UTC": "") + e](n)
    }
    function re(t, e) {
        return function(n) {
            return null != n ? (ie(this, t, n), le.updateOffset(this, e), this) : ne(this, t)
        }
    }
    function se(t) {
        le.duration.fn[t] = function() {
            return this._data[t]
        }
    }
    function ae(t, e) {
        le.duration.fn["as" + t] = function() {
            return + this / e
        }
    }
    function oe(t) {
        "undefined" == typeof ender && (ue = de.moment, de.moment = t ? n("Accessing Moment through the global scope is deprecated, and will be removed in an upcoming release.", le) : le)
    }
    for (var le, ue, ce, he = "2.6.0",
    de = "undefined" != typeof global ? global: this, fe = Math.round, pe = 0, me = 1, ge = 2, ve = 3, ye = 4, be = 5, we = 6, $e = {},
    xe = {
        "_isAMomentObject": null,
        "_i": null,
        "_f": null,
        "_l": null,
        "_strict": null,
        "_isUTC": null,
        "_offset": null,
        "_pf": null,
        "_lang": null
    },
    Ce = "undefined" != typeof module && module.exports, ke = /^\/?Date\((\-?\d+)/i, Te = /(\-)?(?:(\d*)\.)?(\d+)\:(\d+)(?:\:(\d+)\.?(\d{3})?)?/, Se = /^(-)?P(?:(?:([0-9,.]*)Y)?(?:([0-9,.]*)M)?(?:([0-9,.]*)D)?(?:T(?:([0-9,.]*)H)?(?:([0-9,.]*)M)?(?:([0-9,.]*)S)?)?|([0-9,.]*)W)$/, De = /(\[[^\[]*\])|(\\)?(Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|Q|YYYYYY|YYYYY|YYYY|YY|gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|mm?|ss?|S{1,4}|X|zz?|ZZ?|.)/g, Ee = /(\[[^\[]*\])|(\\)?(LT|LL?L?L?|l{1,4})/g, _e = /\d\d?/, Me = /\d{1,3}/, Ae = /\d{1,4}/, Oe = /[+\-]?\d{1,6}/, Fe = /\d+/, Ne = /[0-9]*['a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+|[\u0600-\u06FF\/]+(\s*?[\u0600-\u06FF]+){1,2}/i, je = /Z|[\+\-]\d\d:?\d\d/gi, Pe = /T/i, Ie = /[\+\-]?\d+(\.\d{1,3})?/, Le = /\d{1,2}/, He = /\d/, Re = /\d\d/, Ue = /\d{3}/, qe = /\d{4}/, Ye = /[+-]?\d{6}/, Ve = /[+-]?\d+/, We = /^\s*(?:[+-]\d{6}|\d{4})-(?:(\d\d-\d\d)|(W\d\d$)|(W\d\d-\d)|(\d\d\d))((T| )(\d\d(:\d\d(:\d\d(\.\d+)?)?)?)?([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/, ze = "YYYY-MM-DDTHH:mm:ssZ", Be = [["YYYYYY-MM-DD", /[+-]\d{6}-\d{2}-\d{2}/], ["YYYY-MM-DD", /\d{4}-\d{2}-\d{2}/], ["GGGG-[W]WW-E", /\d{4}-W\d{2}-\d/], ["GGGG-[W]WW", /\d{4}-W\d{2}/], ["YYYY-DDD", /\d{4}-\d{3}/]], Ge = [["HH:mm:ss.SSSS", /(T| )\d\d:\d\d:\d\d\.\d+/], ["HH:mm:ss", /(T| )\d\d:\d\d:\d\d/], ["HH:mm", /(T| )\d\d:\d\d/], ["HH", /(T| )\d\d/]], Qe = /([\+\-]|\d\d)/gi, Ze = ("Date|Hours|Minutes|Seconds|Milliseconds".split("|"), {
        "Milliseconds": 1,
        "Seconds": 1e3,
        "Minutes": 6e4,
        "Hours": 36e5,
        "Days": 864e5,
        "Months": 2592e6,
        "Years": 31536e6
    }), Je = {
        "ms": "millisecond",
        "s": "second",
        "m": "minute",
        "h": "hour",
        "d": "day",
        "D": "date",
        "w": "week",
        "W": "isoWeek",
        "M": "month",
        "Q": "quarter",
        "y": "year",
        "DDD": "dayOfYear",
        "e": "weekday",
        "E": "isoWeekday",
        "gg": "weekYear",
        "GG": "isoWeekYear"
    },
    Xe = {
        "dayofyear": "dayOfYear",
        "isoweekday": "isoWeekday",
        "isoweek": "isoWeek",
        "weekyear": "weekYear",
        "isoweekyear": "isoWeekYear"
    },
    Ke = {},
    tn = "DDD w W M D d".split(" "), en = "M D H h m s w W".split(" "), nn = {
        "M": function() {
            return this.month() + 1
        },
        "MMM": function(t) {
            return this.lang().monthsShort(this, t)
        },
        "MMMM": function(t) {
            return this.lang().months(this, t)
        },
        "D": function() {
            return this.date()
        },
        "DDD": function() {
            return this.dayOfYear()
        },
        "d": function() {
            return this.day()
        },
        "dd": function(t) {
            return this.lang().weekdaysMin(this, t)
        },
        "ddd": function(t) {
            return this.lang().weekdaysShort(this, t)
        },
        "dddd": function(t) {
            return this.lang().weekdays(this, t)
        },
        "w": function() {
            return this.week()
        },
        "W": function() {
            return this.isoWeek()
        },
        "YY": function() {
            return h(this.year() % 100, 2)
        },
        "YYYY": function() {
            return h(this.year(), 4)
        },
        "YYYYY": function() {
            return h(this.year(), 5)
        },
        "YYYYYY": function() {
            var t = this.year(),
            e = t >= 0 ? "+": "-";
            return e + h(Math.abs(t), 6)
        },
        "gg": function() {
            return h(this.weekYear() % 100, 2)
        },
        "gggg": function() {
            return h(this.weekYear(), 4)
        },
        "ggggg": function() {
            return h(this.weekYear(), 5)
        },
        "GG": function() {
            return h(this.isoWeekYear() % 100, 2)
        },
        "GGGG": function() {
            return h(this.isoWeekYear(), 4)
        },
        "GGGGG": function() {
            return h(this.isoWeekYear(), 5)
        },
        "e": function() {
            return this.weekday()
        },
        "E": function() {
            return this.isoWeekday()
        },
        "a": function() {
            return this.lang().meridiem(this.hours(), this.minutes(), !0)
        },
        "A": function() {
            return this.lang().meridiem(this.hours(), this.minutes(), !1)
        },
        "H": function() {
            return this.hours()
        },
        "h": function() {
            return this.hours() % 12 || 12
        },
        "m": function() {
            return this.minutes()
        },
        "s": function() {
            return this.seconds()
        },
        "S": function() {
            return b(this.milliseconds() / 100)
        },
        "SS": function() {
            return h(b(this.milliseconds() / 10), 2)
        },
        "SSS": function() {
            return h(this.milliseconds(), 3)
        },
        "SSSS": function() {
            return h(this.milliseconds(), 3)
        },
        "Z": function() {
            var t = -this.zone(),
            e = "+";
            return 0 > t && (t = -t, e = "-"),
            e + h(b(t / 60), 2) + ":" + h(b(t) % 60, 2)
        },
        "ZZ": function() {
            var t = -this.zone(),
            e = "+";
            return 0 > t && (t = -t, e = "-"),
            e + h(b(t / 60), 2) + h(b(t) % 60, 2)
        },
        "z": function() {
            return this.zoneAbbr()
        },
        "zz": function() {
            return this.zoneName()
        },
        "X": function() {
            return this.unix()
        },
        "Q": function() {
            return this.quarter()
        }
    },
    rn = ["months", "monthsShort", "weekdays", "weekdaysShort", "weekdaysMin"]; tn.length;) ce = tn.pop(),
    nn[ce + "o"] = r(nn[ce], ce);
    for (; en.length;) ce = en.pop(),
    nn[ce + ce] = i(nn[ce], 2);
    for (nn.DDDD = i(nn.DDD, 3), l(s.prototype, {
        "set": function(t) {
            var e, n;
            for (n in t) e = t[n],
            "function" == typeof e ? this[n] = e: this["_" + n] = e
        },
        "_months": "January_February_March_April_May_June_July_August_September_October_November_December".split("_"),
        "months": function(t) {
            return this._months[t.month()]
        },
        "_monthsShort": "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_"),
        "monthsShort": function(t) {
            return this._monthsShort[t.month()]
        },
        "monthsParse": function(t) {
            var e, n, i;
            for (this._monthsParse || (this._monthsParse = []), e = 0; 12 > e; e++) if (this._monthsParse[e] || (n = le.utc([2e3, e]), i = "^" + this.months(n, "") + "|^" + this.monthsShort(n, ""), this._monthsParse[e] = new RegExp(i.replace(".", ""), "i")), this._monthsParse[e].test(t)) return e
        },
        "_weekdays": "Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),
        "weekdays": function(t) {
            return this._weekdays[t.day()]
        },
        "_weekdaysShort": "Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_"),
        "weekdaysShort": function(t) {
            return this._weekdaysShort[t.day()]
        },
        "_weekdaysMin": "Su_Mo_Tu_We_Th_Fr_Sa".split("_"),
        "weekdaysMin": function(t) {
            return this._weekdaysMin[t.day()]
        },
        "weekdaysParse": function(t) {
            var e, n, i;
            for (this._weekdaysParse || (this._weekdaysParse = []), e = 0; 7 > e; e++) if (this._weekdaysParse[e] || (n = le([2e3, 1]).day(e), i = "^" + this.weekdays(n, "") + "|^" + this.weekdaysShort(n, "") + "|^" + this.weekdaysMin(n, ""), this._weekdaysParse[e] = new RegExp(i.replace(".", ""), "i")), this._weekdaysParse[e].test(t)) return e
        },
        "_longDateFormat": {
            "LT": "h:mm A",
            "L": "MM/DD/YYYY",
            "LL": "MMMM D YYYY",
            "LLL": "MMMM D YYYY LT",
            "LLLL": "dddd, MMMM D YYYY LT"
        },
        "longDateFormat": function(t) {
            var e = this._longDateFormat[t];
            return ! e && this._longDateFormat[t.toUpperCase()] && (e = this._longDateFormat[t.toUpperCase()].replace(/MMMM|MM|DD|dddd/g,
            function(t) {
                return t.slice(1)
            }), this._longDateFormat[t] = e),
            e
        },
        "isPM": function(t) {
            return "p" === (t + "").toLowerCase().charAt(0)
        },
        "_meridiemParse": /[ap]\.?m?\.?/i,
        "meridiem": function(t, e, n) {
            return t > 11 ? n ? "pm": "PM": n ? "am": "AM"
        },
        "_calendar": {
            "sameDay": "[Today at] LT",
            "nextDay": "[Tomorrow at] LT",
            "nextWeek": "dddd [at] LT",
            "lastDay": "[Yesterday at] LT",
            "lastWeek": "[Last] dddd [at] LT",
            "sameElse": "L"
        },
        "calendar": function(t, e) {
            var n = this._calendar[t];
            return "function" == typeof n ? n.apply(e) : n
        },
        "_relativeTime": {
            "future": "in %s",
            "past": "%s ago",
            "s": "a few seconds",
            "m": "a minute",
            "mm": "%d minutes",
            "h": "an hour",
            "hh": "%d hours",
            "d": "a day",
            "dd": "%d days",
            "M": "a month",
            "MM": "%d months",
            "y": "a year",
            "yy": "%d years"
        },
        "relativeTime": function(t, e, n, i) {
            var r = this._relativeTime[n];
            return "function" == typeof r ? r(t, e, n, i) : r.replace(/%d/i, t)
        },
        "pastFuture": function(t, e) {
            var n = this._relativeTime[t > 0 ? "future": "past"];
            return "function" == typeof n ? n(e) : n.replace(/%s/i, e)
        },
        "ordinal": function(t) {
            return this._ordinal.replace("%d", t)
        },
        "_ordinal": "%d",
        "preparse": function(t) {
            return t
        },
        "postformat": function(t) {
            return t
        },
        "week": function(t) {
            return X(t, this._week.dow, this._week.doy).week
        },
        "_week": {
            "dow": 0,
            "doy": 6
        },
        "_invalidDate": "Invalid date",
        "invalidDate": function() {
            return this._invalidDate
        }
    }), le = function(n, i, r, s) {
        var a;
        return "boolean" == typeof r && (s = r, r = t),
        a = {},
        a._isAMomentObject = !0,
        a._i = n,
        a._f = i,
        a._l = r,
        a._strict = s,
        a._isUTC = !1,
        a._pf = e(),
        te(a)
    },
    le.suppressDeprecationWarnings = !1, le.createFromInputFallback = n("moment construction falls back to js Date. This is discouraged and will be removed in upcoming major release. Please refer to https://github.com/moment/moment/issues/1407 for more info.",
    function(t) {
        t._d = new Date(t._i)
    }), le.utc = function(n, i, r, s) {
        var a;
        return "boolean" == typeof r && (s = r, r = t),
        a = {},
        a._isAMomentObject = !0,
        a._useUTC = !0,
        a._isUTC = !0,
        a._l = r,
        a._i = n,
        a._f = i,
        a._strict = s,
        a._pf = e(),
        te(a).utc()
    },
    le.unix = function(t) {
        return le(1e3 * t)
    },
    le.duration = function(t, e) {
        var n, i, r, s = t,
        a = null;
        return le.isDuration(t) ? s = {
            "ms": t._milliseconds,
            "d": t._days,
            "M": t._months
        }: "number" == typeof t ? (s = {},
        e ? s[e] = t: s.milliseconds = t) : (a = Te.exec(t)) ? (n = "-" === a[1] ? -1 : 1, s = {
            "y": 0,
            "d": b(a[ge]) * n,
            "h": b(a[ve]) * n,
            "m": b(a[ye]) * n,
            "s": b(a[be]) * n,
            "ms": b(a[we]) * n
        }) : (a = Se.exec(t)) && (n = "-" === a[1] ? -1 : 1, r = function(t) {
            var e = t && parseFloat(t.replace(",", "."));
            return (isNaN(e) ? 0 : e) * n
        },
        s = {
            "y": r(a[2]),
            "M": r(a[3]),
            "d": r(a[4]),
            "h": r(a[5]),
            "m": r(a[6]),
            "s": r(a[7]),
            "w": r(a[8])
        }),
        i = new o(s),
        le.isDuration(t) && t.hasOwnProperty("_lang") && (i._lang = t._lang),
        i
    },
    le.version = he, le.defaultFormat = ze, le.momentProperties = xe, le.updateOffset = function() {},
    le.lang = function(t, e) {
        var n;
        return t ? (e ? E(S(t), e) : null === e ? (_(t), t = "en") : $e[t] || M(t), n = le.duration.fn._lang = le.fn._lang = M(t), n._abbr) : le.fn._lang._abbr
    },
    le.langData = function(t) {
        return t && t._lang && t._lang._abbr && (t = t._lang._abbr),
        M(t)
    },
    le.isMoment = function(t) {
        return t instanceof a || null != t && t.hasOwnProperty("_isAMomentObject")
    },
    le.isDuration = function(t) {
        return t instanceof o
    },
    ce = rn.length - 1; ce >= 0; --ce) y(rn[ce]);
    le.normalizeUnits = function(t) {
        return g(t)
    },
    le.invalid = function(t) {
        var e = le.utc(0 / 0);
        return null != t ? l(e._pf, t) : e._pf.userInvalidated = !0,
        e
    },
    le.parseZone = function() {
        return le.apply(null, arguments).parseZone()
    },
    le.parseTwoDigitYear = function(t) {
        return b(t) + (b(t) > 68 ? 1900 : 2e3)
    },
    l(le.fn = a.prototype, {
        "clone": function() {
            return le(this)
        },
        "valueOf": function() {
            return + this._d + 6e4 * (this._offset || 0)
        },
        "unix": function() {
            return Math.floor( + this / 1e3)
        },
        "toString": function() {
            return this.clone().lang("en").format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ")
        },
        "toDate": function() {
            return this._offset ? new Date( + this) : this._d
        },
        "toISOString": function() {
            var t = le(this).utc();
            return 0 < t.year() && t.year() <= 9999 ? F(t, "YYYY-MM-DD[T]HH:mm:ss.SSS[Z]") : F(t, "YYYYYY-MM-DD[T]HH:mm:ss.SSS[Z]")
        },
        "toArray": function() {
            var t = this;
            return [t.year(), t.month(), t.date(), t.hours(), t.minutes(), t.seconds(), t.milliseconds()]
        },
        "isValid": function() {
            return T(this)
        },
        "isDSTShifted": function() {
            return this._a ? this.isValid() && m(this._a, (this._isUTC ? le.utc(this._a) : le(this._a)).toArray()) > 0 : !1
        },
        "parsingFlags": function() {
            return l({},
            this._pf)
        },
        "invalidAt": function() {
            return this._pf.overflow
        },
        "utc": function() {
            return this.zone(0)
        },
        "local": function() {
            return this.zone(0),
            this._isUTC = !1,
            this
        },
        "format": function(t) {
            var e = F(this, t || le.defaultFormat);
            return this.lang().postformat(e)
        },
        "add": function(t, e) {
            var n;
            return n = "string" == typeof t ? le.duration( + e, t) : le.duration(t, e),
            d(this, n, 1),
            this
        },
        "subtract": function(t, e) {
            var n;
            return n = "string" == typeof t ? le.duration( + e, t) : le.duration(t, e),
            d(this, n, -1),
            this
        },
        "diff": function(t, e, n) {
            var i, r, s = D(t, this),
            a = 6e4 * (this.zone() - s.zone());
            return e = g(e),
            "year" === e || "month" === e ? (i = 432e5 * (this.daysInMonth() + s.daysInMonth()), r = 12 * (this.year() - s.year()) + (this.month() - s.month()), r += (this - le(this).startOf("month") - (s - le(s).startOf("month"))) / i, r -= 6e4 * (this.zone() - le(this).startOf("month").zone() - (s.zone() - le(s).startOf("month").zone())) / i, "year" === e && (r /= 12)) : (i = this - s, r = "second" === e ? i / 1e3: "minute" === e ? i / 6e4: "hour" === e ? i / 36e5: "day" === e ? (i - a) / 864e5: "week" === e ? (i - a) / 6048e5: i),
            n ? r: c(r)
        },
        "from": function(t, e) {
            return le.duration(this.diff(t)).lang(this.lang()._abbr).humanize(!e)
        },
        "fromNow": function(t) {
            return this.from(le(), t)
        },
        "calendar": function() {
            var t = D(le(), this).startOf("day"),
            e = this.diff(t, "days", !0),
            n = -6 > e ? "sameElse": -1 > e ? "lastWeek": 0 > e ? "lastDay": 1 > e ? "sameDay": 2 > e ? "nextDay": 7 > e ? "nextWeek": "sameElse";
            return this.format(this.lang().calendar(n, this))
        },
        "isLeapYear": function() {
            return C(this.year())
        },
        "isDST": function() {
            return this.zone() < this.clone().month(0).zone() || this.zone() < this.clone().month(5).zone()
        },
        "day": function(t) {
            var e = this._isUTC ? this._d.getUTCDay() : this._d.getDay();
            return null != t ? (t = Q(t, this.lang()), this.add({
                "d": t - e
            })) : e
        },
        "month": re("Month", !0),
        "startOf": function(t) {
            switch (t = g(t)) {
            case "year":
                this.month(0);
            case "quarter":
            case "month":
                this.date(1);
            case "week":
            case "isoWeek":
            case "day":
                this.hours(0);
            case "hour":
                this.minutes(0);
            case "minute":
                this.seconds(0);
            case "second":
                this.milliseconds(0)
            }
            return "week" === t ? this.weekday(0) : "isoWeek" === t && this.isoWeekday(1),
            "quarter" === t && this.month(3 * Math.floor(this.month() / 3)),
            this
        },
        "endOf": function(t) {
            return t = g(t),
            this.startOf(t).add("isoWeek" === t ? "week": t, 1).subtract("ms", 1)
        },
        "isAfter": function(t, e) {
            return e = "undefined" != typeof e ? e: "millisecond",
            +this.clone().startOf(e) > +le(t).startOf(e)
        },
        "isBefore": function(t, e) {
            return e = "undefined" != typeof e ? e: "millisecond",
            +this.clone().startOf(e) < +le(t).startOf(e)
        },
        "isSame": function(t, e) {
            return e = e || "ms",
            +this.clone().startOf(e) === +D(t, this).startOf(e)
        },
        "min": function(t) {
            return t = le.apply(null, arguments),
            this > t ? this: t
        },
        "max": function(t) {
            return t = le.apply(null, arguments),
            t > this ? this: t
        },
        "zone": function(t, e) {
            var n = this._offset || 0;
            return null == t ? this._isUTC ? n: this._d.getTimezoneOffset() : ("string" == typeof t && (t = P(t)), Math.abs(t) < 16 && (t = 60 * t), this._offset = t, this._isUTC = !0, n !== t && (!e || this._changeInProgress ? d(this, le.duration(n - t, "m"), 1, !1) : this._changeInProgress || (this._changeInProgress = !0, le.updateOffset(this, !0), this._changeInProgress = null)), this)
        },
        "zoneAbbr": function() {
            return this._isUTC ? "UTC": ""
        },
        "zoneName": function() {
            return this._isUTC ? "Coordinated Universal Time": ""
        },
        "parseZone": function() {
            return this._tzm ? this.zone(this._tzm) : "string" == typeof this._i && this.zone(this._i),
            this
        },
        "hasAlignedHourOffset": function(t) {
            return t = t ? le(t).zone() : 0,
            (this.zone() - t) % 60 === 0
        },
        "daysInMonth": function() {
            return w(this.year(), this.month())
        },
        "dayOfYear": function(t) {
            var e = fe((le(this).startOf("day") - le(this).startOf("year")) / 864e5) + 1;
            return null == t ? e: this.add("d", t - e)
        },
        "quarter": function(t) {
            return null == t ? Math.ceil((this.month() + 1) / 3) : this.month(3 * (t - 1) + this.month() % 3)
        },
        "weekYear": function(t) {
            var e = X(this, this.lang()._week.dow, this.lang()._week.doy).year;
            return null == t ? e: this.add("y", t - e)
        },
        "isoWeekYear": function(t) {
            var e = X(this, 1, 4).year;
            return null == t ? e: this.add("y", t - e)
        },
        "week": function(t) {
            var e = this.lang().week(this);
            return null == t ? e: this.add("d", 7 * (t - e))
        },
        "isoWeek": function(t) {
            var e = X(this, 1, 4).week;
            return null == t ? e: this.add("d", 7 * (t - e))
        },
        "weekday": function(t) {
            var e = (this.day() + 7 - this.lang()._week.dow) % 7;
            return null == t ? e: this.add("d", t - e)
        },
        "isoWeekday": function(t) {
            return null == t ? this.day() || 7 : this.day(this.day() % 7 ? t: t - 7)
        },
        "isoWeeksInYear": function() {
            return $(this.year(), 1, 4)
        },
        "weeksInYear": function() {
            var t = this._lang._week;
            return $(this.year(), t.dow, t.doy)
        },
        "get": function(t) {
            return t = g(t),
            this[t]()
        },
        "set": function(t, e) {
            return t = g(t),
            "function" == typeof this[t] && this[t](e),
            this
        },
        "lang": function(e) {
            return e === t ? this._lang: (this._lang = M(e), this)
        }
    }),
    le.fn.millisecond = le.fn.milliseconds = re("Milliseconds", !1),
    le.fn.second = le.fn.seconds = re("Seconds", !1),
    le.fn.minute = le.fn.minutes = re("Minutes", !1),
    le.fn.hour = le.fn.hours = re("Hours", !0),
    le.fn.date = re("Date", !0),
    le.fn.dates = n("dates accessor is deprecated. Use date instead.", re("Date", !0)),
    le.fn.year = re("FullYear", !0),
    le.fn.years = n("years accessor is deprecated. Use year instead.", re("FullYear", !0)),
    le.fn.days = le.fn.day,
    le.fn.months = le.fn.month,
    le.fn.weeks = le.fn.week,
    le.fn.isoWeeks = le.fn.isoWeek,
    le.fn.quarters = le.fn.quarter,
    le.fn.toJSON = le.fn.toISOString,
    l(le.duration.fn = o.prototype, {
        "_bubble": function() {
            var t, e, n, i, r = this._milliseconds,
            s = this._days,
            a = this._months,
            o = this._data;
            o.milliseconds = r % 1e3,
            t = c(r / 1e3),
            o.seconds = t % 60,
            e = c(t / 60),
            o.minutes = e % 60,
            n = c(e / 60),
            o.hours = n % 24,
            s += c(n / 24),
            o.days = s % 30,
            a += c(s / 30),
            o.months = a % 12,
            i = c(a / 12),
            o.years = i
        },
        "weeks": function() {
            return c(this.days() / 7)
        },
        "valueOf": function() {
            return this._milliseconds + 864e5 * this._days + this._months % 12 * 2592e6 + 31536e6 * b(this._months / 12)
        },
        "humanize": function(t) {
            var e = +this,
            n = J(e, !t, this.lang());
            return t && (n = this.lang().pastFuture(e, n)),
            this.lang().postformat(n)
        },
        "add": function(t, e) {
            var n = le.duration(t, e);
            return this._milliseconds += n._milliseconds,
            this._days += n._days,
            this._months += n._months,
            this._bubble(),
            this
        },
        "subtract": function(t, e) {
            var n = le.duration(t, e);
            return this._milliseconds -= n._milliseconds,
            this._days -= n._days,
            this._months -= n._months,
            this._bubble(),
            this
        },
        "get": function(t) {
            return t = g(t),
            this[t.toLowerCase() + "s"]()
        },
        "as": function(t) {
            return t = g(t),
            this["as" + t.charAt(0).toUpperCase() + t.slice(1) + "s"]()
        },
        "lang": le.fn.lang,
        "toIsoString": function() {
            var t = Math.abs(this.years()),
            e = Math.abs(this.months()),
            n = Math.abs(this.days()),
            i = Math.abs(this.hours()),
            r = Math.abs(this.minutes()),
            s = Math.abs(this.seconds() + this.milliseconds() / 1e3);
            return this.asSeconds() ? (this.asSeconds() < 0 ? "-": "") + "P" + (t ? t + "Y": "") + (e ? e + "M": "") + (n ? n + "D": "") + (i || r || s ? "T": "") + (i ? i + "H": "") + (r ? r + "M": "") + (s ? s + "S": "") : "P0D"
        }
    });
    for (ce in Ze) Ze.hasOwnProperty(ce) && (ae(ce, Ze[ce]), se(ce.toLowerCase()));
    ae("Weeks", 6048e5),
    le.duration.fn.asMonths = function() {
        return ( + this - 31536e6 * this.years()) / 2592e6 + 12 * this.years()
    },
    le.lang("en", {
        "ordinal": function(t) {
            var e = t % 10,
            n = 1 === b(t % 100 / 10) ? "th": 1 === e ? "st": 2 === e ? "nd": 3 === e ? "rd": "th";
            return t + n
        }
    }),
    Ce ? module.exports = le: "function" == typeof define && define.amd ? (define("moment",
    function(t, e, n) {
        return n.config && n.config() && n.config().noGlobal === !0 && (de.moment = ue),
        le
    }), oe(!0)) : oe()
}.call(this),
function(t) {
    "function" == typeof define && define.amd ? define(["moment"], t) : "object" == typeof exports ? module.exports = t(require("../moment")) : t(window.moment)
} (function(t) {
    return t.lang("zh-cn", {
        "months": "\u4e00\u6708_\u4e8c\u6708_\u4e09\u6708_\u56db\u6708_\u4e94\u6708_\u516d\u6708_\u4e03\u6708_\u516b\u6708_\u4e5d\u6708_\u5341\u6708_\u5341\u4e00\u6708_\u5341\u4e8c\u6708".split("_"),
        "monthsShort": "1\u6708_2\u6708_3\u6708_4\u6708_5\u6708_6\u6708_7\u6708_8\u6708_9\u6708_10\u6708_11\u6708_12\u6708".split("_"),
        "weekdays": "\u661f\u671f\u65e5_\u661f\u671f\u4e00_\u661f\u671f\u4e8c_\u661f\u671f\u4e09_\u661f\u671f\u56db_\u661f\u671f\u4e94_\u661f\u671f\u516d".split("_"),
        "weekdaysShort": "\u5468\u65e5_\u5468\u4e00_\u5468\u4e8c_\u5468\u4e09_\u5468\u56db_\u5468\u4e94_\u5468\u516d".split("_"),
        "weekdaysMin": "\u65e5_\u4e00_\u4e8c_\u4e09_\u56db_\u4e94_\u516d".split("_"),
        "longDateFormat": {
            "LT": "Ah\u70b9mm",
            "L": "YYYY-MM-DD",
            "LL": "YYYY\u5e74MMMD\u65e5",
            "LLL": "YYYY\u5e74MMMD\u65e5LT",
            "LLLL": "YYYY\u5e74MMMD\u65e5ddddLT",
            "l": "YYYY-MM-DD",
            "ll": "YYYY\u5e74MMMD\u65e5",
            "lll": "YYYY\u5e74MMMD\u65e5LT",
            "llll": "YYYY\u5e74MMMD\u65e5ddddLT"
        },
        "meridiem": function(t, e) {
            var n = 100 * t + e;
            return 600 > n ? "\u51cc\u6668": 900 > n ? "\u65e9\u4e0a": 1130 > n ? "\u4e0a\u5348": 1230 > n ? "\u4e2d\u5348": 1800 > n ? "\u4e0b\u5348": "\u665a\u4e0a"
        },
        "calendar": {
            "sameDay": function() {
                return 0 === this.minutes() ? "[\u4eca\u5929]Ah[\u70b9\u6574]": "[\u4eca\u5929]LT"
            },
            "nextDay": function() {
                return 0 === this.minutes() ? "[\u660e\u5929]Ah[\u70b9\u6574]": "[\u660e\u5929]LT"
            },
            "lastDay": function() {
                return 0 === this.minutes() ? "[\u6628\u5929]Ah[\u70b9\u6574]": "[\u6628\u5929]LT"
            },
            "nextWeek": function() {
                var e, n;
                return e = t().startOf("week"),
                n = this.unix() - e.unix() >= 604800 ? "[\u4e0b]": "[\u672c]",
                0 === this.minutes() ? n + "dddAh\u70b9\u6574": n + "dddAh\u70b9mm"
            },
            "lastWeek": function() {
                var e, n;
                return e = t().startOf("week"),
                n = this.unix() < e.unix() ? "[\u4e0a]": "[\u672c]",
                0 === this.minutes() ? n + "dddAh\u70b9\u6574": n + "dddAh\u70b9mm"
            },
            "sameElse": "LL"
        },
        "ordinal": function(t, e) {
            switch (e) {
            case "d":
            case "D":
            case "DDD":
                return t + "\u65e5";
            case "M":
                return t + "\u6708";
            case "w":
            case "W":
                return t + "\u5468";
            default:
                return t
            }
        },
        "relativeTime": {
            "future": "%s\u5185",
            "past": "%s\u524d",
            "s": "\u51e0\u79d2",
            "m": "1\u5206\u949f",
            "mm": "%d\u5206\u949f",
            "h": "1\u5c0f\u65f6",
            "hh": "%d\u5c0f\u65f6",
            "d": "1\u5929",
            "dd": "%d\u5929",
            "M": "1\u4e2a\u6708",
            "MM": "%d\u4e2a\u6708",
            "y": "1\u5e74",
            "yy": "%d\u5e74"
        },
        "week": {
            "dow": 1,
            "doy": 4
        }
    })
}),
function(t, e) {
    if ("object" == typeof exports && exports) e(exports);
    else {
        var n = {};
        e(n),
        "function" == typeof define && define.amd ? define(n) : t.Mustache = n
    }
} (this,
function(t) {
    function e(t, e) {
        return b.call(t, e)
    }
    function n(t) {
        return ! e(m, t)
    }
    function i(t) {
        return "function" == typeof t
    }
    function r(t) {
        return t.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&")
    }
    function s(t) {
        return String(t).replace(/[&<>"'\/]/g,
        function(t) {
            return x[t]
        })
    }
    function a(t) {
        if (!$(t) || 2 !== t.length) throw new Error("Invalid tags: " + t);
        return [new RegExp(r(t[0]) + "\\s*"), new RegExp("\\s*" + r(t[1]))]
    }
    function o(e, i) {
        function s() {
            if (S && !D) for (; T.length;) delete k[T.pop()];
            else T = [];
            S = !1,
            D = !1
        }
        i = i || t.tags,
        e = e || "",
        "string" == typeof i && (i = i.split(p));
        for (var o, h, d, m, b, w, $ = a(i), x = new c(e), C = [], k = [], T = [], S = !1, D = !1; ! x.eos();) {
            if (o = x.pos, d = x.scanUntil($[0])) for (var E = 0,
            _ = d.length; _ > E; ++E) m = d.charAt(E),
            n(m) ? T.push(k.length) : D = !0,
            k.push(["text", m, o, o + 1]),
            o += 1,
            "\n" === m && s();
            if (!x.scan($[0])) break;
            if (S = !0, h = x.scan(y) || "name", x.scan(f), "=" === h ? (d = x.scanUntil(g), x.scan(g), x.scanUntil($[1])) : "{" === h ? (d = x.scanUntil(new RegExp("\\s*" + r("}" + i[1]))), x.scan(v), x.scanUntil($[1]), h = "&") : d = x.scanUntil($[1]), !x.scan($[1])) throw new Error("Unclosed tag at " + x.pos);
            if (b = [h, d, o, x.pos], k.push(b), "#" === h || "^" === h) C.push(b);
            else if ("/" === h) {
                if (w = C.pop(), !w) throw new Error('Unopened section "' + d + '" at ' + o);
                if (w[1] !== d) throw new Error('Unclosed section "' + w[1] + '" at ' + o)
            } else "name" === h || "{" === h || "&" === h ? D = !0 : "=" === h && ($ = a(i = d.split(p)))
        }
        if (w = C.pop()) throw new Error('Unclosed section "' + w[1] + '" at ' + x.pos);
        return u(l(k))
    }
    function l(t) {
        for (var e, n, i = [], r = 0, s = t.length; s > r; ++r) e = t[r],
        e && ("text" === e[0] && n && "text" === n[0] ? (n[1] += e[1], n[3] = e[3]) : (i.push(e), n = e));
        return i
    }
    function u(t) {
        for (var e, n, i = [], r = i, s = [], a = 0, o = t.length; o > a; ++a) switch (e = t[a], e[0]) {
        case "#":
        case "^":
            r.push(e),
            s.push(e),
            r = e[4] = [];
            break;
        case "/":
            n = s.pop(),
            n[5] = e[2],
            r = s.length > 0 ? s[s.length - 1][4] : i;
            break;
        default:
            r.push(e)
        }
        return i
    }
    function c(t) {
        this.string = t,
        this.tail = t,
        this.pos = 0
    }
    function h(t, e) {
        this.view = null == t ? {}: t,
        this.cache = {
            ".": this.view
        },
        this.parent = e
    }
    function d() {
        this.cache = {}
    }
    var f = /\s*/,
    p = /\s+/,
    m = /\S/,
    g = /\s*=/,
    v = /\s*\}/,
    y = /#|\^|\/|>|\{|&|=|!/,
    b = RegExp.prototype.test,
    w = Object.prototype.toString,
    $ = Array.isArray ||
    function(t) {
        return "[object Array]" === w.call(t)
    },
    x = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;",
        "/": "&#x2F;"
    };
    c.prototype.eos = function() {
        return "" === this.tail
    },
    c.prototype.scan = function(t) {
        var e = this.tail.match(t);
        if (e && 0 === e.index) {
            var n = e[0];
            return this.tail = this.tail.substring(n.length),
            this.pos += n.length,
            n
        }
        return ""
    },
    c.prototype.scanUntil = function(t) {
        var e, n = this.tail.search(t);
        switch (n) {
        case - 1 : e = this.tail,
            this.tail = "";
            break;
        case 0:
            e = "";
            break;
        default:
            e = this.tail.substring(0, n),
            this.tail = this.tail.substring(n)
        }
        return this.pos += e.length,
        e
    },
    h.prototype.push = function(t) {
        return new h(t, this)
    },
    h.prototype.lookup = function(t) {
        var e;
        if (t in this.cache) e = this.cache[t];
        else {
            for (var n = this; n;) {
                if (t.indexOf(".") > 0) {
                    e = n.view;
                    for (var r = t.split("."), s = 0; null != e && s < r.length;) e = e[r[s++]]
                } else e = n.view[t];
                if (null != e) break;
                n = n.parent
            }
            this.cache[t] = e
        }
        return i(e) && (e = e.call(this.view)),
        e
    },
    d.prototype.clearCache = function() {
        this.cache = {}
    },
    d.prototype.parse = function(t, e) {
        var n = this.cache,
        i = n[t];
        return null == i && (i = n[t] = o(t, e)),
        i
    },
    d.prototype.render = function(t, e, n) {
        var i = this.parse(t),
        r = e instanceof h ? e: new h(e);
        return this.renderTokens(i, r, n, t)
    },
    d.prototype.renderTokens = function(e, n, r, s) {
        function a(t) {
            return c.render(t, n, r)
        }
        for (var o, l, u = "",
        c = this,
        h = 0,
        d = e.length; d > h; ++h) switch (o = e[h], o[0]) {
        case "#":
            if (l = n.lookup(o[1]), !l) continue;
            if ($(l)) for (var f = 0,
            p = l.length; p > f; ++f) u += this.renderTokens(o[4], n.push(l[f]), r, s);
            else if ("object" == typeof l || "string" == typeof l) u += this.renderTokens(o[4], n.push(l), r, s);
            else if (i(l)) {
                if ("string" != typeof s) throw new Error("Cannot use higher-order sections without the original template");
                l = l.call(n.view, s.slice(o[3], o[5]), a),
                null != l && (u += l)
            } else u += this.renderTokens(o[4], n, r, s);
            break;
        case "^":
            l = n.lookup(o[1]),
            (!l || $(l) && 0 === l.length) && (u += this.renderTokens(o[4], n, r, s));
            break;
        case ">":
            if (!r) continue;
            l = i(r) ? r(o[1]) : r[o[1]],
            null != l && (u += this.renderTokens(this.parse(l), n, r, l));
            break;
        case "&":
            l = n.lookup(o[1]),
            null != l && (u += l);
            break;
        case "name":
            l = n.lookup(o[1]),
            null != l && (u += t.escape(l));
            break;
        case "text":
            u += o[1]
        }
        return u
    },
    t.name = "mustache.js",
    t.version = "0.8.1",
    t.tags = ["{{", "}}"];
    var C = new d;
    t.clearCache = function() {
        return C.clearCache()
    },
    t.parse = function(t, e) {
        return C.parse(t, e)
    },
    t.render = function(t, e, n) {
        return C.render(t, e, n)
    },
    t.to_html = function(e, n, r, s) {
        var a = t.render(e, n, r);
        return i(s) ? void s(a) : a
    },
    t.escape = s,
    t.Scanner = c,
    t.Context = h,
    t.Writer = d
}),
function(t, e) {
    "object" == typeof module && "object" == typeof module.exports ? module.exports = t.document ? e(t, !0) : function(t) {
        if (!t.document) throw new Error("jQuery requires a window with a document");
        return e(t)
    }: e(t)
} ("undefined" != typeof window ? window: this,
function(t, e) {
    function n(t) {
        var e = t.length,
        n = se.type(t);
        return "function" === n || se.isWindow(t) ? !1 : 1 === t.nodeType && e ? !0 : "array" === n || 0 === e || "number" == typeof e && e > 0 && e - 1 in t
    }
    function i(t, e, n) {
        if (se.isFunction(e)) return se.grep(t,
        function(t, i) {
            return !! e.call(t, i, t) !== n
        });
        if (e.nodeType) return se.grep(t,
        function(t) {
            return t === e !== n
        });
        if ("string" == typeof e) {
            if (fe.test(e)) return se.filter(e, t, n);
            e = se.filter(e, t)
        }
        return se.grep(t,
        function(t) {
            return se.inArray(t, e) >= 0 !== n
        })
    }
    function r(t, e) {
        do t = t[e];
        while (t && 1 !== t.nodeType);
        return t
    }
    function s(t) {
        var e = $e[t] = {};
        return se.each(t.match(we) || [],
        function(t, n) {
            e[n] = !0
        }),
        e
    }
    function a() {
        me.addEventListener ? (me.removeEventListener("DOMContentLoaded", o, !1), t.removeEventListener("load", o, !1)) : (me.detachEvent("onreadystatechange", o), t.detachEvent("onload", o))
    }
    function o() { (me.addEventListener || "load" === event.type || "complete" === me.readyState) && (a(), se.ready())
    }
    function l(t, e, n) {
        if (void 0 === n && 1 === t.nodeType) {
            var i = "data-" + e.replace(Se, "-$1").toLowerCase();
            if (n = t.getAttribute(i), "string" == typeof n) {
                try {
                    n = "true" === n ? !0 : "false" === n ? !1 : "null" === n ? null: +n + "" === n ? +n: Te.test(n) ? se.parseJSON(n) : n
                } catch(r) {}
                se.data(t, e, n)
            } else n = void 0
        }
        return n
    }
    function u(t) {
        var e;
        for (e in t) if (("data" !== e || !se.isEmptyObject(t[e])) && "toJSON" !== e) return ! 1;
        return ! 0
    }
    function c(t, e, n, i) {
        if (se.acceptData(t)) {
            var r, s, a = se.expando,
            o = t.nodeType,
            l = o ? se.cache: t,
            u = o ? t[a] : t[a] && a;
            if (u && l[u] && (i || l[u].data) || void 0 !== n || "string" != typeof e) return u || (u = o ? t[a] = G.pop() || se.guid++:a),
            l[u] || (l[u] = o ? {}: {
                "toJSON": se.noop
            }),
            ("object" == typeof e || "function" == typeof e) && (i ? l[u] = se.extend(l[u], e) : l[u].data = se.extend(l[u].data, e)),
            s = l[u],
            i || (s.data || (s.data = {}), s = s.data),
            void 0 !== n && (s[se.camelCase(e)] = n),
            "string" == typeof e ? (r = s[e], null == r && (r = s[se.camelCase(e)])) : r = s,
            r
        }
    }
    function h(t, e, n) {
        if (se.acceptData(t)) {
            var i, r, s = t.nodeType,
            a = s ? se.cache: t,
            o = s ? t[se.expando] : se.expando;
            if (a[o]) {
                if (e && (i = n ? a[o] : a[o].data)) {
                    se.isArray(e) ? e = e.concat(se.map(e, se.camelCase)) : e in i ? e = [e] : (e = se.camelCase(e), e = e in i ? [e] : e.split(" ")),
                    r = e.length;
                    for (; r--;) delete i[e[r]];
                    if (n ? !u(i) : !se.isEmptyObject(i)) return
                } (n || (delete a[o].data, u(a[o]))) && (s ? se.cleanData([t], !0) : ie.deleteExpando || a != a.window ? delete a[o] : a[o] = null)
            }
        }
    }
    function d() {
        return ! 0
    }
    function f() {
        return ! 1
    }
    function p() {
        try {
            return me.activeElement
        } catch(t) {}
    }
    function m(t) {
        var e = Ie.split("|"),
        n = t.createDocumentFragment();
        if (n.createElement) for (; e.length;) n.createElement(e.pop());
        return n
    }
    function g(t, e) {
        var n, i, r = 0,
        s = typeof t.getElementsByTagName !== ke ? t.getElementsByTagName(e || "*") : typeof t.querySelectorAll !== ke ? t.querySelectorAll(e || "*") : void 0;
        if (!s) for (s = [], n = t.childNodes || t; null != (i = n[r]); r++) ! e || se.nodeName(i, e) ? s.push(i) : se.merge(s, g(i, e));
        return void 0 === e || e && se.nodeName(t, e) ? se.merge([t], s) : s
    }
    function v(t) {
        Ae.test(t.type) && (t.defaultChecked = t.checked)
    }
    function y(t, e) {
        return se.nodeName(t, "table") && se.nodeName(11 !== e.nodeType ? e: e.firstChild, "tr") ? t.getElementsByTagName("tbody")[0] || t.appendChild(t.ownerDocument.createElement("tbody")) : t
    }
    function b(t) {
        return t.type = (null !== se.find.attr(t, "type")) + "/" + t.type,
        t
    }
    function w(t) {
        var e = Ge.exec(t.type);
        return e ? t.type = e[1] : t.removeAttribute("type"),
        t
    }
    function $(t, e) {
        for (var n, i = 0; null != (n = t[i]); i++) se._data(n, "globalEval", !e || se._data(e[i], "globalEval"))
    }
    function x(t, e) {
        if (1 === e.nodeType && se.hasData(t)) {
            var n, i, r, s = se._data(t),
            a = se._data(e, s),
            o = s.events;
            if (o) {
                delete a.handle,
                a.events = {};
                for (n in o) for (i = 0, r = o[n].length; r > i; i++) se.event.add(e, n, o[n][i])
            }
            a.data && (a.data = se.extend({},
            a.data))
        }
    }
    function C(t, e) {
        var n, i, r;
        if (1 === e.nodeType) {
            if (n = e.nodeName.toLowerCase(), !ie.noCloneEvent && e[se.expando]) {
                r = se._data(e);
                for (i in r.events) se.removeEvent(e, i, r.handle);
                e.removeAttribute(se.expando)
            }
            "script" === n && e.text !== t.text ? (b(e).text = t.text, w(e)) : "object" === n ? (e.parentNode && (e.outerHTML = t.outerHTML), ie.html5Clone && t.innerHTML && !se.trim(e.innerHTML) && (e.innerHTML = t.innerHTML)) : "input" === n && Ae.test(t.type) ? (e.defaultChecked = e.checked = t.checked, e.value !== t.value && (e.value = t.value)) : "option" === n ? e.defaultSelected = e.selected = t.defaultSelected: ("input" === n || "textarea" === n) && (e.defaultValue = t.defaultValue)
        }
    }
    function k(e, n) {
        var i = se(n.createElement(e)).appendTo(n.body),
        r = t.getDefaultComputedStyle ? t.getDefaultComputedStyle(i[0]).display: se.css(i[0], "display");
        return i.detach(),
        r
    }
    function T(t) {
        var e = me,
        n = tn[t];
        return n || (n = k(t, e), "none" !== n && n || (Ke = (Ke || se("<iframe frameborder='0' width='0' height='0'/>")).appendTo(e.documentElement), e = (Ke[0].contentWindow || Ke[0].contentDocument).document, e.write(), e.close(), n = k(t, e), Ke.detach()), tn[t] = n),
        n
    }
    function S(t, e) {
        return {
            "get": function() {
                var n = t();
                if (null != n) return n ? void delete this.get: (this.get = e).apply(this, arguments)
            }
        }
    }
    function D(t, e) {
        if (e in t) return e;
        for (var n = e.charAt(0).toUpperCase() + e.slice(1), i = e, r = pn.length; r--;) if (e = pn[r] + n, e in t) return e;
        return i
    }
    function E(t, e) {
        for (var n, i, r, s = [], a = 0, o = t.length; o > a; a++) i = t[a],
        i.style && (s[a] = se._data(i, "olddisplay"), n = i.style.display, e ? (s[a] || "none" !== n || (i.style.display = ""), "" === i.style.display && _e(i) && (s[a] = se._data(i, "olddisplay", T(i.nodeName)))) : s[a] || (r = _e(i), (n && "none" !== n || !r) && se._data(i, "olddisplay", r ? n: se.css(i, "display"))));
        for (a = 0; o > a; a++) i = t[a],
        i.style && (e && "none" !== i.style.display && "" !== i.style.display || (i.style.display = e ? s[a] || "": "none"));
        return t
    }
    function _(t, e, n) {
        var i = cn.exec(e);
        return i ? Math.max(0, i[1] - (n || 0)) + (i[2] || "px") : e
    }
    function M(t, e, n, i, r) {
        for (var s = n === (i ? "border": "content") ? 4 : "width" === e ? 1 : 0, a = 0; 4 > s; s += 2)"margin" === n && (a += se.css(t, n + Ee[s], !0, r)),
        i ? ("content" === n && (a -= se.css(t, "padding" + Ee[s], !0, r)), "margin" !== n && (a -= se.css(t, "border" + Ee[s] + "Width", !0, r))) : (a += se.css(t, "padding" + Ee[s], !0, r), "padding" !== n && (a += se.css(t, "border" + Ee[s] + "Width", !0, r)));
        return a
    }
    function A(t, e, n) {
        var i = !0,
        r = "width" === e ? t.offsetWidth: t.offsetHeight,
        s = en(t),
        a = ie.boxSizing() && "border-box" === se.css(t, "boxSizing", !1, s);
        if (0 >= r || null == r) {
            if (r = nn(t, e, s), (0 > r || null == r) && (r = t.style[e]), sn.test(r)) return r;
            i = a && (ie.boxSizingReliable() || r === t.style[e]),
            r = parseFloat(r) || 0
        }
        return r + M(t, e, n || (a ? "border": "content"), i, s) + "px"
    }
    function O(t, e, n, i, r) {
        return new O.prototype.init(t, e, n, i, r)
    }
    function F() {
        return setTimeout(function() {
            mn = void 0
        }),
        mn = se.now()
    }
    function N(t, e) {
        var n, i = {
            "height": t
        },
        r = 0;
        for (e = e ? 1 : 0; 4 > r; r += 2 - e) n = Ee[r],
        i["margin" + n] = i["padding" + n] = t;
        return e && (i.opacity = i.width = t),
        i
    }
    function j(t, e, n) {
        for (var i, r = ($n[e] || []).concat($n["*"]), s = 0, a = r.length; a > s; s++) if (i = r[s].call(n, e, t)) return i
    }
    function P(t, e, n) {
        var i, r, s, a, o, l, u, c, h = this,
        d = {},
        f = t.style,
        p = t.nodeType && _e(t),
        m = se._data(t, "fxshow");
        n.queue || (o = se._queueHooks(t, "fx"), null == o.unqueued && (o.unqueued = 0, l = o.empty.fire, o.empty.fire = function() {
            o.unqueued || l()
        }), o.unqueued++, h.always(function() {
            h.always(function() {
                o.unqueued--,
                se.queue(t, "fx").length || o.empty.fire()
            })
        })),
        1 === t.nodeType && ("height" in e || "width" in e) && (n.overflow = [f.overflow, f.overflowX, f.overflowY], u = se.css(t, "display"), c = T(t.nodeName), "none" === u && (u = c), "inline" === u && "none" === se.css(t, "float") && (ie.inlineBlockNeedsLayout && "inline" !== c ? f.zoom = 1 : f.display = "inline-block")),
        n.overflow && (f.overflow = "hidden", ie.shrinkWrapBlocks() || h.always(function() {
            f.overflow = n.overflow[0],
            f.overflowX = n.overflow[1],
            f.overflowY = n.overflow[2]
        }));
        for (i in e) if (r = e[i], vn.exec(r)) {
            if (delete e[i], s = s || "toggle" === r, r === (p ? "hide": "show")) {
                if ("show" !== r || !m || void 0 === m[i]) continue;
                p = !0
            }
            d[i] = m && m[i] || se.style(t, i)
        }
        if (!se.isEmptyObject(d)) {
            m ? "hidden" in m && (p = m.hidden) : m = se._data(t, "fxshow", {}),
            s && (m.hidden = !p),
            p ? se(t).show() : h.done(function() {
                se(t).hide()
            }),
            h.done(function() {
                var e;
                se._removeData(t, "fxshow");
                for (e in d) se.style(t, e, d[e])
            });
            for (i in d) a = j(p ? m[i] : 0, i, h),
            i in m || (m[i] = a.start, p && (a.end = a.start, a.start = "width" === i || "height" === i ? 1 : 0))
        }
    }
    function I(t, e) {
        var n, i, r, s, a;
        for (n in t) if (i = se.camelCase(n), r = e[i], s = t[n], se.isArray(s) && (r = s[1], s = t[n] = s[0]), n !== i && (t[i] = s, delete t[n]), a = se.cssHooks[i], a && "expand" in a) {
            s = a.expand(s),
            delete t[i];
            for (n in s) n in t || (t[n] = s[n], e[n] = r)
        } else e[i] = r
    }
    function L(t, e, n) {
        var i, r, s = 0,
        a = wn.length,
        o = se.Deferred().always(function() {
            delete l.elem
        }),
        l = function() {
            if (r) return ! 1;
            for (var e = mn || F(), n = Math.max(0, u.startTime + u.duration - e), i = n / u.duration || 0, s = 1 - i, a = 0, l = u.tweens.length; l > a; a++) u.tweens[a].run(s);
            return o.notifyWith(t, [u, s, n]),
            1 > s && l ? n: (o.resolveWith(t, [u]), !1)
        },
        u = o.promise({
            "elem": t,
            "props": se.extend({},
            e),
            "opts": se.extend(!0, {
                "specialEasing": {}
            },
            n),
            "originalProperties": e,
            "originalOptions": n,
            "startTime": mn || F(),
            "duration": n.duration,
            "tweens": [],
            "createTween": function(e, n) {
                var i = se.Tween(t, u.opts, e, n, u.opts.specialEasing[e] || u.opts.easing);
                return u.tweens.push(i),
                i
            },
            "stop": function(e) {
                var n = 0,
                i = e ? u.tweens.length: 0;
                if (r) return this;
                for (r = !0; i > n; n++) u.tweens[n].run(1);
                return e ? o.resolveWith(t, [u, e]) : o.rejectWith(t, [u, e]),
                this
            }
        }),
        c = u.props;
        for (I(c, u.opts.specialEasing); a > s; s++) if (i = wn[s].call(u, t, c, u.opts)) return i;
        return se.map(c, j, u),
        se.isFunction(u.opts.start) && u.opts.start.call(t, u),
        se.fx.timer(se.extend(l, {
            "elem": t,
            "anim": u,
            "queue": u.opts.queue
        })),
        u.progress(u.opts.progress).done(u.opts.done, u.opts.complete).fail(u.opts.fail).always(u.opts.always)
    }
    function H(t) {
        return function(e, n) {
            "string" != typeof e && (n = e, e = "*");
            var i, r = 0,
            s = e.toLowerCase().match(we) || [];
            if (se.isFunction(n)) for (; i = s[r++];)"+" === i.charAt(0) ? (i = i.slice(1) || "*", (t[i] = t[i] || []).unshift(n)) : (t[i] = t[i] || []).push(n)
        }
    }
    function R(t, e, n, i) {
        function r(o) {
            var l;
            return s[o] = !0,
            se.each(t[o] || [],
            function(t, o) {
                var u = o(e, n, i);
                return "string" != typeof u || a || s[u] ? a ? !(l = u) : void 0 : (e.dataTypes.unshift(u), r(u), !1)
            }),
            l
        }
        var s = {},
        a = t === Wn;
        return r(e.dataTypes[0]) || !s["*"] && r("*")
    }
    function U(t, e) {
        var n, i, r = se.ajaxSettings.flatOptions || {};
        for (i in e) void 0 !== e[i] && ((r[i] ? t: n || (n = {}))[i] = e[i]);
        return n && se.extend(!0, t, n),
        t
    }
    function q(t, e, n) {
        for (var i, r, s, a, o = t.contents,
        l = t.dataTypes;
        "*" === l[0];) l.shift(),
        void 0 === r && (r = t.mimeType || e.getResponseHeader("Content-Type"));
        if (r) for (a in o) if (o[a] && o[a].test(r)) {
            l.unshift(a);
            break
        }
        if (l[0] in n) s = l[0];
        else {
            for (a in n) {
                if (!l[0] || t.converters[a + " " + l[0]]) {
                    s = a;
                    break
                }
                i || (i = a)
            }
            s = s || i
        }
        return s ? (s !== l[0] && l.unshift(s), n[s]) : void 0
    }
    function Y(t, e, n, i) {
        var r, s, a, o, l, u = {},
        c = t.dataTypes.slice();
        if (c[1]) for (a in t.converters) u[a.toLowerCase()] = t.converters[a];
        for (s = c.shift(); s;) if (t.responseFields[s] && (n[t.responseFields[s]] = e), !l && i && t.dataFilter && (e = t.dataFilter(e, t.dataType)), l = s, s = c.shift()) if ("*" === s) s = l;
        else if ("*" !== l && l !== s) {
            if (a = u[l + " " + s] || u["* " + s], !a) for (r in u) if (o = r.split(" "), o[1] === s && (a = u[l + " " + o[0]] || u["* " + o[0]])) {
                a === !0 ? a = u[r] : u[r] !== !0 && (s = o[0], c.unshift(o[1]));
                break
            }
            if (a !== !0) if (a && t["throws"]) e = a(e);
            else try {
                e = a(e)
            } catch(h) {
                return {
                    "state": "parsererror",
                    "error": a ? h: "No conversion from " + l + " to " + s
                }
            }
        }
        return {
            "state": "success",
            "data": e
        }
    }
    function V(t, e, n, i) {
        var r;
        if (se.isArray(e)) se.each(e,
        function(e, r) {
            n || Qn.test(t) ? i(t, r) : V(t + "[" + ("object" == typeof r ? e: "") + "]", r, n, i)
        });
        else if (n || "object" !== se.type(e)) i(t, e);
        else for (r in e) V(t + "[" + r + "]", e[r], n, i)
    }
    function W() {
        try {
            return new t.XMLHttpRequest
        } catch(e) {}
    }
    function z() {
        try {
            return new t.ActiveXObject("Microsoft.XMLHTTP")
        } catch(e) {}
    }
    function B(t) {
        return se.isWindow(t) ? t: 9 === t.nodeType ? t.defaultView || t.parentWindow: !1
    }
    var G = [],
    Q = G.slice,
    Z = G.concat,
    J = G.push,
    X = G.indexOf,
    K = {},
    te = K.toString,
    ee = K.hasOwnProperty,
    ne = "".trim,
    ie = {},
    re = "1.11.0",
    se = function(t, e) {
        return new se.fn.init(t, e)
    },
    ae = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,
    oe = /^-ms-/,
    le = /-([\da-z])/gi,
    ue = function(t, e) {
        return e.toUpperCase()
    };
    se.fn = se.prototype = {
        "jquery": re,
        "constructor": se,
        "selector": "",
        "length": 0,
        "toArray": function() {
            return Q.call(this)
        },
        "get": function(t) {
            return null != t ? 0 > t ? this[t + this.length] : this[t] : Q.call(this)
        },
        "pushStack": function(t) {
            var e = se.merge(this.constructor(), t);
            return e.prevObject = this,
            e.context = this.context,
            e
        },
        "each": function(t, e) {
            return se.each(this, t, e)
        },
        "map": function(t) {
            return this.pushStack(se.map(this,
            function(e, n) {
                return t.call(e, n, e)
            }))
        },
        "slice": function() {
            return this.pushStack(Q.apply(this, arguments))
        },
        "first": function() {
            return this.eq(0)
        },
        "last": function() {
            return this.eq( - 1)
        },
        "eq": function(t) {
            var e = this.length,
            n = +t + (0 > t ? e: 0);
            return this.pushStack(n >= 0 && e > n ? [this[n]] : [])
        },
        "end": function() {
            return this.prevObject || this.constructor(null)
        },
        "push": J,
        "sort": G.sort,
        "splice": G.splice
    },
    se.extend = se.fn.extend = function() {
        var t, e, n, i, r, s, a = arguments[0] || {},
        o = 1,
        l = arguments.length,
        u = !1;
        for ("boolean" == typeof a && (u = a, a = arguments[o] || {},
        o++), "object" == typeof a || se.isFunction(a) || (a = {}), o === l && (a = this, o--); l > o; o++) if (null != (r = arguments[o])) for (i in r) t = a[i],
        n = r[i],
        a !== n && (u && n && (se.isPlainObject(n) || (e = se.isArray(n))) ? (e ? (e = !1, s = t && se.isArray(t) ? t: []) : s = t && se.isPlainObject(t) ? t: {},
        a[i] = se.extend(u, s, n)) : void 0 !== n && (a[i] = n));
        return a
    },
    se.extend({
        "expando": "jQuery" + (re + Math.random()).replace(/\D/g, ""),
        "isReady": !0,
        "error": function(t) {
            throw new Error(t)
        },
        "noop": function() {},
        "isFunction": function(t) {
            return "function" === se.type(t)
        },
        "isArray": Array.isArray ||
        function(t) {
            return "array" === se.type(t)
        },
        "isWindow": function(t) {
            return null != t && t == t.window
        },
        "isNumeric": function(t) {
            return t - parseFloat(t) >= 0
        },
        "isEmptyObject": function(t) {
            var e;
            for (e in t) return ! 1;
            return ! 0
        },
        "isPlainObject": function(t) {
            var e;
            if (!t || "object" !== se.type(t) || t.nodeType || se.isWindow(t)) return ! 1;
            try {
                if (t.constructor && !ee.call(t, "constructor") && !ee.call(t.constructor.prototype, "isPrototypeOf")) return ! 1
            } catch(n) {
                return ! 1
            }
            if (ie.ownLast) for (e in t) return ee.call(t, e);
            for (e in t);
            return void 0 === e || ee.call(t, e)
        },
        "type": function(t) {
            return null == t ? t + "": "object" == typeof t || "function" == typeof t ? K[te.call(t)] || "object": typeof t
        },
        "globalEval": function(e) {
            e && se.trim(e) && (t.execScript ||
            function(e) {
                t.eval.call(t, e)
            })(e)
        },
        "camelCase": function(t) {
            return t.replace(oe, "ms-").replace(le, ue)
        },
        "nodeName": function(t, e) {
            return t.nodeName && t.nodeName.toLowerCase() === e.toLowerCase()
        },
        "each": function(t, e, i) {
            var r, s = 0,
            a = t.length,
            o = n(t);
            if (i) {
                if (o) for (; a > s && (r = e.apply(t[s], i), r !== !1); s++);
                else for (s in t) if (r = e.apply(t[s], i), r === !1) break
            } else if (o) for (; a > s && (r = e.call(t[s], s, t[s]), r !== !1); s++);
            else for (s in t) if (r = e.call(t[s], s, t[s]), r === !1) break;
            return t
        },
        "trim": ne && !ne.call("\ufeff\xa0") ?
        function(t) {
            return null == t ? "": ne.call(t)
        }: function(t) {
            return null == t ? "": (t + "").replace(ae, "")
        },
        "makeArray": function(t, e) {
            var i = e || [];
            return null != t && (n(Object(t)) ? se.merge(i, "string" == typeof t ? [t] : t) : J.call(i, t)),
            i
        },
        "inArray": function(t, e, n) {
            var i;
            if (e) {
                if (X) return X.call(e, t, n);
                for (i = e.length, n = n ? 0 > n ? Math.max(0, i + n) : n: 0; i > n; n++) if (n in e && e[n] === t) return n
            }
            return - 1
        },
        "merge": function(t, e) {
            for (var n = +e.length,
            i = 0,
            r = t.length; n > i;) t[r++] = e[i++];
            if (n !== n) for (; void 0 !== e[i];) t[r++] = e[i++];
            return t.length = r,
            t
        },
        "grep": function(t, e, n) {
            for (var i, r = [], s = 0, a = t.length, o = !n; a > s; s++) i = !e(t[s], s),
            i !== o && r.push(t[s]);
            return r
        },
        "map": function(t, e, i) {
            var r, s = 0,
            a = t.length,
            o = n(t),
            l = [];
            if (o) for (; a > s; s++) r = e(t[s], s, i),
            null != r && l.push(r);
            else for (s in t) r = e(t[s], s, i),
            null != r && l.push(r);
            return Z.apply([], l)
        },
        "guid": 1,
        "proxy": function(t, e) {
            var n, i, r;
            return "string" == typeof e && (r = t[e], e = t, t = r),
            se.isFunction(t) ? (n = Q.call(arguments, 2), i = function() {
                return t.apply(e || this, n.concat(Q.call(arguments)))
            },
            i.guid = t.guid = t.guid || se.guid++, i) : void 0
        },
        "now": function() {
            return + new Date
        },
        "support": ie
    }),
    se.each("Boolean Number String Function Array Date RegExp Object Error".split(" "),
    function(t, e) {
        K["[object " + e + "]"] = e.toLowerCase()
    });
    var ce = function(t) {
        function e(t, e, n, i) {
            var r, s, a, o, l, u, h, p, m, g;
            if ((e ? e.ownerDocument || e: R) !== O && A(e), e = e || O, n = n || [], !t || "string" != typeof t) return n;
            if (1 !== (o = e.nodeType) && 9 !== o) return [];
            if (N && !i) {
                if (r = ye.exec(t)) if (a = r[1]) {
                    if (9 === o) {
                        if (s = e.getElementById(a), !s || !s.parentNode) return n;
                        if (s.id === a) return n.push(s),
                        n
                    } else if (e.ownerDocument && (s = e.ownerDocument.getElementById(a)) && L(e, s) && s.id === a) return n.push(s),
                    n
                } else {
                    if (r[2]) return K.apply(n, e.getElementsByTagName(t)),
                    n;
                    if ((a = r[3]) && C.getElementsByClassName && e.getElementsByClassName) return K.apply(n, e.getElementsByClassName(a)),
                    n
                }
                if (C.qsa && (!j || !j.test(t))) {
                    if (p = h = H, m = e, g = 9 === o && t, 1 === o && "object" !== e.nodeName.toLowerCase()) {
                        for (u = d(t), (h = e.getAttribute("id")) ? p = h.replace(we, "\\$&") : e.setAttribute("id", p), p = "[id='" + p + "'] ", l = u.length; l--;) u[l] = p + f(u[l]);
                        m = be.test(t) && c(e.parentNode) || e,
                        g = u.join(",")
                    }
                    if (g) try {
                        return K.apply(n, m.querySelectorAll(g)),
                        n
                    } catch(v) {} finally {
                        h || e.removeAttribute("id")
                    }
                }
            }
            return $(t.replace(le, "$1"), e, n, i)
        }
        function n() {
            function t(n, i) {
                return e.push(n + " ") > k.cacheLength && delete t[e.shift()],
                t[n + " "] = i
            }
            var e = [];
            return t
        }
        function i(t) {
            return t[H] = !0,
            t
        }
        function r(t) {
            var e = O.createElement("div");
            try {
                return !! t(e)
            } catch(n) {
                return ! 1
            } finally {
                e.parentNode && e.parentNode.removeChild(e),
                e = null
            }
        }
        function s(t, e) {
            for (var n = t.split("|"), i = t.length; i--;) k.attrHandle[n[i]] = e
        }
        function a(t, e) {
            var n = e && t,
            i = n && 1 === t.nodeType && 1 === e.nodeType && (~e.sourceIndex || G) - (~t.sourceIndex || G);
            if (i) return i;
            if (n) for (; n = n.nextSibling;) if (n === e) return - 1;
            return t ? 1 : -1
        }
        function o(t) {
            return function(e) {
                var n = e.nodeName.toLowerCase();
                return "input" === n && e.type === t
            }
        }
        function l(t) {
            return function(e) {
                var n = e.nodeName.toLowerCase();
                return ("input" === n || "button" === n) && e.type === t
            }
        }
        function u(t) {
            return i(function(e) {
                return e = +e,
                i(function(n, i) {
                    for (var r, s = t([], n.length, e), a = s.length; a--;) n[r = s[a]] && (n[r] = !(i[r] = n[r]))
                })
            })
        }
        function c(t) {
            return t && typeof t.getElementsByTagName !== B && t
        }
        function h() {}
        function d(t, n) {
            var i, r, s, a, o, l, u, c = V[t + " "];
            if (c) return n ? 0 : c.slice(0);
            for (o = t, l = [], u = k.preFilter; o;) { (!i || (r = ue.exec(o))) && (r && (o = o.slice(r[0].length) || o), l.push(s = [])),
                i = !1,
                (r = ce.exec(o)) && (i = r.shift(), s.push({
                    "value": i,
                    "type": r[0].replace(le, " ")
                }), o = o.slice(i.length));
                for (a in k.filter) ! (r = pe[a].exec(o)) || u[a] && !(r = u[a](r)) || (i = r.shift(), s.push({
                    "value": i,
                    "type": a,
                    "matches": r
                }), o = o.slice(i.length));
                if (!i) break
            }
            return n ? o.length: o ? e.error(t) : V(t, l).slice(0)
        }
        function f(t) {
            for (var e = 0,
            n = t.length,
            i = ""; n > e; e++) i += t[e].value;
            return i
        }
        function p(t, e, n) {
            var i = e.dir,
            r = n && "parentNode" === i,
            s = q++;
            return e.first ?
            function(e, n, s) {
                for (; e = e[i];) if (1 === e.nodeType || r) return t(e, n, s)
            }: function(e, n, a) {
                var o, l, u = [U, s];
                if (a) {
                    for (; e = e[i];) if ((1 === e.nodeType || r) && t(e, n, a)) return ! 0
                } else for (; e = e[i];) if (1 === e.nodeType || r) {
                    if (l = e[H] || (e[H] = {}), (o = l[i]) && o[0] === U && o[1] === s) return u[2] = o[2];
                    if (l[i] = u, u[2] = t(e, n, a)) return ! 0
                }
            }
        }
        function m(t) {
            return t.length > 1 ?
            function(e, n, i) {
                for (var r = t.length; r--;) if (!t[r](e, n, i)) return ! 1;
                return ! 0
            }: t[0]
        }
        function g(t, e, n, i, r) {
            for (var s, a = [], o = 0, l = t.length, u = null != e; l > o; o++)(s = t[o]) && (!n || n(s, i, r)) && (a.push(s), u && e.push(o));
            return a
        }
        function v(t, e, n, r, s, a) {
            return r && !r[H] && (r = v(r)),
            s && !s[H] && (s = v(s, a)),
            i(function(i, a, o, l) {
                var u, c, h, d = [],
                f = [],
                p = a.length,
                m = i || w(e || "*", o.nodeType ? [o] : o, []),
                v = !t || !i && e ? m: g(m, d, t, o, l),
                y = n ? s || (i ? t: p || r) ? [] : a: v;
                if (n && n(v, y, o, l), r) for (u = g(y, f), r(u, [], o, l), c = u.length; c--;)(h = u[c]) && (y[f[c]] = !(v[f[c]] = h));
                if (i) {
                    if (s || t) {
                        if (s) {
                            for (u = [], c = y.length; c--;)(h = y[c]) && u.push(v[c] = h);
                            s(null, y = [], u, l)
                        }
                        for (c = y.length; c--;)(h = y[c]) && (u = s ? ee.call(i, h) : d[c]) > -1 && (i[u] = !(a[u] = h))
                    }
                } else y = g(y === a ? y.splice(p, y.length) : y),
                s ? s(null, a, y, l) : K.apply(a, y)
            })
        }
        function y(t) {
            for (var e, n, i, r = t.length,
            s = k.relative[t[0].type], a = s || k.relative[" "], o = s ? 1 : 0, l = p(function(t) {
                return t === e
            },
            a, !0), u = p(function(t) {
                return ee.call(e, t) > -1
            },
            a, !0), c = [function(t, n, i) {
                return ! s && (i || n !== E) || ((e = n).nodeType ? l(t, n, i) : u(t, n, i))
            }]; r > o; o++) if (n = k.relative[t[o].type]) c = [p(m(c), n)];
            else {
                if (n = k.filter[t[o].type].apply(null, t[o].matches), n[H]) {
                    for (i = ++o; r > i && !k.relative[t[i].type]; i++);
                    return v(o > 1 && m(c), o > 1 && f(t.slice(0, o - 1).concat({
                        "value": " " === t[o - 2].type ? "*": ""
                    })).replace(le, "$1"), n, i > o && y(t.slice(o, i)), r > i && y(t = t.slice(i)), r > i && f(t))
                }
                c.push(n)
            }
            return m(c)
        }
        function b(t, n) {
            var r = n.length > 0,
            s = t.length > 0,
            a = function(i, a, o, l, u) {
                var c, h, d, f = 0,
                p = "0",
                m = i && [],
                v = [],
                y = E,
                b = i || s && k.find.TAG("*", u),
                w = U += null == y ? 1 : Math.random() || .1,
                $ = b.length;
                for (u && (E = a !== O && a); p !== $ && null != (c = b[p]); p++) {
                    if (s && c) {
                        for (h = 0; d = t[h++];) if (d(c, a, o)) {
                            l.push(c);
                            break
                        }
                        u && (U = w)
                    }
                    r && ((c = !d && c) && f--, i && m.push(c))
                }
                if (f += p, r && p !== f) {
                    for (h = 0; d = n[h++];) d(m, v, a, o);
                    if (i) {
                        if (f > 0) for (; p--;) m[p] || v[p] || (v[p] = J.call(l));
                        v = g(v)
                    }
                    K.apply(l, v),
                    u && !i && v.length > 0 && f + n.length > 1 && e.uniqueSort(l)
                }
                return u && (U = w, E = y),
                m
            };
            return r ? i(a) : a
        }
        function w(t, n, i) {
            for (var r = 0,
            s = n.length; s > r; r++) e(t, n[r], i);
            return i
        }
        function $(t, e, n, i) {
            var r, s, a, o, l, u = d(t);
            if (!i && 1 === u.length) {
                if (s = u[0] = u[0].slice(0), s.length > 2 && "ID" === (a = s[0]).type && C.getById && 9 === e.nodeType && N && k.relative[s[1].type]) {
                    if (e = (k.find.ID(a.matches[0].replace($e, xe), e) || [])[0], !e) return n;
                    t = t.slice(s.shift().value.length)
                }
                for (r = pe.needsContext.test(t) ? 0 : s.length; r--&&(a = s[r], !k.relative[o = a.type]);) if ((l = k.find[o]) && (i = l(a.matches[0].replace($e, xe), be.test(s[0].type) && c(e.parentNode) || e))) {
                    if (s.splice(r, 1), t = i.length && f(s), !t) return K.apply(n, i),
                    n;
                    break
                }
            }
            return D(t, u)(i, e, !N, n, be.test(t) && c(e.parentNode) || e),
            n
        }
        var x, C, k, T, S, D, E, _, M, A, O, F, N, j, P, I, L, H = "sizzle" + -new Date,
        R = t.document,
        U = 0,
        q = 0,
        Y = n(),
        V = n(),
        W = n(),
        z = function(t, e) {
            return t === e && (M = !0),
            0
        },
        B = "undefined",
        G = 1 << 31,
        Q = {}.hasOwnProperty,
        Z = [],
        J = Z.pop,
        X = Z.push,
        K = Z.push,
        te = Z.slice,
        ee = Z.indexOf ||
        function(t) {
            for (var e = 0,
            n = this.length; n > e; e++) if (this[e] === t) return e;
            return - 1
        },
        ne = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
        ie = "[\\x20\\t\\r\\n\\f]",
        re = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",
        se = re.replace("w", "w#"),
        ae = "\\[" + ie + "*(" + re + ")" + ie + "*(?:([*^$|!~]?=)" + ie + "*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|(" + se + ")|)|)" + ie + "*\\]",
        oe = ":(" + re + ")(?:\\(((['\"])((?:\\\\.|[^\\\\])*?)\\3|((?:\\\\.|[^\\\\()[\\]]|" + ae.replace(3, 8) + ")*)|.*)\\)|)",
        le = new RegExp("^" + ie + "+|((?:^|[^\\\\])(?:\\\\.)*)" + ie + "+$", "g"),
        ue = new RegExp("^" + ie + "*," + ie + "*"),
        ce = new RegExp("^" + ie + "*([>+~]|" + ie + ")" + ie + "*"),
        he = new RegExp("=" + ie + "*([^\\]'\"]*?)" + ie + "*\\]", "g"),
        de = new RegExp(oe),
        fe = new RegExp("^" + se + "$"),
        pe = {
            "ID": new RegExp("^#(" + re + ")"),
            "CLASS": new RegExp("^\\.(" + re + ")"),
            "TAG": new RegExp("^(" + re.replace("w", "w*") + ")"),
            "ATTR": new RegExp("^" + ae),
            "PSEUDO": new RegExp("^" + oe),
            "CHILD": new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + ie + "*(even|odd|(([+-]|)(\\d*)n|)" + ie + "*(?:([+-]|)" + ie + "*(\\d+)|))" + ie + "*\\)|)", "i"),
            "bool": new RegExp("^(?:" + ne + ")$", "i"),
            "needsContext": new RegExp("^" + ie + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + ie + "*((?:-\\d)?\\d*)" + ie + "*\\)|)(?=[^-]|$)", "i")
        },
        me = /^(?:input|select|textarea|button)$/i,
        ge = /^h\d$/i,
        ve = /^[^{]+\{\s*\[native \w/,
        ye = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
        be = /[+~]/,
        we = /'|\\/g,
        $e = new RegExp("\\\\([\\da-f]{1,6}" + ie + "?|(" + ie + ")|.)", "ig"),
        xe = function(t, e, n) {
            var i = "0x" + e - 65536;
            return i !== i || n ? e: 0 > i ? String.fromCharCode(i + 65536) : String.fromCharCode(i >> 10 | 55296, 1023 & i | 56320)
        };
        try {
            K.apply(Z = te.call(R.childNodes), R.childNodes),
            Z[R.childNodes.length].nodeType
        } catch(Ce) {
            K = {
                "apply": Z.length ?
                function(t, e) {
                    X.apply(t, te.call(e))
                }: function(t, e) {
                    for (var n = t.length,
                    i = 0; t[n++] = e[i++];);
                    t.length = n - 1
                }
            }
        }
        C = e.support = {},
        S = e.isXML = function(t) {
            var e = t && (t.ownerDocument || t).documentElement;
            return e ? "HTML" !== e.nodeName: !1
        },
        A = e.setDocument = function(t) {
            var e, n = t ? t.ownerDocument || t: R,
            i = n.defaultView;
            return n !== O && 9 === n.nodeType && n.documentElement ? (O = n, F = n.documentElement, N = !S(n), i && i !== i.top && (i.addEventListener ? i.addEventListener("unload",
            function() {
                A()
            },
            !1) : i.attachEvent && i.attachEvent("onunload",
            function() {
                A()
            })), C.attributes = r(function(t) {
                return t.className = "i",
                !t.getAttribute("className")
            }), C.getElementsByTagName = r(function(t) {
                return t.appendChild(n.createComment("")),
                !t.getElementsByTagName("*").length
            }), C.getElementsByClassName = ve.test(n.getElementsByClassName) && r(function(t) {
                return t.innerHTML = "<div class='a'></div><div class='a i'></div>",
                t.firstChild.className = "i",
                2 === t.getElementsByClassName("i").length
            }), C.getById = r(function(t) {
                return F.appendChild(t).id = H,
                !n.getElementsByName || !n.getElementsByName(H).length
            }), C.getById ? (k.find.ID = function(t, e) {
                if (typeof e.getElementById !== B && N) {
                    var n = e.getElementById(t);
                    return n && n.parentNode ? [n] : []
                }
            },
            k.filter.ID = function(t) {
                var e = t.replace($e, xe);
                return function(t) {
                    return t.getAttribute("id") === e
                }
            }) : (delete k.find.ID, k.filter.ID = function(t) {
                var e = t.replace($e, xe);
                return function(t) {
                    var n = typeof t.getAttributeNode !== B && t.getAttributeNode("id");
                    return n && n.value === e
                }
            }), k.find.TAG = C.getElementsByTagName ?
            function(t, e) {
                return typeof e.getElementsByTagName !== B ? e.getElementsByTagName(t) : void 0
            }: function(t, e) {
                var n, i = [],
                r = 0,
                s = e.getElementsByTagName(t);
                if ("*" === t) {
                    for (; n = s[r++];) 1 === n.nodeType && i.push(n);
                    return i
                }
                return s
            },
            k.find.CLASS = C.getElementsByClassName &&
            function(t, e) {
                return typeof e.getElementsByClassName !== B && N ? e.getElementsByClassName(t) : void 0
            },
            P = [], j = [], (C.qsa = ve.test(n.querySelectorAll)) && (r(function(t) {
                t.innerHTML = "<select t=''><option selected=''></option></select>",
                t.querySelectorAll("[t^='']").length && j.push("[*^$]=" + ie + "*(?:''|\"\")"),
                t.querySelectorAll("[selected]").length || j.push("\\[" + ie + "*(?:value|" + ne + ")"),
                t.querySelectorAll(":checked").length || j.push(":checked")
            }), r(function(t) {
                var e = n.createElement("input");
                e.setAttribute("type", "hidden"),
                t.appendChild(e).setAttribute("name", "D"),
                t.querySelectorAll("[name=d]").length && j.push("name" + ie + "*[*^$|!~]?="),
                t.querySelectorAll(":enabled").length || j.push(":enabled", ":disabled"),
                t.querySelectorAll("*,:x"),
                j.push(",.*:")
            })), (C.matchesSelector = ve.test(I = F.webkitMatchesSelector || F.mozMatchesSelector || F.oMatchesSelector || F.msMatchesSelector)) && r(function(t) {
                C.disconnectedMatch = I.call(t, "div"),
                I.call(t, "[s!='']:x"),
                P.push("!=", oe)
            }), j = j.length && new RegExp(j.join("|")), P = P.length && new RegExp(P.join("|")), e = ve.test(F.compareDocumentPosition), L = e || ve.test(F.contains) ?
            function(t, e) {
                var n = 9 === t.nodeType ? t.documentElement: t,
                i = e && e.parentNode;
                return t === i || !(!i || 1 !== i.nodeType || !(n.contains ? n.contains(i) : t.compareDocumentPosition && 16 & t.compareDocumentPosition(i)))
            }: function(t, e) {
                if (e) for (; e = e.parentNode;) if (e === t) return ! 0;
                return ! 1
            },
            z = e ?
            function(t, e) {
                if (t === e) return M = !0,
                0;
                var i = !t.compareDocumentPosition - !e.compareDocumentPosition;
                return i ? i: (i = (t.ownerDocument || t) === (e.ownerDocument || e) ? t.compareDocumentPosition(e) : 1, 1 & i || !C.sortDetached && e.compareDocumentPosition(t) === i ? t === n || t.ownerDocument === R && L(R, t) ? -1 : e === n || e.ownerDocument === R && L(R, e) ? 1 : _ ? ee.call(_, t) - ee.call(_, e) : 0 : 4 & i ? -1 : 1)
            }: function(t, e) {
                if (t === e) return M = !0,
                0;
                var i, r = 0,
                s = t.parentNode,
                o = e.parentNode,
                l = [t],
                u = [e];
                if (!s || !o) return t === n ? -1 : e === n ? 1 : s ? -1 : o ? 1 : _ ? ee.call(_, t) - ee.call(_, e) : 0;
                if (s === o) return a(t, e);
                for (i = t; i = i.parentNode;) l.unshift(i);
                for (i = e; i = i.parentNode;) u.unshift(i);
                for (; l[r] === u[r];) r++;
                return r ? a(l[r], u[r]) : l[r] === R ? -1 : u[r] === R ? 1 : 0
            },
            n) : O
        },
        e.matches = function(t, n) {
            return e(t, null, null, n)
        },
        e.matchesSelector = function(t, n) {
            if ((t.ownerDocument || t) !== O && A(t), n = n.replace(he, "='$1']"), !(!C.matchesSelector || !N || P && P.test(n) || j && j.test(n))) try {
                var i = I.call(t, n);
                if (i || C.disconnectedMatch || t.document && 11 !== t.document.nodeType) return i
            } catch(r) {}
            return e(n, O, null, [t]).length > 0
        },
        e.contains = function(t, e) {
            return (t.ownerDocument || t) !== O && A(t),
            L(t, e)
        },
        e.attr = function(t, e) { (t.ownerDocument || t) !== O && A(t);
            var n = k.attrHandle[e.toLowerCase()],
            i = n && Q.call(k.attrHandle, e.toLowerCase()) ? n(t, e, !N) : void 0;
            return void 0 !== i ? i: C.attributes || !N ? t.getAttribute(e) : (i = t.getAttributeNode(e)) && i.specified ? i.value: null
        },
        e.error = function(t) {
            throw new Error("Syntax error, unrecognized expression: " + t)
        },
        e.uniqueSort = function(t) {
            var e, n = [],
            i = 0,
            r = 0;
            if (M = !C.detectDuplicates, _ = !C.sortStable && t.slice(0), t.sort(z), M) {
                for (; e = t[r++];) e === t[r] && (i = n.push(r));
                for (; i--;) t.splice(n[i], 1)
            }
            return _ = null,
            t
        },
        T = e.getText = function(t) {
            var e, n = "",
            i = 0,
            r = t.nodeType;
            if (r) {
                if (1 === r || 9 === r || 11 === r) {
                    if ("string" == typeof t.textContent) return t.textContent;
                    for (t = t.firstChild; t; t = t.nextSibling) n += T(t)
                } else if (3 === r || 4 === r) return t.nodeValue
            } else for (; e = t[i++];) n += T(e);
            return n
        },
        k = e.selectors = {
            "cacheLength": 50,
            "createPseudo": i,
            "match": pe,
            "attrHandle": {},
            "find": {},
            "relative": {
                ">": {
                    "dir": "parentNode",
                    "first": !0
                },
                " ": {
                    "dir": "parentNode"
                },
                "+": {
                    "dir": "previousSibling",
                    "first": !0
                },
                "~": {
                    "dir": "previousSibling"
                }
            },
            "preFilter": {
                "ATTR": function(t) {
                    return t[1] = t[1].replace($e, xe),
                    t[3] = (t[4] || t[5] || "").replace($e, xe),
                    "~=" === t[2] && (t[3] = " " + t[3] + " "),
                    t.slice(0, 4)
                },
                "CHILD": function(t) {
                    return t[1] = t[1].toLowerCase(),
                    "nth" === t[1].slice(0, 3) ? (t[3] || e.error(t[0]), t[4] = +(t[4] ? t[5] + (t[6] || 1) : 2 * ("even" === t[3] || "odd" === t[3])), t[5] = +(t[7] + t[8] || "odd" === t[3])) : t[3] && e.error(t[0]),
                    t
                },
                "PSEUDO": function(t) {
                    var e, n = !t[5] && t[2];
                    return pe.CHILD.test(t[0]) ? null: (t[3] && void 0 !== t[4] ? t[2] = t[4] : n && de.test(n) && (e = d(n, !0)) && (e = n.indexOf(")", n.length - e) - n.length) && (t[0] = t[0].slice(0, e), t[2] = n.slice(0, e)), t.slice(0, 3))
                }
            },
            "filter": {
                "TAG": function(t) {
                    var e = t.replace($e, xe).toLowerCase();
                    return "*" === t ?
                    function() {
                        return ! 0
                    }: function(t) {
                        return t.nodeName && t.nodeName.toLowerCase() === e
                    }
                },
                "CLASS": function(t) {
                    var e = Y[t + " "];
                    return e || (e = new RegExp("(^|" + ie + ")" + t + "(" + ie + "|$)")) && Y(t,
                    function(t) {
                        return e.test("string" == typeof t.className && t.className || typeof t.getAttribute !== B && t.getAttribute("class") || "")
                    })
                },
                "ATTR": function(t, n, i) {
                    return function(r) {
                        var s = e.attr(r, t);
                        return null == s ? "!=" === n: n ? (s += "", "=" === n ? s === i: "!=" === n ? s !== i: "^=" === n ? i && 0 === s.indexOf(i) : "*=" === n ? i && s.indexOf(i) > -1 : "$=" === n ? i && s.slice( - i.length) === i: "~=" === n ? (" " + s + " ").indexOf(i) > -1 : "|=" === n ? s === i || s.slice(0, i.length + 1) === i + "-": !1) : !0
                    }
                },
                "CHILD": function(t, e, n, i, r) {
                    var s = "nth" !== t.slice(0, 3),
                    a = "last" !== t.slice( - 4),
                    o = "of-type" === e;
                    return 1 === i && 0 === r ?
                    function(t) {
                        return !! t.parentNode
                    }: function(e, n, l) {
                        var u, c, h, d, f, p, m = s !== a ? "nextSibling": "previousSibling",
                        g = e.parentNode,
                        v = o && e.nodeName.toLowerCase(),
                        y = !l && !o;
                        if (g) {
                            if (s) {
                                for (; m;) {
                                    for (h = e; h = h[m];) if (o ? h.nodeName.toLowerCase() === v: 1 === h.nodeType) return ! 1;
                                    p = m = "only" === t && !p && "nextSibling"
                                }
                                return ! 0
                            }
                            if (p = [a ? g.firstChild: g.lastChild], a && y) {
                                for (c = g[H] || (g[H] = {}), u = c[t] || [], f = u[0] === U && u[1], d = u[0] === U && u[2], h = f && g.childNodes[f]; h = ++f && h && h[m] || (d = f = 0) || p.pop();) if (1 === h.nodeType && ++d && h === e) {
                                    c[t] = [U, f, d];
                                    break
                                }
                            } else if (y && (u = (e[H] || (e[H] = {}))[t]) && u[0] === U) d = u[1];
                            else for (; (h = ++f && h && h[m] || (d = f = 0) || p.pop()) && ((o ? h.nodeName.toLowerCase() !== v: 1 !== h.nodeType) || !++d || (y && ((h[H] || (h[H] = {}))[t] = [U, d]), h !== e)););
                            return d -= r,
                            d === i || d % i === 0 && d / i >= 0
                        }
                    }
                },
                "PSEUDO": function(t, n) {
                    var r, s = k.pseudos[t] || k.setFilters[t.toLowerCase()] || e.error("unsupported pseudo: " + t);
                    return s[H] ? s(n) : s.length > 1 ? (r = [t, t, "", n], k.setFilters.hasOwnProperty(t.toLowerCase()) ? i(function(t, e) {
                        for (var i, r = s(t, n), a = r.length; a--;) i = ee.call(t, r[a]),
                        t[i] = !(e[i] = r[a])
                    }) : function(t) {
                        return s(t, 0, r)
                    }) : s
                }
            },
            "pseudos": {
                "not": i(function(t) {
                    var e = [],
                    n = [],
                    r = D(t.replace(le, "$1"));
                    return r[H] ? i(function(t, e, n, i) {
                        for (var s, a = r(t, null, i, []), o = t.length; o--;)(s = a[o]) && (t[o] = !(e[o] = s))
                    }) : function(t, i, s) {
                        return e[0] = t,
                        r(e, null, s, n),
                        !n.pop()
                    }
                }),
                "has": i(function(t) {
                    return function(n) {
                        return e(t, n).length > 0
                    }
                }),
                "contains": i(function(t) {
                    return function(e) {
                        return (e.textContent || e.innerText || T(e)).indexOf(t) > -1
                    }
                }),
                "lang": i(function(t) {
                    return fe.test(t || "") || e.error("unsupported lang: " + t),
                    t = t.replace($e, xe).toLowerCase(),
                    function(e) {
                        var n;
                        do
                        if (n = N ? e.lang: e.getAttribute("xml:lang") || e.getAttribute("lang")) return n = n.toLowerCase(),
                        n === t || 0 === n.indexOf(t + "-");
                        while ((e = e.parentNode) && 1 === e.nodeType);
                        return ! 1
                    }
                }),
                "target": function(e) {
                    var n = t.location && t.location.hash;
                    return n && n.slice(1) === e.id
                },
                "root": function(t) {
                    return t === F
                },
                "focus": function(t) {
                    return t === O.activeElement && (!O.hasFocus || O.hasFocus()) && !!(t.type || t.href || ~t.tabIndex)
                },
                "enabled": function(t) {
                    return t.disabled === !1
                },
                "disabled": function(t) {
                    return t.disabled === !0
                },
                "checked": function(t) {
                    var e = t.nodeName.toLowerCase();
                    return "input" === e && !!t.checked || "option" === e && !!t.selected
                },
                "selected": function(t) {
                    return t.parentNode && t.parentNode.selectedIndex,
                    t.selected === !0
                },
                "empty": function(t) {
                    for (t = t.firstChild; t; t = t.nextSibling) if (t.nodeType < 6) return ! 1;
                    return ! 0
                },
                "parent": function(t) {
                    return ! k.pseudos.empty(t)
                },
                "header": function(t) {
                    return ge.test(t.nodeName)
                },
                "input": function(t) {
                    return me.test(t.nodeName)
                },
                "button": function(t) {
                    var e = t.nodeName.toLowerCase();
                    return "input" === e && "button" === t.type || "button" === e
                },
                "text": function(t) {
                    var e;
                    return "input" === t.nodeName.toLowerCase() && "text" === t.type && (null == (e = t.getAttribute("type")) || "text" === e.toLowerCase())
                },
                "first": u(function() {
                    return [0]
                }),
                "last": u(function(t, e) {
                    return [e - 1]
                }),
                "eq": u(function(t, e, n) {
                    return [0 > n ? n + e: n]
                }),
                "even": u(function(t, e) {
                    for (var n = 0; e > n; n += 2) t.push(n);
                    return t
                }),
                "odd": u(function(t, e) {
                    for (var n = 1; e > n; n += 2) t.push(n);
                    return t
                }),
                "lt": u(function(t, e, n) {
                    for (var i = 0 > n ? n + e: n; --i >= 0;) t.push(i);
                    return t
                }),
                "gt": u(function(t, e, n) {
                    for (var i = 0 > n ? n + e: n; ++i < e;) t.push(i);
                    return t
                })
            }
        },
        k.pseudos.nth = k.pseudos.eq;
        for (x in {
            "radio": !0,
            "checkbox": !0,
            "file": !0,
            "password": !0,
            "image": !0
        }) k.pseudos[x] = o(x);
        for (x in {
            "submit": !0,
            "reset": !0
        }) k.pseudos[x] = l(x);
        return h.prototype = k.filters = k.pseudos,
        k.setFilters = new h,
        D = e.compile = function(t, e) {
            var n, i = [],
            r = [],
            s = W[t + " "];
            if (!s) {
                for (e || (e = d(t)), n = e.length; n--;) s = y(e[n]),
                s[H] ? i.push(s) : r.push(s);
                s = W(t, b(r, i))
            }
            return s
        },
        C.sortStable = H.split("").sort(z).join("") === H,
        C.detectDuplicates = !!M,
        A(),
        C.sortDetached = r(function(t) {
            return 1 & t.compareDocumentPosition(O.createElement("div"))
        }),
        r(function(t) {
            return t.innerHTML = "<a href='#'></a>",
            "#" === t.firstChild.getAttribute("href")
        }) || s("type|href|height|width",
        function(t, e, n) {
            return n ? void 0 : t.getAttribute(e, "type" === e.toLowerCase() ? 1 : 2)
        }),
        C.attributes && r(function(t) {
            return t.innerHTML = "<input/>",
            t.firstChild.setAttribute("value", ""),
            "" === t.firstChild.getAttribute("value")
        }) || s("value",
        function(t, e, n) {
            return n || "input" !== t.nodeName.toLowerCase() ? void 0 : t.defaultValue
        }),
        r(function(t) {
            return null == t.getAttribute("disabled")
        }) || s(ne,
        function(t, e, n) {
            var i;
            return n ? void 0 : t[e] === !0 ? e.toLowerCase() : (i = t.getAttributeNode(e)) && i.specified ? i.value: null
        }),
        e
    } (t);
    se.find = ce,
    se.expr = ce.selectors,
    se.expr[":"] = se.expr.pseudos,
    se.unique = ce.uniqueSort,
    se.text = ce.getText,
    se.isXMLDoc = ce.isXML,
    se.contains = ce.contains;
    var he = se.expr.match.needsContext,
    de = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
    fe = /^.[^:#\[\.,]*$/;
    se.filter = function(t, e, n) {
        var i = e[0];
        return n && (t = ":not(" + t + ")"),
        1 === e.length && 1 === i.nodeType ? se.find.matchesSelector(i, t) ? [i] : [] : se.find.matches(t, se.grep(e,
        function(t) {
            return 1 === t.nodeType
        }))
    },
    se.fn.extend({
        "find": function(t) {
            var e, n = [],
            i = this,
            r = i.length;
            if ("string" != typeof t) return this.pushStack(se(t).filter(function() {
                for (e = 0; r > e; e++) if (se.contains(i[e], this)) return ! 0
            }));
            for (e = 0; r > e; e++) se.find(t, i[e], n);
            return n = this.pushStack(r > 1 ? se.unique(n) : n),
            n.selector = this.selector ? this.selector + " " + t: t,
            n
        },
        "filter": function(t) {
            return this.pushStack(i(this, t || [], !1))
        },
        "not": function(t) {
            return this.pushStack(i(this, t || [], !0))
        },
        "is": function(t) {
            return !! i(this, "string" == typeof t && he.test(t) ? se(t) : t || [], !1).length
        }
    });
    var pe, me = t.document,
    ge = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/,
    ve = se.fn.init = function(t, e) {
        var n, i;
        if (!t) return this;
        if ("string" == typeof t) {
            if (n = "<" === t.charAt(0) && ">" === t.charAt(t.length - 1) && t.length >= 3 ? [null, t, null] : ge.exec(t), !n || !n[1] && e) return ! e || e.jquery ? (e || pe).find(t) : this.constructor(e).find(t);
            if (n[1]) {
                if (e = e instanceof se ? e[0] : e, se.merge(this, se.parseHTML(n[1], e && e.nodeType ? e.ownerDocument || e: me, !0)), de.test(n[1]) && se.isPlainObject(e)) for (n in e) se.isFunction(this[n]) ? this[n](e[n]) : this.attr(n, e[n]);
                return this
            }
            if (i = me.getElementById(n[2]), i && i.parentNode) {
                if (i.id !== n[2]) return pe.find(t);
                this.length = 1,
                this[0] = i
            }
            return this.context = me,
            this.selector = t,
            this
        }
        return t.nodeType ? (this.context = this[0] = t, this.length = 1, this) : se.isFunction(t) ? "undefined" != typeof pe.ready ? pe.ready(t) : t(se) : (void 0 !== t.selector && (this.selector = t.selector, this.context = t.context), se.makeArray(t, this))
    };
    ve.prototype = se.fn,
    pe = se(me);
    var ye = /^(?:parents|prev(?:Until|All))/,
    be = {
        "children": !0,
        "contents": !0,
        "next": !0,
        "prev": !0
    };
    se.extend({
        "dir": function(t, e, n) {
            for (var i = [], r = t[e]; r && 9 !== r.nodeType && (void 0 === n || 1 !== r.nodeType || !se(r).is(n));) 1 === r.nodeType && i.push(r),
            r = r[e];
            return i
        },
        "sibling": function(t, e) {
            for (var n = []; t; t = t.nextSibling) 1 === t.nodeType && t !== e && n.push(t);
            return n
        }
    }),
    se.fn.extend({
        "has": function(t) {
            var e, n = se(t, this),
            i = n.length;
            return this.filter(function() {
                for (e = 0; i > e; e++) if (se.contains(this, n[e])) return ! 0
            })
        },
        "closest": function(t, e) {
            for (var n, i = 0,
            r = this.length,
            s = [], a = he.test(t) || "string" != typeof t ? se(t, e || this.context) : 0; r > i; i++) for (n = this[i]; n && n !== e; n = n.parentNode) if (n.nodeType < 11 && (a ? a.index(n) > -1 : 1 === n.nodeType && se.find.matchesSelector(n, t))) {
                s.push(n);
                break
            }
            return this.pushStack(s.length > 1 ? se.unique(s) : s)
        },
        "index": function(t) {
            return t ? "string" == typeof t ? se.inArray(this[0], se(t)) : se.inArray(t.jquery ? t[0] : t, this) : this[0] && this[0].parentNode ? this.first().prevAll().length: -1
        },
        "add": function(t, e) {
            return this.pushStack(se.unique(se.merge(this.get(), se(t, e))))
        },
        "addBack": function(t) {
            return this.add(null == t ? this.prevObject: this.prevObject.filter(t))
        }
    }),
    se.each({
        "parent": function(t) {
            var e = t.parentNode;
            return e && 11 !== e.nodeType ? e: null
        },
        "parents": function(t) {
            return se.dir(t, "parentNode")
        },
        "parentsUntil": function(t, e, n) {
            return se.dir(t, "parentNode", n)
        },
        "next": function(t) {
            return r(t, "nextSibling")
        },
        "prev": function(t) {
            return r(t, "previousSibling")
        },
        "nextAll": function(t) {
            return se.dir(t, "nextSibling")
        },
        "prevAll": function(t) {
            return se.dir(t, "previousSibling")
        },
        "nextUntil": function(t, e, n) {
            return se.dir(t, "nextSibling", n)
        },
        "prevUntil": function(t, e, n) {
            return se.dir(t, "previousSibling", n)
        },
        "siblings": function(t) {
            return se.sibling((t.parentNode || {}).firstChild, t)
        },
        "children": function(t) {
            return se.sibling(t.firstChild)
        },
        "contents": function(t) {
            return se.nodeName(t, "iframe") ? t.contentDocument || t.contentWindow.document: se.merge([], t.childNodes)
        }
    },
    function(t, e) {
        se.fn[t] = function(n, i) {
            var r = se.map(this, e, n);
            return "Until" !== t.slice( - 5) && (i = n),
            i && "string" == typeof i && (r = se.filter(i, r)),
            this.length > 1 && (be[t] || (r = se.unique(r)), ye.test(t) && (r = r.reverse())),
            this.pushStack(r)
        }
    });
    var we = /\S+/g,
    $e = {};
    se.Callbacks = function(t) {
        t = "string" == typeof t ? $e[t] || s(t) : se.extend({},
        t);
        var e, n, i, r, a, o, l = [],
        u = !t.once && [],
        c = function(s) {
            for (n = t.memory && s, i = !0, a = o || 0, o = 0, r = l.length, e = !0; l && r > a; a++) if (l[a].apply(s[0], s[1]) === !1 && t.stopOnFalse) {
                n = !1;
                break
            }
            e = !1,
            l && (u ? u.length && c(u.shift()) : n ? l = [] : h.disable())
        },
        h = {
            "add": function() {
                if (l) {
                    var i = l.length; !
                    function s(e) {
                        se.each(e,
                        function(e, n) {
                            var i = se.type(n);
                            "function" === i ? t.unique && h.has(n) || l.push(n) : n && n.length && "string" !== i && s(n)
                        })
                    } (arguments),
                    e ? r = l.length: n && (o = i, c(n))
                }
                return this
            },
            "remove": function() {
                return l && se.each(arguments,
                function(t, n) {
                    for (var i; (i = se.inArray(n, l, i)) > -1;) l.splice(i, 1),
                    e && (r >= i && r--, a >= i && a--)
                }),
                this
            },
            "has": function(t) {
                return t ? se.inArray(t, l) > -1 : !(!l || !l.length)
            },
            "empty": function() {
                return l = [],
                r = 0,
                this
            },
            "disable": function() {
                return l = u = n = void 0,
                this
            },
            "disabled": function() {
                return ! l
            },
            "lock": function() {
                return u = void 0,
                n || h.disable(),
                this
            },
            "locked": function() {
                return ! u
            },
            "fireWith": function(t, n) {
                return ! l || i && !u || (n = n || [], n = [t, n.slice ? n.slice() : n], e ? u.push(n) : c(n)),
                this
            },
            "fire": function() {
                return h.fireWith(this, arguments),
                this
            },
            "fired": function() {
                return !! i
            }
        };
        return h
    },
    se.extend({
        "Deferred": function(t) {
            var e = [["resolve", "done", se.Callbacks("once memory"), "resolved"], ["reject", "fail", se.Callbacks("once memory"), "rejected"], ["notify", "progress", se.Callbacks("memory")]],
            n = "pending",
            i = {
                "state": function() {
                    return n
                },
                "always": function() {
                    return r.done(arguments).fail(arguments),
                    this
                },
                "then": function() {
                    var t = arguments;
                    return se.Deferred(function(n) {
                        se.each(e,
                        function(e, s) {
                            var a = se.isFunction(t[e]) && t[e];
                            r[s[1]](function() {
                                var t = a && a.apply(this, arguments);
                                t && se.isFunction(t.promise) ? t.promise().done(n.resolve).fail(n.reject).progress(n.notify) : n[s[0] + "With"](this === i ? n.promise() : this, a ? [t] : arguments)
                            })
                        }),
                        t = null
                    }).promise()
                },
                "promise": function(t) {
                    return null != t ? se.extend(t, i) : i
                }
            },
            r = {};
            return i.pipe = i.then,
            se.each(e,
            function(t, s) {
                var a = s[2],
                o = s[3];
                i[s[1]] = a.add,
                o && a.add(function() {
                    n = o
                },
                e[1 ^ t][2].disable, e[2][2].lock),
                r[s[0]] = function() {
                    return r[s[0] + "With"](this === r ? i: this, arguments),
                    this
                },
                r[s[0] + "With"] = a.fireWith
            }),
            i.promise(r),
            t && t.call(r, r),
            r
        },
        "when": function(t) {
            var e, n, i, r = 0,
            s = Q.call(arguments),
            a = s.length,
            o = 1 !== a || t && se.isFunction(t.promise) ? a: 0,
            l = 1 === o ? t: se.Deferred(),
            u = function(t, n, i) {
                return function(r) {
                    n[t] = this,
                    i[t] = arguments.length > 1 ? Q.call(arguments) : r,
                    i === e ? l.notifyWith(n, i) : --o || l.resolveWith(n, i)
                }
            };
            if (a > 1) for (e = new Array(a), n = new Array(a), i = new Array(a); a > r; r++) s[r] && se.isFunction(s[r].promise) ? s[r].promise().done(u(r, i, s)).fail(l.reject).progress(u(r, n, e)) : --o;
            return o || l.resolveWith(i, s),
            l.promise()
        }
    });
    var xe;
    se.fn.ready = function(t) {
        return se.ready.promise().done(t),
        this
    },
    se.extend({
        "isReady": !1,
        "readyWait": 1,
        "holdReady": function(t) {
            t ? se.readyWait++:se.ready(!0)
        },
        "ready": function(t) {
            if (t === !0 ? !--se.readyWait: !se.isReady) {
                if (!me.body) return setTimeout(se.ready);
                se.isReady = !0,
                t !== !0 && --se.readyWait > 0 || (xe.resolveWith(me, [se]), se.fn.trigger && se(me).trigger("ready").off("ready"))
            }
        }
    }),
    se.ready.promise = function(e) {
        if (!xe) if (xe = se.Deferred(), "complete" === me.readyState) setTimeout(se.ready);
        else if (me.addEventListener) me.addEventListener("DOMContentLoaded", o, !1),
        t.addEventListener("load", o, !1);
        else {
            me.attachEvent("onreadystatechange", o),
            t.attachEvent("onload", o);
            var n = !1;
            try {
                n = null == t.frameElement && me.documentElement
            } catch(i) {}
            n && n.doScroll && !
            function r() {
                if (!se.isReady) {
                    try {
                        n.doScroll("left")
                    } catch(t) {
                        return setTimeout(r, 50)
                    }
                    a(),
                    se.ready()
                }
            } ()
        }
        return xe.promise(e)
    };
    var Ce, ke = "undefined";
    for (Ce in se(ie)) break;
    ie.ownLast = "0" !== Ce,
    ie.inlineBlockNeedsLayout = !1,
    se(function() {
        var t, e, n = me.getElementsByTagName("body")[0];
        n && (t = me.createElement("div"), t.style.cssText = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px;margin-top:1px", e = me.createElement("div"), n.appendChild(t).appendChild(e), typeof e.style.zoom !== ke && (e.style.cssText = "border:0;margin:0;width:1px;padding:1px;display:inline;zoom:1", (ie.inlineBlockNeedsLayout = 3 === e.offsetWidth) && (n.style.zoom = 1)), n.removeChild(t), t = e = null)
    }),
    function() {
        var t = me.createElement("div");
        if (null == ie.deleteExpando) {
            ie.deleteExpando = !0;
            try {
                delete t.test
            } catch(e) {
                ie.deleteExpando = !1
            }
        }
        t = null
    } (),
    se.acceptData = function(t) {
        var e = se.noData[(t.nodeName + " ").toLowerCase()],
        n = +t.nodeType || 1;
        return 1 !== n && 9 !== n ? !1 : !e || e !== !0 && t.getAttribute("classid") === e
    };
    var Te = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
    Se = /([A-Z])/g;
    se.extend({
        "cache": {},
        "noData": {
            "applet ": !0,
            "embed ": !0,
            "object ": "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
        },
        "hasData": function(t) {
            return t = t.nodeType ? se.cache[t[se.expando]] : t[se.expando],
            !!t && !u(t)
        },
        "data": function(t, e, n) {
            return c(t, e, n)
        },
        "removeData": function(t, e) {
            return h(t, e)
        },
        "_data": function(t, e, n) {
            return c(t, e, n, !0)
        },
        "_removeData": function(t, e) {
            return h(t, e, !0)
        }
    }),
    se.fn.extend({
        "data": function(t, e) {
            var n, i, r, s = this[0],
            a = s && s.attributes;
            if (void 0 === t) {
                if (this.length && (r = se.data(s), 1 === s.nodeType && !se._data(s, "parsedAttrs"))) {
                    for (n = a.length; n--;) i = a[n].name,
                    0 === i.indexOf("data-") && (i = se.camelCase(i.slice(5)), l(s, i, r[i]));
                    se._data(s, "parsedAttrs", !0)
                }
                return r
            }
            return "object" == typeof t ? this.each(function() {
                se.data(this, t)
            }) : arguments.length > 1 ? this.each(function() {
                se.data(this, t, e)
            }) : s ? l(s, t, se.data(s, t)) : void 0
        },
        "removeData": function(t) {
            return this.each(function() {
                se.removeData(this, t)
            })
        }
    }),
    se.extend({
        "queue": function(t, e, n) {
            var i;
            return t ? (e = (e || "fx") + "queue", i = se._data(t, e), n && (!i || se.isArray(n) ? i = se._data(t, e, se.makeArray(n)) : i.push(n)), i || []) : void 0
        },
        "dequeue": function(t, e) {
            e = e || "fx";
            var n = se.queue(t, e),
            i = n.length,
            r = n.shift(),
            s = se._queueHooks(t, e),
            a = function() {
                se.dequeue(t, e)
            };
            "inprogress" === r && (r = n.shift(), i--),
            r && ("fx" === e && n.unshift("inprogress"), delete s.stop, r.call(t, a, s)),
            !i && s && s.empty.fire()
        },
        "_queueHooks": function(t, e) {
            var n = e + "queueHooks";
            return se._data(t, n) || se._data(t, n, {
                "empty": se.Callbacks("once memory").add(function() {
                    se._removeData(t, e + "queue"),
                    se._removeData(t, n)
                })
            })
        }
    }),
    se.fn.extend({
        "queue": function(t, e) {
            var n = 2;
            return "string" != typeof t && (e = t, t = "fx", n--),
            arguments.length < n ? se.queue(this[0], t) : void 0 === e ? this: this.each(function() {
                var n = se.queue(this, t, e);
                se._queueHooks(this, t),
                "fx" === t && "inprogress" !== n[0] && se.dequeue(this, t)
            })
        },
        "dequeue": function(t) {
            return this.each(function() {
                se.dequeue(this, t)
            })
        },
        "clearQueue": function(t) {
            return this.queue(t || "fx", [])
        },
        "promise": function(t, e) {
            var n, i = 1,
            r = se.Deferred(),
            s = this,
            a = this.length,
            o = function() {--i || r.resolveWith(s, [s])
            };
            for ("string" != typeof t && (e = t, t = void 0), t = t || "fx"; a--;) n = se._data(s[a], t + "queueHooks"),
            n && n.empty && (i++, n.empty.add(o));
            return o(),
            r.promise(e)
        }
    });
    var De = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
    Ee = ["Top", "Right", "Bottom", "Left"],
    _e = function(t, e) {
        return t = e || t,
        "none" === se.css(t, "display") || !se.contains(t.ownerDocument, t)
    },
    Me = se.access = function(t, e, n, i, r, s, a) {
        var o = 0,
        l = t.length,
        u = null == n;
        if ("object" === se.type(n)) {
            r = !0;
            for (o in n) se.access(t, e, o, n[o], !0, s, a)
        } else if (void 0 !== i && (r = !0, se.isFunction(i) || (a = !0), u && (a ? (e.call(t, i), e = null) : (u = e, e = function(t, e, n) {
            return u.call(se(t), n)
        })), e)) for (; l > o; o++) e(t[o], n, a ? i: i.call(t[o], o, e(t[o], n)));
        return r ? t: u ? e.call(t) : l ? e(t[0], n) : s
    },
    Ae = /^(?:checkbox|radio)$/i; !
    function() {
        var t = me.createDocumentFragment(),
        e = me.createElement("div"),
        n = me.createElement("input");
        if (e.setAttribute("className", "t"), e.innerHTML = "  <link/><table></table><a href='/a'>a</a>", ie.leadingWhitespace = 3 === e.firstChild.nodeType, ie.tbody = !e.getElementsByTagName("tbody").length, ie.htmlSerialize = !!e.getElementsByTagName("link").length, ie.html5Clone = "<:nav></:nav>" !== me.createElement("nav").cloneNode(!0).outerHTML, n.type = "checkbox", n.checked = !0, t.appendChild(n), ie.appendChecked = n.checked, e.innerHTML = "<textarea>x</textarea>", ie.noCloneChecked = !!e.cloneNode(!0).lastChild.defaultValue, t.appendChild(e), e.innerHTML = "<input type='radio' checked='checked' name='t'/>", ie.checkClone = e.cloneNode(!0).cloneNode(!0).lastChild.checked, ie.noCloneEvent = !0, e.attachEvent && (e.attachEvent("onclick",
        function() {
            ie.noCloneEvent = !1
        }), e.cloneNode(!0).click()), null == ie.deleteExpando) {
            ie.deleteExpando = !0;
            try {
                delete e.test
            } catch(i) {
                ie.deleteExpando = !1
            }
        }
        t = e = n = null
    } (),
    function() {
        var e, n, i = me.createElement("div");
        for (e in {
            "submit": !0,
            "change": !0,
            "focusin": !0
        }) n = "on" + e,
        (ie[e + "Bubbles"] = n in t) || (i.setAttribute(n, "t"), ie[e + "Bubbles"] = i.attributes[n].expando === !1);
        i = null
    } ();
    var Oe = /^(?:input|select|textarea)$/i,
    Fe = /^key/,
    Ne = /^(?:mouse|contextmenu)|click/,
    je = /^(?:focusinfocus|focusoutblur)$/,
    Pe = /^([^.]*)(?:\.(.+)|)$/;
    se.event = {
        "global": {},
        "add": function(t, e, n, i, r) {
            var s, a, o, l, u, c, h, d, f, p, m, g = se._data(t);
            if (g) {
                for (n.handler && (l = n, n = l.handler, r = l.selector), n.guid || (n.guid = se.guid++), (a = g.events) || (a = g.events = {}), (c = g.handle) || (c = g.handle = function(t) {
                    return typeof se === ke || t && se.event.triggered === t.type ? void 0 : se.event.dispatch.apply(c.elem, arguments)
                },
                c.elem = t), e = (e || "").match(we) || [""], o = e.length; o--;) s = Pe.exec(e[o]) || [],
                f = m = s[1],
                p = (s[2] || "").split(".").sort(),
                f && (u = se.event.special[f] || {},
                f = (r ? u.delegateType: u.bindType) || f, u = se.event.special[f] || {},
                h = se.extend({
                    "type": f,
                    "origType": m,
                    "data": i,
                    "handler": n,
                    "guid": n.guid,
                    "selector": r,
                    "needsContext": r && se.expr.match.needsContext.test(r),
                    "namespace": p.join(".")
                },
                l), (d = a[f]) || (d = a[f] = [], d.delegateCount = 0, u.setup && u.setup.call(t, i, p, c) !== !1 || (t.addEventListener ? t.addEventListener(f, c, !1) : t.attachEvent && t.attachEvent("on" + f, c))), u.add && (u.add.call(t, h), h.handler.guid || (h.handler.guid = n.guid)), r ? d.splice(d.delegateCount++, 0, h) : d.push(h), se.event.global[f] = !0);
                t = null
            }
        },
        "remove": function(t, e, n, i, r) {
            var s, a, o, l, u, c, h, d, f, p, m, g = se.hasData(t) && se._data(t);
            if (g && (c = g.events)) {
                for (e = (e || "").match(we) || [""], u = e.length; u--;) if (o = Pe.exec(e[u]) || [], f = m = o[1], p = (o[2] || "").split(".").sort(), f) {
                    for (h = se.event.special[f] || {},
                    f = (i ? h.delegateType: h.bindType) || f, d = c[f] || [], o = o[2] && new RegExp("(^|\\.)" + p.join("\\.(?:.*\\.|)") + "(\\.|$)"), l = s = d.length; s--;) a = d[s],
                    !r && m !== a.origType || n && n.guid !== a.guid || o && !o.test(a.namespace) || i && i !== a.selector && ("**" !== i || !a.selector) || (d.splice(s, 1), a.selector && d.delegateCount--, h.remove && h.remove.call(t, a));
                    l && !d.length && (h.teardown && h.teardown.call(t, p, g.handle) !== !1 || se.removeEvent(t, f, g.handle), delete c[f])
                } else for (f in c) se.event.remove(t, f + e[u], n, i, !0);
                se.isEmptyObject(c) && (delete g.handle, se._removeData(t, "events"))
            }
        },
        "trigger": function(e, n, i, r) {
            var s, a, o, l, u, c, h, d = [i || me],
            f = ee.call(e, "type") ? e.type: e,
            p = ee.call(e, "namespace") ? e.namespace.split(".") : [];
            if (o = c = i = i || me, 3 !== i.nodeType && 8 !== i.nodeType && !je.test(f + se.event.triggered) && (f.indexOf(".") >= 0 && (p = f.split("."), f = p.shift(), p.sort()), a = f.indexOf(":") < 0 && "on" + f, e = e[se.expando] ? e: new se.Event(f, "object" == typeof e && e), e.isTrigger = r ? 2 : 3, e.namespace = p.join("."), e.namespace_re = e.namespace ? new RegExp("(^|\\.)" + p.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, e.result = void 0, e.target || (e.target = i), n = null == n ? [e] : se.makeArray(n, [e]), u = se.event.special[f] || {},
            r || !u.trigger || u.trigger.apply(i, n) !== !1)) {
                if (!r && !u.noBubble && !se.isWindow(i)) {
                    for (l = u.delegateType || f, je.test(l + f) || (o = o.parentNode); o; o = o.parentNode) d.push(o),
                    c = o;
                    c === (i.ownerDocument || me) && d.push(c.defaultView || c.parentWindow || t)
                }
                for (h = 0; (o = d[h++]) && !e.isPropagationStopped();) e.type = h > 1 ? l: u.bindType || f,
                s = (se._data(o, "events") || {})[e.type] && se._data(o, "handle"),
                s && s.apply(o, n),
                s = a && o[a],
                s && s.apply && se.acceptData(o) && (e.result = s.apply(o, n), e.result === !1 && e.preventDefault());
                if (e.type = f, !r && !e.isDefaultPrevented() && (!u._default || u._default.apply(d.pop(), n) === !1) && se.acceptData(i) && a && i[f] && !se.isWindow(i)) {
                    c = i[a],
                    c && (i[a] = null),
                    se.event.triggered = f;
                    try {
                        i[f]()
                    } catch(m) {}
                    se.event.triggered = void 0,
                    c && (i[a] = c)
                }
                return e.result
            }
        },
        "dispatch": function(t) {
            t = se.event.fix(t);
            var e, n, i, r, s, a = [],
            o = Q.call(arguments),
            l = (se._data(this, "events") || {})[t.type] || [],
            u = se.event.special[t.type] || {};
            if (o[0] = t, t.delegateTarget = this, !u.preDispatch || u.preDispatch.call(this, t) !== !1) {
                for (a = se.event.handlers.call(this, t, l), e = 0; (r = a[e++]) && !t.isPropagationStopped();) for (t.currentTarget = r.elem, s = 0; (i = r.handlers[s++]) && !t.isImmediatePropagationStopped();)(!t.namespace_re || t.namespace_re.test(i.namespace)) && (t.handleObj = i, t.data = i.data, n = ((se.event.special[i.origType] || {}).handle || i.handler).apply(r.elem, o), void 0 !== n && (t.result = n) === !1 && (t.preventDefault(), t.stopPropagation()));
                return u.postDispatch && u.postDispatch.call(this, t),
                t.result
            }
        },
        "handlers": function(t, e) {
            var n, i, r, s, a = [],
            o = e.delegateCount,
            l = t.target;
            if (o && l.nodeType && (!t.button || "click" !== t.type)) for (; l != this; l = l.parentNode || this) if (1 === l.nodeType && (l.disabled !== !0 || "click" !== t.type)) {
                for (r = [], s = 0; o > s; s++) i = e[s],
                n = i.selector + " ",
                void 0 === r[n] && (r[n] = i.needsContext ? se(n, this).index(l) >= 0 : se.find(n, this, null, [l]).length),
                r[n] && r.push(i);
                r.length && a.push({
                    "elem": l,
                    "handlers": r
                })
            }
            return o < e.length && a.push({
                "elem": this,
                "handlers": e.slice(o)
            }),
            a
        },
        "fix": function(t) {
            if (t[se.expando]) return t;
            var e, n, i, r = t.type,
            s = t,
            a = this.fixHooks[r];
            for (a || (this.fixHooks[r] = a = Ne.test(r) ? this.mouseHooks: Fe.test(r) ? this.keyHooks: {}), i = a.props ? this.props.concat(a.props) : this.props, t = new se.Event(s), e = i.length; e--;) n = i[e],
            t[n] = s[n];
            return t.target || (t.target = s.srcElement || me),
            3 === t.target.nodeType && (t.target = t.target.parentNode),
            t.metaKey = !!t.metaKey,
            a.filter ? a.filter(t, s) : t
        },
        "props": "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        "fixHooks": {},
        "keyHooks": {
            "props": "char charCode key keyCode".split(" "),
            "filter": function(t, e) {
                return null == t.which && (t.which = null != e.charCode ? e.charCode: e.keyCode),
                t
            }
        },
        "mouseHooks": {
            "props": "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            "filter": function(t, e) {
                var n, i, r, s = e.button,
                a = e.fromElement;
                return null == t.pageX && null != e.clientX && (i = t.target.ownerDocument || me, r = i.documentElement, n = i.body, t.pageX = e.clientX + (r && r.scrollLeft || n && n.scrollLeft || 0) - (r && r.clientLeft || n && n.clientLeft || 0), t.pageY = e.clientY + (r && r.scrollTop || n && n.scrollTop || 0) - (r && r.clientTop || n && n.clientTop || 0)),
                !t.relatedTarget && a && (t.relatedTarget = a === t.target ? e.toElement: a),
                t.which || void 0 === s || (t.which = 1 & s ? 1 : 2 & s ? 3 : 4 & s ? 2 : 0),
                t
            }
        },
        "special": {
            "load": {
                "noBubble": !0
            },
            "focus": {
                "trigger": function() {
                    if (this !== p() && this.focus) try {
                        return this.focus(),
                        !1
                    } catch(t) {}
                },
                "delegateType": "focusin"
            },
            "blur": {
                "trigger": function() {
                    return this === p() && this.blur ? (this.blur(), !1) : void 0
                },
                "delegateType": "focusout"
            },
            "click": {
                "trigger": function() {
                    return se.nodeName(this, "input") && "checkbox" === this.type && this.click ? (this.click(), !1) : void 0
                },
                "_default": function(t) {
                    return se.nodeName(t.target, "a")
                }
            },
            "beforeunload": {
                "postDispatch": function(t) {
                    void 0 !== t.result && (t.originalEvent.returnValue = t.result)
                }
            }
        },
        "simulate": function(t, e, n, i) {
            var r = se.extend(new se.Event, n, {
                "type": t,
                "isSimulated": !0,
                "originalEvent": {}
            });
            i ? se.event.trigger(r, null, e) : se.event.dispatch.call(e, r),
            r.isDefaultPrevented() && n.preventDefault()
        }
    },
    se.removeEvent = me.removeEventListener ?
    function(t, e, n) {
        t.removeEventListener && t.removeEventListener(e, n, !1)
    }: function(t, e, n) {
        var i = "on" + e;
        t.detachEvent && (typeof t[i] === ke && (t[i] = null), t.detachEvent(i, n))
    },
    se.Event = function(t, e) {
        return this instanceof se.Event ? (t && t.type ? (this.originalEvent = t, this.type = t.type, this.isDefaultPrevented = t.defaultPrevented || void 0 === t.defaultPrevented && (t.returnValue === !1 || t.getPreventDefault && t.getPreventDefault()) ? d: f) : this.type = t, e && se.extend(this, e), this.timeStamp = t && t.timeStamp || se.now(), void(this[se.expando] = !0)) : new se.Event(t, e)
    },
    se.Event.prototype = {
        "isDefaultPrevented": f,
        "isPropagationStopped": f,
        "isImmediatePropagationStopped": f,
        "preventDefault": function() {
            var t = this.originalEvent;
            this.isDefaultPrevented = d,
            t && (t.preventDefault ? t.preventDefault() : t.returnValue = !1)
        },
        "stopPropagation": function() {
            var t = this.originalEvent;
            this.isPropagationStopped = d,
            t && (t.stopPropagation && t.stopPropagation(), t.cancelBubble = !0)
        },
        "stopImmediatePropagation": function() {
            this.isImmediatePropagationStopped = d,
            this.stopPropagation()
        }
    },
    se.each({
        "mouseenter": "mouseover",
        "mouseleave": "mouseout"
    },
    function(t, e) {
        se.event.special[t] = {
            "delegateType": e,
            "bindType": e,
            "handle": function(t) {
                var n, i = this,
                r = t.relatedTarget,
                s = t.handleObj;
                return (!r || r !== i && !se.contains(i, r)) && (t.type = s.origType, n = s.handler.apply(this, arguments), t.type = e),
                n
            }
        }
    }),
    ie.submitBubbles || (se.event.special.submit = {
        "setup": function() {
            return se.nodeName(this, "form") ? !1 : void se.event.add(this, "click._submit keypress._submit",
            function(t) {
                var e = t.target,
                n = se.nodeName(e, "input") || se.nodeName(e, "button") ? e.form: void 0;
                n && !se._data(n, "submitBubbles") && (se.event.add(n, "submit._submit",
                function(t) {
                    t._submit_bubble = !0
                }), se._data(n, "submitBubbles", !0))
            })
        },
        "postDispatch": function(t) {
            t._submit_bubble && (delete t._submit_bubble, this.parentNode && !t.isTrigger && se.event.simulate("submit", this.parentNode, t, !0))
        },
        "teardown": function() {
            return se.nodeName(this, "form") ? !1 : void se.event.remove(this, "._submit")
        }
    }),
    ie.changeBubbles || (se.event.special.change = {
        "setup": function() {
            return Oe.test(this.nodeName) ? (("checkbox" === this.type || "radio" === this.type) && (se.event.add(this, "propertychange._change",
            function(t) {
                "checked" === t.originalEvent.propertyName && (this._just_changed = !0)
            }), se.event.add(this, "click._change",
            function(t) {
                this._just_changed && !t.isTrigger && (this._just_changed = !1),
                se.event.simulate("change", this, t, !0)
            })), !1) : void se.event.add(this, "beforeactivate._change",
            function(t) {
                var e = t.target;
                Oe.test(e.nodeName) && !se._data(e, "changeBubbles") && (se.event.add(e, "change._change",
                function(t) { ! this.parentNode || t.isSimulated || t.isTrigger || se.event.simulate("change", this.parentNode, t, !0)
                }), se._data(e, "changeBubbles", !0))
            })
        },
        "handle": function(t) {
            var e = t.target;
            return this !== e || t.isSimulated || t.isTrigger || "radio" !== e.type && "checkbox" !== e.type ? t.handleObj.handler.apply(this, arguments) : void 0
        },
        "teardown": function() {
            return se.event.remove(this, "._change"),
            !Oe.test(this.nodeName)
        }
    }),
    ie.focusinBubbles || se.each({
        "focus": "focusin",
        "blur": "focusout"
    },
    function(t, e) {
        var n = function(t) {
            se.event.simulate(e, t.target, se.event.fix(t), !0)
        };
        se.event.special[e] = {
            "setup": function() {
                var i = this.ownerDocument || this,
                r = se._data(i, e);
                r || i.addEventListener(t, n, !0),
                se._data(i, e, (r || 0) + 1)
            },
            "teardown": function() {
                var i = this.ownerDocument || this,
                r = se._data(i, e) - 1;
                r ? se._data(i, e, r) : (i.removeEventListener(t, n, !0), se._removeData(i, e))
            }
        }
    }),
    se.fn.extend({
        "on": function(t, e, n, i, r) {
            var s, a;
            if ("object" == typeof t) {
                "string" != typeof e && (n = n || e, e = void 0);
                for (s in t) this.on(s, e, n, t[s], r);
                return this
            }
            if (null == n && null == i ? (i = e, n = e = void 0) : null == i && ("string" == typeof e ? (i = n, n = void 0) : (i = n, n = e, e = void 0)), i === !1) i = f;
            else if (!i) return this;
            return 1 === r && (a = i, i = function(t) {
                return se().off(t),
                a.apply(this, arguments)
            },
            i.guid = a.guid || (a.guid = se.guid++)),
            this.each(function() {
                se.event.add(this, t, i, n, e)
            })
        },
        "one": function(t, e, n, i) {
            return this.on(t, e, n, i, 1)
        },
        "off": function(t, e, n) {
            var i, r;
            if (t && t.preventDefault && t.handleObj) return i = t.handleObj,
            se(t.delegateTarget).off(i.namespace ? i.origType + "." + i.namespace: i.origType, i.selector, i.handler),
            this;
            if ("object" == typeof t) {
                for (r in t) this.off(r, e, t[r]);
                return this
            }
            return (e === !1 || "function" == typeof e) && (n = e, e = void 0),
            n === !1 && (n = f),
            this.each(function() {
                se.event.remove(this, t, n, e)
            })
        },
        "trigger": function(t, e) {
            return this.each(function() {
                se.event.trigger(t, e, this)
            })
        },
        "triggerHandler": function(t, e) {
            var n = this[0];
            return n ? se.event.trigger(t, e, n, !0) : void 0
        }
    });
    var Ie = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",
    Le = / jQuery\d+="(?:null|\d+)"/g,
    He = new RegExp("<(?:" + Ie + ")[\\s/>]", "i"),
    Re = /^\s+/,
    Ue = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
    qe = /<([\w:]+)/,
    Ye = /<tbody/i,
    Ve = /<|&#?\w+;/,
    We = /<(?:script|style|link)/i,
    ze = /checked\s*(?:[^=]|=\s*.checked.)/i,
    Be = /^$|\/(?:java|ecma)script/i,
    Ge = /^true\/(.*)/,
    Qe = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,
    Ze = {
        "option": [1, "<select multiple='multiple'>", "</select>"],
        "legend": [1, "<fieldset>", "</fieldset>"],
        "area": [1, "<map>", "</map>"],
        "param": [1, "<object>", "</object>"],
        "thead": [1, "<table>", "</table>"],
        "tr": [2, "<table><tbody>", "</tbody></table>"],
        "col": [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
        "td": [3, "<table><tbody><tr>", "</tr></tbody></table>"],
        "_default": ie.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]
    },
    Je = m(me),
    Xe = Je.appendChild(me.createElement("div"));
    Ze.optgroup = Ze.option,
    Ze.tbody = Ze.tfoot = Ze.colgroup = Ze.caption = Ze.thead,
    Ze.th = Ze.td,
    se.extend({
        "clone": function(t, e, n) {
            var i, r, s, a, o, l = se.contains(t.ownerDocument, t);
            if (ie.html5Clone || se.isXMLDoc(t) || !He.test("<" + t.nodeName + ">") ? s = t.cloneNode(!0) : (Xe.innerHTML = t.outerHTML, Xe.removeChild(s = Xe.firstChild)), !(ie.noCloneEvent && ie.noCloneChecked || 1 !== t.nodeType && 11 !== t.nodeType || se.isXMLDoc(t))) for (i = g(s), o = g(t), a = 0; null != (r = o[a]); ++a) i[a] && C(r, i[a]);
            if (e) if (n) for (o = o || g(t), i = i || g(s), a = 0; null != (r = o[a]); a++) x(r, i[a]);
            else x(t, s);
            return i = g(s, "script"),
            i.length > 0 && $(i, !l && g(t, "script")),
            i = o = r = null,
            s
        },
        "buildFragment": function(t, e, n, i) {
            for (var r, s, a, o, l, u, c, h = t.length,
            d = m(e), f = [], p = 0; h > p; p++) if (s = t[p], s || 0 === s) if ("object" === se.type(s)) se.merge(f, s.nodeType ? [s] : s);
            else if (Ve.test(s)) {
                for (o = o || d.appendChild(e.createElement("div")), l = (qe.exec(s) || ["", ""])[1].toLowerCase(), c = Ze[l] || Ze._default, o.innerHTML = c[1] + s.replace(Ue, "<$1></$2>") + c[2], r = c[0]; r--;) o = o.lastChild;
                if (!ie.leadingWhitespace && Re.test(s) && f.push(e.createTextNode(Re.exec(s)[0])), !ie.tbody) for (s = "table" !== l || Ye.test(s) ? "<table>" !== c[1] || Ye.test(s) ? 0 : o: o.firstChild, r = s && s.childNodes.length; r--;) se.nodeName(u = s.childNodes[r], "tbody") && !u.childNodes.length && s.removeChild(u);
                for (se.merge(f, o.childNodes), o.textContent = ""; o.firstChild;) o.removeChild(o.firstChild);
                o = d.lastChild
            } else f.push(e.createTextNode(s));
            for (o && d.removeChild(o), ie.appendChecked || se.grep(g(f, "input"), v), p = 0; s = f[p++];) if ((!i || -1 === se.inArray(s, i)) && (a = se.contains(s.ownerDocument, s), o = g(d.appendChild(s), "script"), a && $(o), n)) for (r = 0; s = o[r++];) Be.test(s.type || "") && n.push(s);
            return o = null,
            d
        },
        "cleanData": function(t, e) {
            for (var n, i, r, s, a = 0,
            o = se.expando,
            l = se.cache,
            u = ie.deleteExpando,
            c = se.event.special; null != (n = t[a]); a++) if ((e || se.acceptData(n)) && (r = n[o], s = r && l[r])) {
                if (s.events) for (i in s.events) c[i] ? se.event.remove(n, i) : se.removeEvent(n, i, s.handle);
                l[r] && (delete l[r], u ? delete n[o] : typeof n.removeAttribute !== ke ? n.removeAttribute(o) : n[o] = null, G.push(r))
            }
        }
    }),
    se.fn.extend({
        "text": function(t) {
            return Me(this,
            function(t) {
                return void 0 === t ? se.text(this) : this.empty().append((this[0] && this[0].ownerDocument || me).createTextNode(t))
            },
            null, t, arguments.length)
        },
        "append": function() {
            return this.domManip(arguments,
            function(t) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var e = y(this, t);
                    e.appendChild(t)
                }
            })
        },
        "prepend": function() {
            return this.domManip(arguments,
            function(t) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var e = y(this, t);
                    e.insertBefore(t, e.firstChild)
                }
            })
        },
        "before": function() {
            return this.domManip(arguments,
            function(t) {
                this.parentNode && this.parentNode.insertBefore(t, this)
            })
        },
        "after": function() {
            return this.domManip(arguments,
            function(t) {
                this.parentNode && this.parentNode.insertBefore(t, this.nextSibling)
            })
        },
        "remove": function(t, e) {
            for (var n, i = t ? se.filter(t, this) : this, r = 0; null != (n = i[r]); r++) e || 1 !== n.nodeType || se.cleanData(g(n)),
            n.parentNode && (e && se.contains(n.ownerDocument, n) && $(g(n, "script")), n.parentNode.removeChild(n));
            return this
        },
        "empty": function() {
            for (var t, e = 0; null != (t = this[e]); e++) {
                for (1 === t.nodeType && se.cleanData(g(t, !1)); t.firstChild;) t.removeChild(t.firstChild);
                t.options && se.nodeName(t, "select") && (t.options.length = 0)
            }
            return this
        },
        "clone": function(t, e) {
            return t = null == t ? !1 : t,
            e = null == e ? t: e,
            this.map(function() {
                return se.clone(this, t, e)
            })
        },
        "html": function(t) {
            return Me(this,
            function(t) {
                var e = this[0] || {},
                n = 0,
                i = this.length;
                if (void 0 === t) return 1 === e.nodeType ? e.innerHTML.replace(Le, "") : void 0;
                if (! ("string" != typeof t || We.test(t) || !ie.htmlSerialize && He.test(t) || !ie.leadingWhitespace && Re.test(t) || Ze[(qe.exec(t) || ["", ""])[1].toLowerCase()])) {
                    t = t.replace(Ue, "<$1></$2>");
                    try {
                        for (; i > n; n++) e = this[n] || {},
                        1 === e.nodeType && (se.cleanData(g(e, !1)), e.innerHTML = t);
                        e = 0
                    } catch(r) {}
                }
                e && this.empty().append(t)
            },
            null, t, arguments.length)
        },
        "replaceWith": function() {
            var t = arguments[0];
            return this.domManip(arguments,
            function(e) {
                t = this.parentNode,
                se.cleanData(g(this)),
                t && t.replaceChild(e, this)
            }),
            t && (t.length || t.nodeType) ? this: this.remove()
        },
        "detach": function(t) {
            return this.remove(t, !0)
        },
        "domManip": function(t, e) {
            t = Z.apply([], t);
            var n, i, r, s, a, o, l = 0,
            u = this.length,
            c = this,
            h = u - 1,
            d = t[0],
            f = se.isFunction(d);
            if (f || u > 1 && "string" == typeof d && !ie.checkClone && ze.test(d)) return this.each(function(n) {
                var i = c.eq(n);
                f && (t[0] = d.call(this, n, i.html())),
                i.domManip(t, e)
            });
            if (u && (o = se.buildFragment(t, this[0].ownerDocument, !1, this), n = o.firstChild, 1 === o.childNodes.length && (o = n), n)) {
                for (s = se.map(g(o, "script"), b), r = s.length; u > l; l++) i = o,
                l !== h && (i = se.clone(i, !0, !0), r && se.merge(s, g(i, "script"))),
                e.call(this[l], i, l);
                if (r) for (a = s[s.length - 1].ownerDocument, se.map(s, w), l = 0; r > l; l++) i = s[l],
                Be.test(i.type || "") && !se._data(i, "globalEval") && se.contains(a, i) && (i.src ? se._evalUrl && se._evalUrl(i.src) : se.globalEval((i.text || i.textContent || i.innerHTML || "").replace(Qe, "")));
                o = n = null
            }
            return this
        }
    }),
    se.each({
        "appendTo": "append",
        "prependTo": "prepend",
        "insertBefore": "before",
        "insertAfter": "after",
        "replaceAll": "replaceWith"
    },
    function(t, e) {
        se.fn[t] = function(t) {
            for (var n, i = 0,
            r = [], s = se(t), a = s.length - 1; a >= i; i++) n = i === a ? this: this.clone(!0),
            se(s[i])[e](n),
            J.apply(r, n.get());
            return this.pushStack(r)
        }
    });
    var Ke, tn = {}; !
    function() {
        var t, e, n = me.createElement("div"),
        i = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;padding:0;margin:0;border:0";
        n.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",
        t = n.getElementsByTagName("a")[0],
        t.style.cssText = "float:left;opacity:.5",
        ie.opacity = /^0.5/.test(t.style.opacity),
        ie.cssFloat = !!t.style.cssFloat,
        n.style.backgroundClip = "content-box",
        n.cloneNode(!0).style.backgroundClip = "",
        ie.clearCloneStyle = "content-box" === n.style.backgroundClip,
        t = n = null,
        ie.shrinkWrapBlocks = function() {
            var t, n, r, s;
            if (null == e) {
                if (t = me.getElementsByTagName("body")[0], !t) return;
                s = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px",
                n = me.createElement("div"),
                r = me.createElement("div"),
                t.appendChild(n).appendChild(r),
                e = !1,
                typeof r.style.zoom !== ke && (r.style.cssText = i + ";width:1px;padding:1px;zoom:1", r.innerHTML = "<div></div>", r.firstChild.style.width = "5px", e = 3 !== r.offsetWidth),
                t.removeChild(n),
                t = n = r = null
            }
            return e
        }
    } ();
    var en, nn, rn = /^margin/,
    sn = new RegExp("^(" + De + ")(?!px)[a-z%]+$", "i"),
    an = /^(top|right|bottom|left)$/;
    t.getComputedStyle ? (en = function(t) {
        return t.ownerDocument.defaultView.getComputedStyle(t, null)
    },
    nn = function(t, e, n) {
        var i, r, s, a, o = t.style;
        return n = n || en(t),
        a = n ? n.getPropertyValue(e) || n[e] : void 0,
        n && ("" !== a || se.contains(t.ownerDocument, t) || (a = se.style(t, e)), sn.test(a) && rn.test(e) && (i = o.width, r = o.minWidth, s = o.maxWidth, o.minWidth = o.maxWidth = o.width = a, a = n.width, o.width = i, o.minWidth = r, o.maxWidth = s)),
        void 0 === a ? a: a + ""
    }) : me.documentElement.currentStyle && (en = function(t) {
        return t.currentStyle
    },
    nn = function(t, e, n) {
        var i, r, s, a, o = t.style;
        return n = n || en(t),
        a = n ? n[e] : void 0,
        null == a && o && o[e] && (a = o[e]),
        sn.test(a) && !an.test(e) && (i = o.left, r = t.runtimeStyle, s = r && r.left, s && (r.left = t.currentStyle.left), o.left = "fontSize" === e ? "1em": a, a = o.pixelLeft + "px", o.left = i, s && (r.left = s)),
        void 0 === a ? a: a + "" || "auto"
    }),
    function() {
        function e() {
            var e, n, i = me.getElementsByTagName("body")[0];
            i && (e = me.createElement("div"), n = me.createElement("div"), e.style.cssText = u, i.appendChild(e).appendChild(n), n.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;position:absolute;display:block;padding:1px;border:1px;width:4px;margin-top:1%;top:1%", se.swap(i, null != i.style.zoom ? {
                "zoom": 1
            }: {},
            function() {
                r = 4 === n.offsetWidth
            }), s = !0, a = !1, o = !0, t.getComputedStyle && (a = "1%" !== (t.getComputedStyle(n, null) || {}).top, s = "4px" === (t.getComputedStyle(n, null) || {
                "width": "4px"
            }).width), i.removeChild(e), n = i = null)
        }
        var n, i, r, s, a, o, l = me.createElement("div"),
        u = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px",
        c = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;padding:0;margin:0;border:0";
        l.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",
        n = l.getElementsByTagName("a")[0],
        n.style.cssText = "float:left;opacity:.5",
        ie.opacity = /^0.5/.test(n.style.opacity),
        ie.cssFloat = !!n.style.cssFloat,
        l.style.backgroundClip = "content-box",
        l.cloneNode(!0).style.backgroundClip = "",
        ie.clearCloneStyle = "content-box" === l.style.backgroundClip,
        n = l = null,
        se.extend(ie, {
            "reliableHiddenOffsets": function() {
                if (null != i) return i;
                var t, e, n, r = me.createElement("div"),
                s = me.getElementsByTagName("body")[0];
                if (s) return r.setAttribute("className", "t"),
                r.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",
                t = me.createElement("div"),
                t.style.cssText = u,
                s.appendChild(t).appendChild(r),
                r.innerHTML = "<table><tr><td></td><td>t</td></tr></table>",
                e = r.getElementsByTagName("td"),
                e[0].style.cssText = "padding:0;margin:0;border:0;display:none",
                n = 0 === e[0].offsetHeight,
                e[0].style.display = "",
                e[1].style.display = "none",
                i = n && 0 === e[0].offsetHeight,
                s.removeChild(t),
                r = s = null,
                i
            },
            "boxSizing": function() {
                return null == r && e(),
                r
            },
            "boxSizingReliable": function() {
                return null == s && e(),
                s
            },
            "pixelPosition": function() {
                return null == a && e(),
                a
            },
            "reliableMarginRight": function() {
                var e, n, i, r;
                if (null == o && t.getComputedStyle) {
                    if (e = me.getElementsByTagName("body")[0], !e) return;
                    n = me.createElement("div"),
                    i = me.createElement("div"),
                    n.style.cssText = u,
                    e.appendChild(n).appendChild(i),
                    r = i.appendChild(me.createElement("div")),
                    r.style.cssText = i.style.cssText = c,
                    r.style.marginRight = r.style.width = "0",
                    i.style.width = "1px",
                    o = !parseFloat((t.getComputedStyle(r, null) || {}).marginRight),
                    e.removeChild(n)
                }
                return o
            }
        })
    } (),
    se.swap = function(t, e, n, i) {
        var r, s, a = {};
        for (s in e) a[s] = t.style[s],
        t.style[s] = e[s];
        r = n.apply(t, i || []);
        for (s in e) t.style[s] = a[s];
        return r
    };
    var on = /alpha\([^)]*\)/i,
    ln = /opacity\s*=\s*([^)]*)/,
    un = /^(none|table(?!-c[ea]).+)/,
    cn = new RegExp("^(" + De + ")(.*)$", "i"),
    hn = new RegExp("^([+-])=(" + De + ")", "i"),
    dn = {
        "position": "absolute",
        "visibility": "hidden",
        "display": "block"
    },
    fn = {
        "letterSpacing": 0,
        "fontWeight": 400
    },
    pn = ["Webkit", "O", "Moz", "ms"];
    se.extend({
        "cssHooks": {
            "opacity": {
                "get": function(t, e) {
                    if (e) {
                        var n = nn(t, "opacity");
                        return "" === n ? "1": n
                    }
                }
            }
        },
        "cssNumber": {
            "columnCount": !0,
            "fillOpacity": !0,
            "fontWeight": !0,
            "lineHeight": !0,
            "opacity": !0,
            "order": !0,
            "orphans": !0,
            "widows": !0,
            "zIndex": !0,
            "zoom": !0
        },
        "cssProps": {
            "float": ie.cssFloat ? "cssFloat": "styleFloat"
        },
        "style": function(t, e, n, i) {
            if (t && 3 !== t.nodeType && 8 !== t.nodeType && t.style) {
                var r, s, a, o = se.camelCase(e),
                l = t.style;
                if (e = se.cssProps[o] || (se.cssProps[o] = D(l, o)), a = se.cssHooks[e] || se.cssHooks[o], void 0 === n) return a && "get" in a && void 0 !== (r = a.get(t, !1, i)) ? r: l[e];
                if (s = typeof n, "string" === s && (r = hn.exec(n)) && (n = (r[1] + 1) * r[2] + parseFloat(se.css(t, e)), s = "number"), null != n && n === n && ("number" !== s || se.cssNumber[o] || (n += "px"), ie.clearCloneStyle || "" !== n || 0 !== e.indexOf("background") || (l[e] = "inherit"), !(a && "set" in a && void 0 === (n = a.set(t, n, i))))) try {
                    l[e] = "",
                    l[e] = n
                } catch(u) {}
            }
        },
        "css": function(t, e, n, i) {
            var r, s, a, o = se.camelCase(e);
            return e = se.cssProps[o] || (se.cssProps[o] = D(t.style, o)),
            a = se.cssHooks[e] || se.cssHooks[o],
            a && "get" in a && (s = a.get(t, !0, n)),
            void 0 === s && (s = nn(t, e, i)),
            "normal" === s && e in fn && (s = fn[e]),
            "" === n || n ? (r = parseFloat(s), n === !0 || se.isNumeric(r) ? r || 0 : s) : s
        }
    }),
    se.each(["height", "width"],
    function(t, e) {
        se.cssHooks[e] = {
            "get": function(t, n, i) {
                return n ? 0 === t.offsetWidth && un.test(se.css(t, "display")) ? se.swap(t, dn,
                function() {
                    return A(t, e, i)
                }) : A(t, e, i) : void 0
            },
            "set": function(t, n, i) {
                var r = i && en(t);
                return _(t, n, i ? M(t, e, i, ie.boxSizing() && "border-box" === se.css(t, "boxSizing", !1, r), r) : 0)
            }
        }
    }),
    ie.opacity || (se.cssHooks.opacity = {
        "get": function(t, e) {
            return ln.test((e && t.currentStyle ? t.currentStyle.filter: t.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "": e ? "1": ""
        },
        "set": function(t, e) {
            var n = t.style,
            i = t.currentStyle,
            r = se.isNumeric(e) ? "alpha(opacity=" + 100 * e + ")": "",
            s = i && i.filter || n.filter || "";
            n.zoom = 1,
            (e >= 1 || "" === e) && "" === se.trim(s.replace(on, "")) && n.removeAttribute && (n.removeAttribute("filter"), "" === e || i && !i.filter) || (n.filter = on.test(s) ? s.replace(on, r) : s + " " + r)
        }
    }),
    se.cssHooks.marginRight = S(ie.reliableMarginRight,
    function(t, e) {
        return e ? se.swap(t, {
            "display": "inline-block"
        },
        nn, [t, "marginRight"]) : void 0
    }),
    se.each({
        "margin": "",
        "padding": "",
        "border": "Width"
    },
    function(t, e) {
        se.cssHooks[t + e] = {
            "expand": function(n) {
                for (var i = 0,
                r = {},
                s = "string" == typeof n ? n.split(" ") : [n]; 4 > i; i++) r[t + Ee[i] + e] = s[i] || s[i - 2] || s[0];
                return r
            }
        },
        rn.test(t) || (se.cssHooks[t + e].set = _)
    }),
    se.fn.extend({
        "css": function(t, e) {
            return Me(this,
            function(t, e, n) {
                var i, r, s = {},
                a = 0;
                if (se.isArray(e)) {
                    for (i = en(t), r = e.length; r > a; a++) s[e[a]] = se.css(t, e[a], !1, i);
                    return s
                }
                return void 0 !== n ? se.style(t, e, n) : se.css(t, e)
            },
            t, e, arguments.length > 1)
        },
        "show": function() {
            return E(this, !0)
        },
        "hide": function() {
            return E(this)
        },
        "toggle": function(t) {
            return "boolean" == typeof t ? t ? this.show() : this.hide() : this.each(function() {
                _e(this) ? se(this).show() : se(this).hide()
            })
        }
    }),
    se.Tween = O,
    O.prototype = {
        "constructor": O,
        "init": function(t, e, n, i, r, s) {
            this.elem = t,
            this.prop = n,
            this.easing = r || "swing",
            this.options = e,
            this.start = this.now = this.cur(),
            this.end = i,
            this.unit = s || (se.cssNumber[n] ? "": "px")
        },
        "cur": function() {
            var t = O.propHooks[this.prop];
            return t && t.get ? t.get(this) : O.propHooks._default.get(this)
        },
        "run": function(t) {
            var e, n = O.propHooks[this.prop];
            return this.pos = e = this.options.duration ? se.easing[this.easing](t, this.options.duration * t, 0, 1, this.options.duration) : t,
            this.now = (this.end - this.start) * e + this.start,
            this.options.step && this.options.step.call(this.elem, this.now, this),
            n && n.set ? n.set(this) : O.propHooks._default.set(this),
            this
        }
    },
    O.prototype.init.prototype = O.prototype,
    O.propHooks = {
        "_default": {
            "get": function(t) {
                var e;
                return null == t.elem[t.prop] || t.elem.style && null != t.elem.style[t.prop] ? (e = se.css(t.elem, t.prop, ""), e && "auto" !== e ? e: 0) : t.elem[t.prop]
            },
            "set": function(t) {
                se.fx.step[t.prop] ? se.fx.step[t.prop](t) : t.elem.style && (null != t.elem.style[se.cssProps[t.prop]] || se.cssHooks[t.prop]) ? se.style(t.elem, t.prop, t.now + t.unit) : t.elem[t.prop] = t.now
            }
        }
    },
    O.propHooks.scrollTop = O.propHooks.scrollLeft = {
        "set": function(t) {
            t.elem.nodeType && t.elem.parentNode && (t.elem[t.prop] = t.now)
        }
    },
    se.easing = {
        "linear": function(t) {
            return t
        },
        "swing": function(t) {
            return.5 - Math.cos(t * Math.PI) / 2
        }
    },
    se.fx = O.prototype.init,
    se.fx.step = {};
    var mn, gn, vn = /^(?:toggle|show|hide)$/,
    yn = new RegExp("^(?:([+-])=|)(" + De + ")([a-z%]*)$", "i"),
    bn = /queueHooks$/,
    wn = [P],
    $n = {
        "*": [function(t, e) {
            var n = this.createTween(t, e),
            i = n.cur(),
            r = yn.exec(e),
            s = r && r[3] || (se.cssNumber[t] ? "": "px"),
            a = (se.cssNumber[t] || "px" !== s && +i) && yn.exec(se.css(n.elem, t)),
            o = 1,
            l = 20;
            if (a && a[3] !== s) {
                s = s || a[3],
                r = r || [],
                a = +i || 1;
                do o = o || ".5",
                a /= o,
                se.style(n.elem, t, a + s);
                while (o !== (o = n.cur() / i) && 1 !== o && --l)
            }
            return r && (a = n.start = +a || +i || 0, n.unit = s, n.end = r[1] ? a + (r[1] + 1) * r[2] : +r[2]),
            n
        }]
    };
    se.Animation = se.extend(L, {
        "tweener": function(t, e) {
            se.isFunction(t) ? (e = t, t = ["*"]) : t = t.split(" ");
            for (var n, i = 0,
            r = t.length; r > i; i++) n = t[i],
            $n[n] = $n[n] || [],
            $n[n].unshift(e)
        },
        "prefilter": function(t, e) {
            e ? wn.unshift(t) : wn.push(t)
        }
    }),
    se.speed = function(t, e, n) {
        var i = t && "object" == typeof t ? se.extend({},
        t) : {
            "complete": n || !n && e || se.isFunction(t) && t,
            "duration": t,
            "easing": n && e || e && !se.isFunction(e) && e
        };
        return i.duration = se.fx.off ? 0 : "number" == typeof i.duration ? i.duration: i.duration in se.fx.speeds ? se.fx.speeds[i.duration] : se.fx.speeds._default,
        (null == i.queue || i.queue === !0) && (i.queue = "fx"),
        i.old = i.complete,
        i.complete = function() {
            se.isFunction(i.old) && i.old.call(this),
            i.queue && se.dequeue(this, i.queue)
        },
        i
    },
    se.fn.extend({
        "fadeTo": function(t, e, n, i) {
            return this.filter(_e).css("opacity", 0).show().end().animate({
                "opacity": e
            },
            t, n, i)
        },
        "animate": function(t, e, n, i) {
            var r = se.isEmptyObject(t),
            s = se.speed(e, n, i),
            a = function() {
                var e = L(this, se.extend({},
                t), s); (r || se._data(this, "finish")) && e.stop(!0)
            };
            return a.finish = a,
            r || s.queue === !1 ? this.each(a) : this.queue(s.queue, a)
        },
        "stop": function(t, e, n) {
            var i = function(t) {
                var e = t.stop;
                delete t.stop,
                e(n)
            };
            return "string" != typeof t && (n = e, e = t, t = void 0),
            e && t !== !1 && this.queue(t || "fx", []),
            this.each(function() {
                var e = !0,
                r = null != t && t + "queueHooks",
                s = se.timers,
                a = se._data(this);
                if (r) a[r] && a[r].stop && i(a[r]);
                else for (r in a) a[r] && a[r].stop && bn.test(r) && i(a[r]);
                for (r = s.length; r--;) s[r].elem !== this || null != t && s[r].queue !== t || (s[r].anim.stop(n), e = !1, s.splice(r, 1)); (e || !n) && se.dequeue(this, t)
            })
        },
        "finish": function(t) {
            return t !== !1 && (t = t || "fx"),
            this.each(function() {
                var e, n = se._data(this),
                i = n[t + "queue"],
                r = n[t + "queueHooks"],
                s = se.timers,
                a = i ? i.length: 0;
                for (n.finish = !0, se.queue(this, t, []), r && r.stop && r.stop.call(this, !0), e = s.length; e--;) s[e].elem === this && s[e].queue === t && (s[e].anim.stop(!0), s.splice(e, 1));
                for (e = 0; a > e; e++) i[e] && i[e].finish && i[e].finish.call(this);
                delete n.finish
            })
        }
    }),
    se.each(["toggle", "show", "hide"],
    function(t, e) {
        var n = se.fn[e];
        se.fn[e] = function(t, i, r) {
            return null == t || "boolean" == typeof t ? n.apply(this, arguments) : this.animate(N(e, !0), t, i, r)
        }
    }),
    se.each({
        "slideDown": N("show"),
        "slideUp": N("hide"),
        "slideToggle": N("toggle"),
        "fadeIn": {
            "opacity": "show"
        },
        "fadeOut": {
            "opacity": "hide"
        },
        "fadeToggle": {
            "opacity": "toggle"
        }
    },
    function(t, e) {
        se.fn[t] = function(t, n, i) {
            return this.animate(e, t, n, i)
        }
    }),
    se.timers = [],
    se.fx.tick = function() {
        var t, e = se.timers,
        n = 0;
        for (mn = se.now(); n < e.length; n++) t = e[n],
        t() || e[n] !== t || e.splice(n--, 1);
        e.length || se.fx.stop(),
        mn = void 0
    },
    se.fx.timer = function(t) {
        se.timers.push(t),
        t() ? se.fx.start() : se.timers.pop()
    },
    se.fx.interval = 13,
    se.fx.start = function() {
        gn || (gn = setInterval(se.fx.tick, se.fx.interval))
    },
    se.fx.stop = function() {
        clearInterval(gn),
        gn = null
    },
    se.fx.speeds = {
        "slow": 600,
        "fast": 200,
        "_default": 400
    },
    se.fn.delay = function(t, e) {
        return t = se.fx ? se.fx.speeds[t] || t: t,
        e = e || "fx",
        this.queue(e,
        function(e, n) {
            var i = setTimeout(e, t);
            n.stop = function() {
                clearTimeout(i)
            }
        })
    },
    function() {
        var t, e, n, i, r = me.createElement("div");
        r.setAttribute("className", "t"),
        r.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",
        t = r.getElementsByTagName("a")[0],
        n = me.createElement("select"),
        i = n.appendChild(me.createElement("option")),
        e = r.getElementsByTagName("input")[0],
        t.style.cssText = "top:1px",
        ie.getSetAttribute = "t" !== r.className,
        ie.style = /top/.test(t.getAttribute("style")),
        ie.hrefNormalized = "/a" === t.getAttribute("href"),
        ie.checkOn = !!e.value,
        ie.optSelected = i.selected,
        ie.enctype = !!me.createElement("form").enctype,
        n.disabled = !0,
        ie.optDisabled = !i.disabled,
        e = me.createElement("input"),
        e.setAttribute("value", ""),
        ie.input = "" === e.getAttribute("value"),
        e.value = "t",
        e.setAttribute("type", "radio"),
        ie.radioValue = "t" === e.value,
        t = e = n = i = r = null
    } ();
    var xn = /\r/g;
    se.fn.extend({
        "val": function(t) {
            var e, n, i, r = this[0]; {
                if (arguments.length) return i = se.isFunction(t),
                this.each(function(n) {
                    var r;
                    1 === this.nodeType && (r = i ? t.call(this, n, se(this).val()) : t, null == r ? r = "": "number" == typeof r ? r += "": se.isArray(r) && (r = se.map(r,
                    function(t) {
                        return null == t ? "": t + ""
                    })), e = se.valHooks[this.type] || se.valHooks[this.nodeName.toLowerCase()], e && "set" in e && void 0 !== e.set(this, r, "value") || (this.value = r))
                });
                if (r) return e = se.valHooks[r.type] || se.valHooks[r.nodeName.toLowerCase()],
                e && "get" in e && void 0 !== (n = e.get(r, "value")) ? n: (n = r.value, "string" == typeof n ? n.replace(xn, "") : null == n ? "": n)
            }
        }
    }),
    se.extend({
        "valHooks": {
            "option": {
                "get": function(t) {
                    var e = se.find.attr(t, "value");
                    return null != e ? e: se.text(t)
                }
            },
            "select": {
                "get": function(t) {
                    for (var e, n, i = t.options,
                    r = t.selectedIndex,
                    s = "select-one" === t.type || 0 > r,
                    a = s ? null: [], o = s ? r + 1 : i.length, l = 0 > r ? o: s ? r: 0; o > l; l++) if (n = i[l], !(!n.selected && l !== r || (ie.optDisabled ? n.disabled: null !== n.getAttribute("disabled")) || n.parentNode.disabled && se.nodeName(n.parentNode, "optgroup"))) {
                        if (e = se(n).val(), s) return e;
                        a.push(e)
                    }
                    return a
                },
                "set": function(t, e) {
                    for (var n, i, r = t.options,
                    s = se.makeArray(e), a = r.length; a--;) if (i = r[a], se.inArray(se.valHooks.option.get(i), s) >= 0) try {
                        i.selected = n = !0
                    } catch(o) {
                        i.scrollHeight
                    } else i.selected = !1;
                    return n || (t.selectedIndex = -1),
                    r
                }
            }
        }
    }),
    se.each(["radio", "checkbox"],
    function() {
        se.valHooks[this] = {
            "set": function(t, e) {
                return se.isArray(e) ? t.checked = se.inArray(se(t).val(), e) >= 0 : void 0
            }
        },
        ie.checkOn || (se.valHooks[this].get = function(t) {
            return null === t.getAttribute("value") ? "on": t.value
        })
    });
    var Cn, kn, Tn = se.expr.attrHandle,
    Sn = /^(?:checked|selected)$/i,
    Dn = ie.getSetAttribute,
    En = ie.input;
    se.fn.extend({
        "attr": function(t, e) {
            return Me(this, se.attr, t, e, arguments.length > 1)
        },
        "removeAttr": function(t) {
            return this.each(function() {
                se.removeAttr(this, t)
            })
        }
    }),
    se.extend({
        "attr": function(t, e, n) {
            var i, r, s = t.nodeType;
            if (t && 3 !== s && 8 !== s && 2 !== s) return typeof t.getAttribute === ke ? se.prop(t, e, n) : (1 === s && se.isXMLDoc(t) || (e = e.toLowerCase(), i = se.attrHooks[e] || (se.expr.match.bool.test(e) ? kn: Cn)), void 0 === n ? i && "get" in i && null !== (r = i.get(t, e)) ? r: (r = se.find.attr(t, e), null == r ? void 0 : r) : null !== n ? i && "set" in i && void 0 !== (r = i.set(t, n, e)) ? r: (t.setAttribute(e, n + ""), n) : void se.removeAttr(t, e))
        },
        "removeAttr": function(t, e) {
            var n, i, r = 0,
            s = e && e.match(we);
            if (s && 1 === t.nodeType) for (; n = s[r++];) i = se.propFix[n] || n,
            se.expr.match.bool.test(n) ? En && Dn || !Sn.test(n) ? t[i] = !1 : t[se.camelCase("default-" + n)] = t[i] = !1 : se.attr(t, n, ""),
            t.removeAttribute(Dn ? n: i)
        },
        "attrHooks": {
            "type": {
                "set": function(t, e) {
                    if (!ie.radioValue && "radio" === e && se.nodeName(t, "input")) {
                        var n = t.value;
                        return t.setAttribute("type", e),
                        n && (t.value = n),
                        e
                    }
                }
            }
        }
    }),
    kn = {
        "set": function(t, e, n) {
            return e === !1 ? se.removeAttr(t, n) : En && Dn || !Sn.test(n) ? t.setAttribute(!Dn && se.propFix[n] || n, n) : t[se.camelCase("default-" + n)] = t[n] = !0,
            n
        }
    },
    se.each(se.expr.match.bool.source.match(/\w+/g),
    function(t, e) {
        var n = Tn[e] || se.find.attr;
        Tn[e] = En && Dn || !Sn.test(e) ?
        function(t, e, i) {
            var r, s;
            return i || (s = Tn[e], Tn[e] = r, r = null != n(t, e, i) ? e.toLowerCase() : null, Tn[e] = s),
            r
        }: function(t, e, n) {
            return n ? void 0 : t[se.camelCase("default-" + e)] ? e.toLowerCase() : null
        }
    }),
    En && Dn || (se.attrHooks.value = {
        "set": function(t, e, n) {
            return se.nodeName(t, "input") ? void(t.defaultValue = e) : Cn && Cn.set(t, e, n)
        }
    }),
    Dn || (Cn = {
        "set": function(t, e, n) {
            var i = t.getAttributeNode(n);
            return i || t.setAttributeNode(i = t.ownerDocument.createAttribute(n)),
            i.value = e += "",
            "value" === n || e === t.getAttribute(n) ? e: void 0
        }
    },
    Tn.id = Tn.name = Tn.coords = function(t, e, n) {
        var i;
        return n ? void 0 : (i = t.getAttributeNode(e)) && "" !== i.value ? i.value: null
    },
    se.valHooks.button = {
        "get": function(t, e) {
            var n = t.getAttributeNode(e);
            return n && n.specified ? n.value: void 0
        },
        "set": Cn.set
    },
    se.attrHooks.contenteditable = {
        "set": function(t, e, n) {
            Cn.set(t, "" === e ? !1 : e, n)
        }
    },
    se.each(["width", "height"],
    function(t, e) {
        se.attrHooks[e] = {
            "set": function(t, n) {
                return "" === n ? (t.setAttribute(e, "auto"), n) : void 0
            }
        }
    })),
    ie.style || (se.attrHooks.style = {
        "get": function(t) {
            return t.style.cssText || void 0
        },
        "set": function(t, e) {
            return t.style.cssText = e + ""
        }
    });
    var _n = /^(?:input|select|textarea|button|object)$/i,
    Mn = /^(?:a|area)$/i;
    se.fn.extend({
        "prop": function(t, e) {
            return Me(this, se.prop, t, e, arguments.length > 1)
        },
        "removeProp": function(t) {
            return t = se.propFix[t] || t,
            this.each(function() {
                try {
                    this[t] = void 0,
                    delete this[t]
                } catch(e) {}
            })
        }
    }),
    se.extend({
        "propFix": {
            "for": "htmlFor",
            "class": "className"
        },
        "prop": function(t, e, n) {
            var i, r, s, a = t.nodeType;
            if (t && 3 !== a && 8 !== a && 2 !== a) return s = 1 !== a || !se.isXMLDoc(t),
            s && (e = se.propFix[e] || e, r = se.propHooks[e]),
            void 0 !== n ? r && "set" in r && void 0 !== (i = r.set(t, n, e)) ? i: t[e] = n: r && "get" in r && null !== (i = r.get(t, e)) ? i: t[e]
        },
        "propHooks": {
            "tabIndex": {
                "get": function(t) {
                    var e = se.find.attr(t, "tabindex");
                    return e ? parseInt(e, 10) : _n.test(t.nodeName) || Mn.test(t.nodeName) && t.href ? 0 : -1
                }
            }
        }
    }),
    ie.hrefNormalized || se.each(["href", "src"],
    function(t, e) {
        se.propHooks[e] = {
            "get": function(t) {
                return t.getAttribute(e, 4)
            }
        }
    }),
    ie.optSelected || (se.propHooks.selected = {
        "get": function(t) {
            var e = t.parentNode;
            return e && (e.selectedIndex, e.parentNode && e.parentNode.selectedIndex),
            null
        }
    }),
    se.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"],
    function() {
        se.propFix[this.toLowerCase()] = this
    }),
    ie.enctype || (se.propFix.enctype = "encoding");
    var An = /[\t\r\n\f]/g;
    se.fn.extend({
        "addClass": function(t) {
            var e, n, i, r, s, a, o = 0,
            l = this.length,
            u = "string" == typeof t && t;
            if (se.isFunction(t)) return this.each(function(e) {
                se(this).addClass(t.call(this, e, this.className))
            });
            if (u) for (e = (t || "").match(we) || []; l > o; o++) if (n = this[o], i = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(An, " ") : " ")) {
                for (s = 0; r = e[s++];) i.indexOf(" " + r + " ") < 0 && (i += r + " ");
                a = se.trim(i),
                n.className !== a && (n.className = a)
            }
            return this
        },
        "removeClass": function(t) {
            var e, n, i, r, s, a, o = 0,
            l = this.length,
            u = 0 === arguments.length || "string" == typeof t && t;
            if (se.isFunction(t)) return this.each(function(e) {
                se(this).removeClass(t.call(this, e, this.className))
            });
            if (u) for (e = (t || "").match(we) || []; l > o; o++) if (n = this[o], i = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(An, " ") : "")) {
                for (s = 0; r = e[s++];) for (; i.indexOf(" " + r + " ") >= 0;) i = i.replace(" " + r + " ", " ");
                a = t ? se.trim(i) : "",
                n.className !== a && (n.className = a)
            }
            return this
        },
        "toggleClass": function(t, e) {
            var n = typeof t;
            return "boolean" == typeof e && "string" === n ? e ? this.addClass(t) : this.removeClass(t) : this.each(se.isFunction(t) ?
            function(n) {
                se(this).toggleClass(t.call(this, n, this.className, e), e)
            }: function() {
                if ("string" === n) for (var e, i = 0,
                r = se(this), s = t.match(we) || []; e = s[i++];) r.hasClass(e) ? r.removeClass(e) : r.addClass(e);
                else(n === ke || "boolean" === n) && (this.className && se._data(this, "__className__", this.className), this.className = this.className || t === !1 ? "": se._data(this, "__className__") || "")
            })
        },
        "hasClass": function(t) {
            for (var e = " " + t + " ",
            n = 0,
            i = this.length; i > n; n++) if (1 === this[n].nodeType && (" " + this[n].className + " ").replace(An, " ").indexOf(e) >= 0) return ! 0;
            return ! 1
        }
    }),
    se.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),
    function(t, e) {
        se.fn[e] = function(t, n) {
            return arguments.length > 0 ? this.on(e, null, t, n) : this.trigger(e)
        }
    }),
    se.fn.extend({
        "hover": function(t, e) {
            return this.mouseenter(t).mouseleave(e || t)
        },
        "bind": function(t, e, n) {
            return this.on(t, null, e, n)
        },
        "unbind": function(t, e) {
            return this.off(t, null, e)
        },
        "delegate": function(t, e, n, i) {
            return this.on(e, t, n, i)
        },
        "undelegate": function(t, e, n) {
            return 1 === arguments.length ? this.off(t, "**") : this.off(e, t || "**", n)
        }
    });
    var On = se.now(),
    Fn = /\?/,
    Nn = /(,)|(\[|{)|(}|])|"(?:[^"\\\r\n]|\\["\\\/bfnrt]|\\u[\da-fA-F]{4})*"\s*:?|true|false|null|-?(?!0\d)\d+(?:\.\d+|)(?:[eE][+-]?\d+|)/g;
    se.parseJSON = function(e) {
        if (t.JSON && t.JSON.parse) return t.JSON.parse(e + "");
        var n, i = null,
        r = se.trim(e + "");
        return r && !se.trim(r.replace(Nn,
        function(t, e, r, s) {
            return n && e && (i = 0),
            0 === i ? t: (n = r || e, i += !s - !r, "")
        })) ? Function("return " + r)() : se.error("Invalid JSON: " + e)
    },
    se.parseXML = function(e) {
        var n, i;
        if (!e || "string" != typeof e) return null;
        try {
            t.DOMParser ? (i = new DOMParser, n = i.parseFromString(e, "text/xml")) : (n = new ActiveXObject("Microsoft.XMLDOM"), n.async = "false", n.loadXML(e))
        } catch(r) {
            n = void 0
        }
        return n && n.documentElement && !n.getElementsByTagName("parsererror").length || se.error("Invalid XML: " + e),
        n
    };
    var jn, Pn, In = /#.*$/,
    Ln = /([?&])_=[^&]*/,
    Hn = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm,
    Rn = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/,
    Un = /^(?:GET|HEAD)$/,
    qn = /^\/\//,
    Yn = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/,
    Vn = {},
    Wn = {},
    zn = "*/".concat("*");
    try {
        Pn = location.href
    } catch(Bn) {
        Pn = me.createElement("a"),
        Pn.href = "",
        Pn = Pn.href
    }
    jn = Yn.exec(Pn.toLowerCase()) || [],
    se.extend({
        "active": 0,
        "lastModified": {},
        "etag": {},
        "ajaxSettings": {
            "url": Pn,
            "type": "GET",
            "isLocal": Rn.test(jn[1]),
            "global": !0,
            "processData": !0,
            "async": !0,
            "contentType": "application/x-www-form-urlencoded; charset=UTF-8",
            "accepts": {
                "*": zn,
                "text": "text/plain",
                "html": "text/html",
                "xml": "application/xml, text/xml",
                "json": "application/json, text/javascript"
            },
            "contents": {
                "xml": /xml/,
                "html": /html/,
                "json": /json/
            },
            "responseFields": {
                "xml": "responseXML",
                "text": "responseText",
                "json": "responseJSON"
            },
            "converters": {
                "* text": String,
                "text html": !0,
                "text json": se.parseJSON,
                "text xml": se.parseXML
            },
            "flatOptions": {
                "url": !0,
                "context": !0
            }
        },
        "ajaxSetup": function(t, e) {
            return e ? U(U(t, se.ajaxSettings), e) : U(se.ajaxSettings, t)
        },
        "ajaxPrefilter": H(Vn),
        "ajaxTransport": H(Wn),
        "ajax": function(t, e) {
            function n(t, e, n, i) {
                var r, c, v, y, w, x = e;
                2 !== b && (b = 2, o && clearTimeout(o), u = void 0, a = i || "", $.readyState = t > 0 ? 4 : 0, r = t >= 200 && 300 > t || 304 === t, n && (y = q(h, $, n)), y = Y(h, y, $, r), r ? (h.ifModified && (w = $.getResponseHeader("Last-Modified"), w && (se.lastModified[s] = w), w = $.getResponseHeader("etag"), w && (se.etag[s] = w)), 204 === t || "HEAD" === h.type ? x = "nocontent": 304 === t ? x = "notmodified": (x = y.state, c = y.data, v = y.error, r = !v)) : (v = x, (t || !x) && (x = "error", 0 > t && (t = 0))), $.status = t, $.statusText = (e || x) + "", r ? p.resolveWith(d, [c, x, $]) : p.rejectWith(d, [$, x, v]), $.statusCode(g), g = void 0, l && f.trigger(r ? "ajaxSuccess": "ajaxError", [$, h, r ? c: v]), m.fireWith(d, [$, x]), l && (f.trigger("ajaxComplete", [$, h]), --se.active || se.event.trigger("ajaxStop")))
            }
            "object" == typeof t && (e = t, t = void 0),
            e = e || {};
            var i, r, s, a, o, l, u, c, h = se.ajaxSetup({},
            e),
            d = h.context || h,
            f = h.context && (d.nodeType || d.jquery) ? se(d) : se.event,
            p = se.Deferred(),
            m = se.Callbacks("once memory"),
            g = h.statusCode || {},
            v = {},
            y = {},
            b = 0,
            w = "canceled",
            $ = {
                "readyState": 0,
                "getResponseHeader": function(t) {
                    var e;
                    if (2 === b) {
                        if (!c) for (c = {}; e = Hn.exec(a);) c[e[1].toLowerCase()] = e[2];
                        e = c[t.toLowerCase()]
                    }
                    return null == e ? null: e
                },
                "getAllResponseHeaders": function() {
                    return 2 === b ? a: null
                },
                "setRequestHeader": function(t, e) {
                    var n = t.toLowerCase();
                    return b || (t = y[n] = y[n] || t, v[t] = e),
                    this
                },
                "overrideMimeType": function(t) {
                    return b || (h.mimeType = t),
                    this
                },
                "statusCode": function(t) {
                    var e;
                    if (t) if (2 > b) for (e in t) g[e] = [g[e], t[e]];
                    else $.always(t[$.status]);
                    return this
                },
                "abort": function(t) {
                    var e = t || w;
                    return u && u.abort(e),
                    n(0, e),
                    this
                }
            };
            if (p.promise($).complete = m.add, $.success = $.done, $.error = $.fail, h.url = ((t || h.url || Pn) + "").replace(In, "").replace(qn, jn[1] + "//"), h.type = e.method || e.type || h.method || h.type, h.dataTypes = se.trim(h.dataType || "*").toLowerCase().match(we) || [""], null == h.crossDomain && (i = Yn.exec(h.url.toLowerCase()), h.crossDomain = !(!i || i[1] === jn[1] && i[2] === jn[2] && (i[3] || ("http:" === i[1] ? "80": "443")) === (jn[3] || ("http:" === jn[1] ? "80": "443")))), h.data && h.processData && "string" != typeof h.data && (h.data = se.param(h.data, h.traditional)), R(Vn, h, e, $), 2 === b) return $;
            l = h.global,
            l && 0 === se.active++&&se.event.trigger("ajaxStart"),
            h.type = h.type.toUpperCase(),
            h.hasContent = !Un.test(h.type),
            s = h.url,
            h.hasContent || (h.data && (s = h.url += (Fn.test(s) ? "&": "?") + h.data, delete h.data), h.cache === !1 && (h.url = Ln.test(s) ? s.replace(Ln, "$1_=" + On++) : s + (Fn.test(s) ? "&": "?") + "_=" + On++)),
            h.ifModified && (se.lastModified[s] && $.setRequestHeader("If-Modified-Since", se.lastModified[s]), se.etag[s] && $.setRequestHeader("If-None-Match", se.etag[s])),
            (h.data && h.hasContent && h.contentType !== !1 || e.contentType) && $.setRequestHeader("Content-Type", h.contentType),
            $.setRequestHeader("Accept", h.dataTypes[0] && h.accepts[h.dataTypes[0]] ? h.accepts[h.dataTypes[0]] + ("*" !== h.dataTypes[0] ? ", " + zn + "; q=0.01": "") : h.accepts["*"]);
            for (r in h.headers) $.setRequestHeader(r, h.headers[r]);
            if (h.beforeSend && (h.beforeSend.call(d, $, h) === !1 || 2 === b)) return $.abort();
            w = "abort";
            for (r in {
                "success": 1,
                "error": 1,
                "complete": 1
            }) $[r](h[r]);
            if (u = R(Wn, h, e, $)) {
                $.readyState = 1,
                l && f.trigger("ajaxSend", [$, h]),
                h.async && h.timeout > 0 && (o = setTimeout(function() {
                    $.abort("timeout")
                },
                h.timeout));
                try {
                    b = 1,
                    u.send(v, n)
                } catch(x) {
                    if (! (2 > b)) throw x;
                    n( - 1, x)
                }
            } else n( - 1, "No Transport");
            return $
        },
        "getJSON": function(t, e, n) {
            return se.get(t, e, n, "json")
        },
        "getScript": function(t, e) {
            return se.get(t, void 0, e, "script")
        }
    }),
    se.each(["get", "post"],
    function(t, e) {
        se[e] = function(t, n, i, r) {
            return se.isFunction(n) && (r = r || i, i = n, n = void 0),
            se.ajax({
                "url": t,
                "type": e,
                "dataType": r,
                "data": n,
                "success": i
            })
        }
    }),
    se.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"],
    function(t, e) {
        se.fn[e] = function(t) {
            return this.on(e, t)
        }
    }),
    se._evalUrl = function(t) {
        return se.ajax({
            "url": t,
            "type": "GET",
            "dataType": "script",
            "async": !1,
            "global": !1,
            "throws": !0
        })
    },
    se.fn.extend({
        "wrapAll": function(t) {
            if (se.isFunction(t)) return this.each(function(e) {
                se(this).wrapAll(t.call(this, e))
            });
            if (this[0]) {
                var e = se(t, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && e.insertBefore(this[0]),
                e.map(function() {
                    for (var t = this; t.firstChild && 1 === t.firstChild.nodeType;) t = t.firstChild;
                    return t
                }).append(this)
            }
            return this
        },
        "wrapInner": function(t) {
            return this.each(se.isFunction(t) ?
            function(e) {
                se(this).wrapInner(t.call(this, e))
            }: function() {
                var e = se(this),
                n = e.contents();
                n.length ? n.wrapAll(t) : e.append(t)
            })
        },
        "wrap": function(t) {
            var e = se.isFunction(t);
            return this.each(function(n) {
                se(this).wrapAll(e ? t.call(this, n) : t)
            })
        },
        "unwrap": function() {
            return this.parent().each(function() {
                se.nodeName(this, "body") || se(this).replaceWith(this.childNodes)
            }).end()
        }
    }),
    se.expr.filters.hidden = function(t) {
        return t.offsetWidth <= 0 && t.offsetHeight <= 0 || !ie.reliableHiddenOffsets() && "none" === (t.style && t.style.display || se.css(t, "display"))
    },
    se.expr.filters.visible = function(t) {
        return ! se.expr.filters.hidden(t)
    };
    var Gn = /%20/g,
    Qn = /\[\]$/,
    Zn = /\r?\n/g,
    Jn = /^(?:submit|button|image|reset|file)$/i,
    Xn = /^(?:input|select|textarea|keygen)/i;
    se.param = function(t, e) {
        var n, i = [],
        r = function(t, e) {
            e = se.isFunction(e) ? e() : null == e ? "": e,
            i[i.length] = encodeURIComponent(t) + "=" + encodeURIComponent(e)
        };
        if (void 0 === e && (e = se.ajaxSettings && se.ajaxSettings.traditional), se.isArray(t) || t.jquery && !se.isPlainObject(t)) se.each(t,
        function() {
            r(this.name, this.value)
        });
        else for (n in t) V(n, t[n], e, r);
        return i.join("&").replace(Gn, "+")
    },
    se.fn.extend({
        "serialize": function() {
            return se.param(this.serializeArray())
        },
        "serializeArray": function() {
            return this.map(function() {
                var t = se.prop(this, "elements");
                return t ? se.makeArray(t) : this
            }).filter(function() {
                var t = this.type;
                return this.name && !se(this).is(":disabled") && Xn.test(this.nodeName) && !Jn.test(t) && (this.checked || !Ae.test(t))
            }).map(function(t, e) {
                var n = se(this).val();
                return null == n ? null: se.isArray(n) ? se.map(n,
                function(t) {
                    return {
                        "name": e.name,
                        "value": t.replace(Zn, "\r\n")
                    }
                }) : {
                    "name": e.name,
                    "value": n.replace(Zn, "\r\n")
                }
            }).get()
        }
    }),
    se.ajaxSettings.xhr = void 0 !== t.ActiveXObject ?
    function() {
        return ! this.isLocal && /^(get|post|head|put|delete|options)$/i.test(this.type) && W() || z()
    }: W;
    var Kn = 0,
    ti = {},
    ei = se.ajaxSettings.xhr();
    t.ActiveXObject && se(t).on("unload",
    function() {
        for (var t in ti) ti[t](void 0, !0)
    }),
    ie.cors = !!ei && "withCredentials" in ei,
    ei = ie.ajax = !!ei,
    ei && se.ajaxTransport(function(t) {
        if (!t.crossDomain || ie.cors) {
            var e;
            return {
                "send": function(n, i) {
                    var r, s = t.xhr(),
                    a = ++Kn;
                    if (s.open(t.type, t.url, t.async, t.username, t.password), t.xhrFields) for (r in t.xhrFields) s[r] = t.xhrFields[r];
                    t.mimeType && s.overrideMimeType && s.overrideMimeType(t.mimeType),
                    t.crossDomain || n["X-Requested-With"] || (n["X-Requested-With"] = "XMLHttpRequest");
                    for (r in n) void 0 !== n[r] && s.setRequestHeader(r, n[r] + "");
                    s.send(t.hasContent && t.data || null),
                    e = function(n, r) {
                        var o, l, u;
                        if (e && (r || 4 === s.readyState)) if (delete ti[a], e = void 0, s.onreadystatechange = se.noop, r) 4 !== s.readyState && s.abort();
                        else {
                            u = {},
                            o = s.status,
                            "string" == typeof s.responseText && (u.text = s.responseText);
                            try {
                                l = s.statusText
                            } catch(c) {
                                l = ""
                            }
                            o || !t.isLocal || t.crossDomain ? 1223 === o && (o = 204) : o = u.text ? 200 : 404
                        }
                        u && i(o, l, u, s.getAllResponseHeaders())
                    },
                    t.async ? 4 === s.readyState ? setTimeout(e) : s.onreadystatechange = ti[a] = e: e()
                },
                "abort": function() {
                    e && e(void 0, !0)
                }
            }
        }
    }),
    se.ajaxSetup({
        "accepts": {
            "script": "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        "contents": {
            "script": /(?:java|ecma)script/
        },
        "converters": {
            "text script": function(t) {
                return se.globalEval(t),
                t
            }
        }
    }),
    se.ajaxPrefilter("script",
    function(t) {
        void 0 === t.cache && (t.cache = !1),
        t.crossDomain && (t.type = "GET", t.global = !1)
    }),
    se.ajaxTransport("script",
    function(t) {
        if (t.crossDomain) {
            var e, n = me.head || se("head")[0] || me.documentElement;
            return {
                "send": function(i, r) {
                    e = me.createElement("script"),
                    e.async = !0,
                    t.scriptCharset && (e.charset = t.scriptCharset),
                    e.src = t.url,
                    e.onload = e.onreadystatechange = function(t, n) { (n || !e.readyState || /loaded|complete/.test(e.readyState)) && (e.onload = e.onreadystatechange = null, e.parentNode && e.parentNode.removeChild(e), e = null, n || r(200, "success"))
                    },
                    n.insertBefore(e, n.firstChild)
                },
                "abort": function() {
                    e && e.onload(void 0, !0)
                }
            }
        }
    });
    var ni = [],
    ii = /(=)\?(?=&|$)|\?\?/;
    se.ajaxSetup({
        "jsonp": "callback",
        "jsonpCallback": function() {
            var t = ni.pop() || se.expando + "_" + On++;
            return this[t] = !0,
            t
        }
    }),
    se.ajaxPrefilter("json jsonp",
    function(e, n, i) {
        var r, s, a, o = e.jsonp !== !1 && (ii.test(e.url) ? "url": "string" == typeof e.data && !(e.contentType || "").indexOf("application/x-www-form-urlencoded") && ii.test(e.data) && "data");
        return o || "jsonp" === e.dataTypes[0] ? (r = e.jsonpCallback = se.isFunction(e.jsonpCallback) ? e.jsonpCallback() : e.jsonpCallback, o ? e[o] = e[o].replace(ii, "$1" + r) : e.jsonp !== !1 && (e.url += (Fn.test(e.url) ? "&": "?") + e.jsonp + "=" + r), e.converters["script json"] = function() {
            return a || se.error(r + " was not called"),
            a[0]
        },
        e.dataTypes[0] = "json", s = t[r], t[r] = function() {
            a = arguments
        },
        i.always(function() {
            t[r] = s,
            e[r] && (e.jsonpCallback = n.jsonpCallback, ni.push(r)),
            a && se.isFunction(s) && s(a[0]),
            a = s = void 0
        }), "script") : void 0
    }),
    se.parseHTML = function(t, e, n) {
        if (!t || "string" != typeof t) return null;
        "boolean" == typeof e && (n = e, e = !1),
        e = e || me;
        var i = de.exec(t),
        r = !n && [];
        return i ? [e.createElement(i[1])] : (i = se.buildFragment([t], e, r), r && r.length && se(r).remove(), se.merge([], i.childNodes))
    };
    var ri = se.fn.load;
    se.fn.load = function(t, e, n) {
        if ("string" != typeof t && ri) return ri.apply(this, arguments);
        var i, r, s, a = this,
        o = t.indexOf(" ");
        return o >= 0 && (i = t.slice(o, t.length), t = t.slice(0, o)),
        se.isFunction(e) ? (n = e, e = void 0) : e && "object" == typeof e && (s = "POST"),
        a.length > 0 && se.ajax({
            "url": t,
            "type": s,
            "dataType": "html",
            "data": e
        }).done(function(t) {
            r = arguments,
            a.html(i ? se("<div>").append(se.parseHTML(t)).find(i) : t)
        }).complete(n &&
        function(t, e) {
            a.each(n, r || [t.responseText, e, t])
        }),
        this
    },
    se.expr.filters.animated = function(t) {
        return se.grep(se.timers,
        function(e) {
            return t === e.elem
        }).length
    };
    var si = t.document.documentElement;
    se.offset = {
        "setOffset": function(t, e, n) {
            var i, r, s, a, o, l, u, c = se.css(t, "position"),
            h = se(t),
            d = {};
            "static" === c && (t.style.position = "relative"),
            o = h.offset(),
            s = se.css(t, "top"),
            l = se.css(t, "left"),
            u = ("absolute" === c || "fixed" === c) && se.inArray("auto", [s, l]) > -1,
            u ? (i = h.position(), a = i.top, r = i.left) : (a = parseFloat(s) || 0, r = parseFloat(l) || 0),
            se.isFunction(e) && (e = e.call(t, n, o)),
            null != e.top && (d.top = e.top - o.top + a),
            null != e.left && (d.left = e.left - o.left + r),
            "using" in e ? e.using.call(t, d) : h.css(d)
        }
    },
    se.fn.extend({
        "offset": function(t) {
            if (arguments.length) return void 0 === t ? this: this.each(function(e) {
                se.offset.setOffset(this, t, e)
            });
            var e, n, i = {
                "top": 0,
                "left": 0
            },
            r = this[0],
            s = r && r.ownerDocument;
            if (s) return e = s.documentElement,
            se.contains(e, r) ? (typeof r.getBoundingClientRect !== ke && (i = r.getBoundingClientRect()), n = B(s), {
                "top": i.top + (n.pageYOffset || e.scrollTop) - (e.clientTop || 0),
                "left": i.left + (n.pageXOffset || e.scrollLeft) - (e.clientLeft || 0)
            }) : i
        },
        "position": function() {
            if (this[0]) {
                var t, e, n = {
                    "top": 0,
                    "left": 0
                },
                i = this[0];
                return "fixed" === se.css(i, "position") ? e = i.getBoundingClientRect() : (t = this.offsetParent(), e = this.offset(), se.nodeName(t[0], "html") || (n = t.offset()), n.top += se.css(t[0], "borderTopWidth", !0), n.left += se.css(t[0], "borderLeftWidth", !0)),
                {
                    "top": e.top - n.top - se.css(i, "marginTop", !0),
                    "left": e.left - n.left - se.css(i, "marginLeft", !0)
                }
            }
        },
        "offsetParent": function() {
            return this.map(function() {
                for (var t = this.offsetParent || si; t && !se.nodeName(t, "html") && "static" === se.css(t, "position");) t = t.offsetParent;
                return t || si
            })
        }
    }),
    se.each({
        "scrollLeft": "pageXOffset",
        "scrollTop": "pageYOffset"
    },
    function(t, e) {
        var n = /Y/.test(e);
        se.fn[t] = function(i) {
            return Me(this,
            function(t, i, r) {
                var s = B(t);
                return void 0 === r ? s ? e in s ? s[e] : s.document.documentElement[i] : t[i] : void(s ? s.scrollTo(n ? se(s).scrollLeft() : r, n ? r: se(s).scrollTop()) : t[i] = r)
            },
            t, i, arguments.length, null)
        }
    }),
    se.each(["top", "left"],
    function(t, e) {
        se.cssHooks[e] = S(ie.pixelPosition,
        function(t, n) {
            return n ? (n = nn(t, e), sn.test(n) ? se(t).position()[e] + "px": n) : void 0
        })
    }),
    se.each({
        "Height": "height",
        "Width": "width"
    },
    function(t, e) {
        se.each({
            "padding": "inner" + t,
            "content": e,
            "": "outer" + t
        },
        function(n, i) {
            se.fn[i] = function(i, r) {
                var s = arguments.length && (n || "boolean" != typeof i),
                a = n || (i === !0 || r === !0 ? "margin": "border");
                return Me(this,
                function(e, n, i) {
                    var r;
                    return se.isWindow(e) ? e.document.documentElement["client" + t] : 9 === e.nodeType ? (r = e.documentElement, Math.max(e.body["scroll" + t], r["scroll" + t], e.body["offset" + t], r["offset" + t], r["client" + t])) : void 0 === i ? se.css(e, n, a) : se.style(e, n, i, a)
                },
                e, s ? i: void 0, s, null)
            }
        })
    }),
    se.fn.size = function() {
        return this.length
    },
    se.fn.andSelf = se.fn.addBack,
    "function" == typeof define && define.amd && define("jquery", [],
    function() {
        return se
    });
    var ai = t.jQuery,
    oi = t.$;
    return se.noConflict = function(e) {
        return t.$ === se && (t.$ = oi),
        e && t.jQuery === se && (t.jQuery = ai),
        se
    },
    typeof e === ke && (t.jQuery = t.$ = se),
    se
}),
function(t) {
    function e(t) {
        return new RegExp("^" + t + "$")
    }
    function n(t, e) {
        for (var n = Array.prototype.slice.call(arguments).splice(2), i = t.split("."), r = i.pop(), s = 0; s < i.length; s++) e = e[i[s]];
        return e[r].apply(this, n)
    }
    var i = [],
    r = {
        "options": {
            "prependExistingHelpBlock": !1,
            "sniffHtml": !0,
            "preventSubmit": !0,
            "submitError": !1,
            "submitSuccess": !1,
            "semanticallyStrict": !1,
            "autoAdd": {
                "helpBlocks": !0
            },
            "filter": function() {
                return ! 0
            }
        },
        "methods": {
            "init": function(e) {
                var n = t.extend(!0, {},
                r);
                n.options = t.extend(!0, n.options, e);
                var o = this,
                l = t.unique(o.map(function() {
                    return t(this).parents("form")[0]
                }).toArray());
                return t(l).bind("submit",
                function(e) {
                    var i = t(this),
                    r = 0,
                    s = i.find("input,textarea,select").not("[type=submit],[type=image]").filter(n.options.filter);
                    s.trigger("submit.validation").trigger("validationLostFocus.validation"),
                    s.each(function(e, n) {
                        var i = t(n),
                        s = i.parents(".control-group").first();
                        s.hasClass("warning") && (s.removeClass("warning").addClass("error"), r++)
                    }),
                    s.trigger("validationLostFocus.validation"),
                    r ? (n.options.preventSubmit && e.preventDefault(), i.addClass("error"), t.isFunction(n.options.submitError) && n.options.submitError(i, e, s.jqBootstrapValidation("collectErrors", !0))) : (i.removeClass("error"), t.isFunction(n.options.submitSuccess) && n.options.submitSuccess(i, e))
                }),
                this.each(function() {
                    var e = t(this),
                    r = e.parents(".control-group").first(),
                    o = r.find(".help-block").first(),
                    l = e.parents("form").first(),
                    u = [];
                    if (!o.length && n.options.autoAdd && n.options.autoAdd.helpBlocks && (o = t('<div class="help-block" />'), r.find(".controls").append(o), i.push(o[0])), n.options.sniffHtml) {
                        var c = "";
                        if (void 0 !== e.attr("pattern") && (c = "Not in the expected format<!-- data-validation-pattern-message to override -->", e.data("validationPatternMessage") && (c = e.data("validationPatternMessage")), e.data("validationPatternMessage", c), e.data("validationPatternRegex", e.attr("pattern"))), void 0 !== e.attr("max") || void 0 !== e.attr("aria-valuemax")) {
                            var h = e.attr(void 0 !== e.attr("max") ? "max": "aria-valuemax");
                            c = "Too high: Maximum of '" + h + "'<!-- data-validation-max-message to override -->",
                            e.data("validationMaxMessage") && (c = e.data("validationMaxMessage")),
                            e.data("validationMaxMessage", c),
                            e.data("validationMaxMax", h)
                        }
                        if (void 0 !== e.attr("min") || void 0 !== e.attr("aria-valuemin")) {
                            var d = e.attr(void 0 !== e.attr("min") ? "min": "aria-valuemin");
                            c = "Too low: Minimum of '" + d + "'<!-- data-validation-min-message to override -->",
                            e.data("validationMinMessage") && (c = e.data("validationMinMessage")),
                            e.data("validationMinMessage", c),
                            e.data("validationMinMin", d)
                        }
                        void 0 !== e.attr("maxlength") && (c = "Too long: Maximum of '" + e.attr("maxlength") + "' characters<!-- data-validation-maxlength-message to override -->", e.data("validationMaxlengthMessage") && (c = e.data("validationMaxlengthMessage")), e.data("validationMaxlengthMessage", c), e.data("validationMaxlengthMaxlength", e.attr("maxlength"))),
                        void 0 !== e.attr("minlength") && (c = "Too short: Minimum of '" + e.attr("minlength") + "' characters<!-- data-validation-minlength-message to override -->", e.data("validationMinlengthMessage") && (c = e.data("validationMinlengthMessage")), e.data("validationMinlengthMessage", c), e.data("validationMinlengthMinlength", e.attr("minlength"))),
                        (void 0 !== e.attr("required") || void 0 !== e.attr("aria-required")) && (c = n.builtInValidators.required.message, e.data("validationRequiredMessage") && (c = e.data("validationRequiredMessage")), e.data("validationRequiredMessage", c)),
                        void 0 !== e.attr("type") && "number" === e.attr("type").toLowerCase() && (c = n.builtInValidators.number.message, e.data("validationNumberMessage") && (c = e.data("validationNumberMessage")), e.data("validationNumberMessage", c)),
                        void 0 !== e.attr("type") && "email" === e.attr("type").toLowerCase() && (c = "Not a valid email address<!-- data-validator-validemail-message to override -->", e.data("validationValidemailMessage") ? c = e.data("validationValidemailMessage") : e.data("validationEmailMessage") && (c = e.data("validationEmailMessage")), e.data("validationValidemailMessage", c)),
                        void 0 !== e.attr("minchecked") && (c = "Not enough options checked; Minimum of '" + e.attr("minchecked") + "' required<!-- data-validation-minchecked-message to override -->", e.data("validationMincheckedMessage") && (c = e.data("validationMincheckedMessage")), e.data("validationMincheckedMessage", c), e.data("validationMincheckedMinchecked", e.attr("minchecked"))),
                        void 0 !== e.attr("maxchecked") && (c = "Too many options checked; Maximum of '" + e.attr("maxchecked") + "' required<!-- data-validation-maxchecked-message to override -->", e.data("validationMaxcheckedMessage") && (c = e.data("validationMaxcheckedMessage")), e.data("validationMaxcheckedMessage", c), e.data("validationMaxcheckedMaxchecked", e.attr("maxchecked")))
                    }
                    void 0 !== e.data("validation") && (u = e.data("validation").split(",")),
                    t.each(e.data(),
                    function(t) {
                        var e = t.replace(/([A-Z])/g, ",$1").split(",");
                        "validation" === e[0] && e[1] && u.push(e[1])
                    });
                    var f = u,
                    p = [];
                    do t.each(u,
                    function(t, e) {
                        u[t] = s(e)
                    }),
                    u = t.unique(u),
                    p = [],
                    t.each(f,
                    function(i, r) {
                        if (void 0 !== e.data("validation" + r + "Shortcut")) t.each(e.data("validation" + r + "Shortcut").split(","),
                        function(t, e) {
                            p.push(e)
                        });
                        else if (n.builtInValidators[r.toLowerCase()]) {
                            var a = n.builtInValidators[r.toLowerCase()];
                            "shortcut" === a.type.toLowerCase() && t.each(a.shortcut.split(","),
                            function(t, e) {
                                e = s(e),
                                p.push(e),
                                u.push(e)
                            })
                        }
                    }),
                    f = p;
                    while (f.length > 0);
                    var m = {};
                    t.each(u,
                    function(i, r) {
                        var a = e.data("validation" + r + "Message"),
                        o = void 0 !== a,
                        l = !1;
                        if (a = a ? a: "'" + r + "' validation failed <!-- Add attribute 'data-validation-" + r.toLowerCase() + "-message' to input to change this message -->", t.each(n.validatorTypes,
                        function(n, i) {
                            void 0 === m[n] && (m[n] = []),
                            l || void 0 === e.data("validation" + r + s(i.name)) || (m[n].push(t.extend(!0, {
                                "name": s(i.name),
                                "message": a
                            },
                            i.init(e, r))), l = !0)
                        }), !l && n.builtInValidators[r.toLowerCase()]) {
                            var u = t.extend(!0, {},
                            n.builtInValidators[r.toLowerCase()]);
                            o && (u.message = a);
                            var c = u.type.toLowerCase();
                            "shortcut" === c ? l = !0 : t.each(n.validatorTypes,
                            function(n, i) {
                                void 0 === m[n] && (m[n] = []),
                                l || c !== n.toLowerCase() || (e.data("validation" + r + s(i.name), u[i.name.toLowerCase()]), m[c].push(t.extend(u, i.init(e, r))), l = !0)
                            })
                        }
                        l || t.error("Cannot find validation info for '" + r + "'")
                    }),
                    o.data("original-contents", o.data("original-contents") ? o.data("original-contents") : o.html()),
                    o.data("original-role", o.data("original-role") ? o.data("original-role") : o.attr("role")),
                    r.data("original-classes", r.data("original-clases") ? r.data("original-classes") : r.attr("class")),
                    e.data("original-aria-invalid", e.data("original-aria-invalid") ? e.data("original-aria-invalid") : e.attr("aria-invalid")),
                    e.bind("validation.validation",
                    function(i, r) {
                        var s = a(e),
                        o = [];
                        return t.each(m,
                        function(i, a) { (s || s.length || r && r.includeEmpty || n.validatorTypes[i].blockSubmit && r && r.submitting) && t.each(a,
                            function(t, r) {
                                n.validatorTypes[i].validate(e, s, r) && o.push(r.message)
                            })
                        }),
                        o
                    }),
                    e.bind("getValidators.validation",
                    function() {
                        return m
                    }),
                    e.bind("submit.validation",
                    function() {
                        return e.triggerHandler("change.validation", {
                            "submitting": !0
                        })
                    }),
                    e.bind(["change"].join(".validation ") + ".validation",
                    function(i, s) {
                        var u = a(e),
                        c = [];
                        r.find("input,textarea,select").each(function(n, i) {
                            var r = c.length;
                            if (t.each(t(i).triggerHandler("validation.validation", s),
                            function(t, e) {
                                c.push(e)
                            }), c.length > r) t(i).attr("aria-invalid", "true");
                            else {
                                var a = e.data("original-aria-invalid");
                                t(i).attr("aria-invalid", void 0 !== a ? a: !1)
                            }
                        }),
                        l.find("input,select,textarea").not(e).not('[name="' + e.attr("name") + '"]').trigger("validationLostFocus.validation"),
                        c = t.unique(c.sort()),
                        c.length ? (r.removeClass("success error").addClass("warning"), o.html(n.options.semanticallyStrict && 1 === c.length ? c[0] + (n.options.prependExistingHelpBlock ? o.data("original-contents") : "") : '<ul role="alert"><li>' + c.join("</li><li>") + "</li></ul>" + (n.options.prependExistingHelpBlock ? o.data("original-contents") : ""))) : (r.removeClass("warning error success"), u.length > 0 && r.addClass("success"), o.html(o.data("original-contents"))),
                        "blur" === i.type && r.removeClass("success")
                    }),
                    e.bind("validationLostFocus.validation",
                    function() {
                        r.removeClass("success")
                    })
                })
            },
            "destroy": function() {
                return this.each(function() {
                    var e = t(this),
                    n = e.parents(".control-group").first(),
                    r = n.find(".help-block").first();
                    e.unbind(".validation"),
                    r.html(r.data("original-contents")),
                    n.attr("class", n.data("original-classes")),
                    e.attr("aria-invalid", e.data("original-aria-invalid")),
                    r.attr("role", e.data("original-role")),
                    i.indexOf(r[0]) > -1 && r.remove()
                })
            },
            "collectErrors": function() {
                var e = {};
                return this.each(function(n, i) {
                    var r = t(i),
                    s = r.attr("name"),
                    a = r.triggerHandler("validation.validation", {
                        "includeEmpty": !0
                    });
                    e[s] = t.extend(!0, a, e[s])
                }),
                t.each(e,
                function(t, n) {
                    0 === n.length && delete e[t]
                }),
                e
            },
            "hasErrors": function() {
                var e = [];
                return this.each(function(n, i) {
                    e = e.concat(t(i).triggerHandler("getValidators.validation") ? t(i).triggerHandler("validation.validation", {
                        "submitting": !0
                    }) : [])
                }),
                e.length > 0
            },
            "override": function(e) {
                r = t.extend(!0, r, e)
            }
        },
        "validatorTypes": {
            "callback": {
                "name": "callback",
                "init": function(t, e) {
                    return {
                        "validatorName": e,
                        "callback": t.data("validation" + e + "Callback"),
                        "lastValue": t.val(),
                        "lastValid": !0,
                        "lastFinished": !0
                    }
                },
                "validate": function(t, e, i) {
                    if (i.lastValue === e && i.lastFinished) return ! i.lastValid;
                    if (i.lastFinished === !0) {
                        i.lastValue = e,
                        i.lastValid = !0,
                        i.lastFinished = !1;
                        var r = i,
                        s = t;
                        n(i.callback, window, t, e,
                        function(t) {
                            r.lastValue === t.value && (r.lastValid = t.valid, t.message && (r.message = t.message), r.lastFinished = !0, s.data("validation" + r.validatorName + "Message", r.message), setTimeout(function() {
                                s.trigger("change.validation")
                            },
                            1))
                        })
                    }
                    return ! 1
                }
            },
            "ajax": {
                "name": "ajax",
                "init": function(t, e) {
                    return {
                        "validatorName": e,
                        "url": t.data("validation" + e + "Ajax"),
                        "lastValue": t.val(),
                        "lastValid": !0,
                        "lastFinished": !0
                    }
                },
                "validate": function(e, n, i) {
                    return "" + i.lastValue == "" + n && i.lastFinished === !0 ? i.lastValid === !1 : (i.lastFinished === !0 && (i.lastValue = n, i.lastValid = !0, i.lastFinished = !1, t.ajax({
                        "url": i.url,
                        "data": "value=" + n + "&field=" + e.attr("name"),
                        "dataType": "json",
                        "success": function(t) {
                            "" + i.lastValue == "" + t.value && (i.lastValid = !!t.valid, t.message && (i.message = t.message), i.lastFinished = !0, e.data("validation" + i.validatorName + "Message", i.message), setTimeout(function() {
                                e.trigger("change.validation")
                            },
                            1))
                        },
                        "failure": function() {
                            i.lastValid = !0,
                            i.message = "ajax call failed",
                            i.lastFinished = !0,
                            e.data("validation" + i.validatorName + "Message", i.message),
                            setTimeout(function() {
                                e.trigger("change.validation")
                            },
                            1)
                        }
                    })), !1)
                }
            },
            "regex": {
                "name": "regex",
                "init": function(t, n) {
                    return {
                        "regex": e(t.data("validation" + n + "Regex"))
                    }
                },
                "validate": function(t, e, n) {
                    return ! n.regex.test(e) && !n.negative || n.regex.test(e) && n.negative
                }
            },
            "required": {
                "name": "required",
                "init": function() {
                    return {}
                },
                "validate": function(t, e, n) {
                    return ! (0 !== e.length || n.negative) || !!(e.length > 0 && n.negative)
                },
                "blockSubmit": !0
            },
            "match": {
                "name": "match",
                "init": function(t, e) {
                    var n = t.parents("form").first().find('[name="' + t.data("validation" + e + "Match") + '"]').first();
                    return n.bind("validation.validation",
                    function() {
                        t.trigger("change.validation", {
                            "submitting": !0
                        })
                    }),
                    {
                        "element": n
                    }
                },
                "validate": function(t, e, n) {
                    return e !== n.element.val() && !n.negative || e === n.element.val() && n.negative
                },
                "blockSubmit": !0
            },
            "max": {
                "name": "max",
                "init": function(t, e) {
                    return {
                        "max": t.data("validation" + e + "Max")
                    }
                },
                "validate": function(t, e, n) {
                    return parseFloat(e, 10) > parseFloat(n.max, 10) && !n.negative || parseFloat(e, 10) <= parseFloat(n.max, 10) && n.negative
                }
            },
            "min": {
                "name": "min",
                "init": function(t, e) {
                    return {
                        "min": t.data("validation" + e + "Min")
                    }
                },
                "validate": function(t, e, n) {
                    return parseFloat(e) < parseFloat(n.min) && !n.negative || parseFloat(e) >= parseFloat(n.min) && n.negative
                }
            },
            "maxlength": {
                "name": "maxlength",
                "init": function(t, e) {
                    return {
                        "maxlength": t.data("validation" + e + "Maxlength")
                    }
                },
                "validate": function(t, e, n) {
                    return e.length > n.maxlength && !n.negative || e.length <= n.maxlength && n.negative
                }
            },
            "minlength": {
                "name": "minlength",
                "init": function(t, e) {
                    return {
                        "minlength": t.data("validation" + e + "Minlength")
                    }
                },
                "validate": function(t, e, n) {
                    return e.length < n.minlength && !n.negative || e.length >= n.minlength && n.negative
                }
            },
            "maxchecked": {
                "name": "maxchecked",
                "init": function(t, e) {
                    var n = t.parents("form").first().find('[name="' + t.attr("name") + '"]');
                    return n.bind("click.validation",
                    function() {
                        t.trigger("change.validation", {
                            "includeEmpty": !0
                        })
                    }),
                    {
                        "maxchecked": t.data("validation" + e + "Maxchecked"),
                        "elements": n
                    }
                },
                "validate": function(t, e, n) {
                    return n.elements.filter(":checked").length > n.maxchecked && !n.negative || n.elements.filter(":checked").length <= n.maxchecked && n.negative
                },
                "blockSubmit": !0
            },
            "minchecked": {
                "name": "minchecked",
                "init": function(t, e) {
                    var n = t.parents("form").first().find('[name="' + t.attr("name") + '"]');
                    return n.bind("click.validation",
                    function() {
                        t.trigger("change.validation", {
                            "includeEmpty": !0
                        })
                    }),
                    {
                        "minchecked": t.data("validation" + e + "Minchecked"),
                        "elements": n
                    }
                },
                "validate": function(t, e, n) {
                    return n.elements.filter(":checked").length < n.minchecked && !n.negative || n.elements.filter(":checked").length >= n.minchecked && n.negative
                },
                "blockSubmit": !0
            }
        },
        "builtInValidators": {
            "email": {
                "name": "Email",
                "type": "shortcut",
                "shortcut": "validemail"
            },
            "validemail": {
                "name": "Validemail",
                "type": "regex",
                "regex": "[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}",
                "message": "Not a valid email address<!-- data-validator-validemail-message to override -->"
            },
            "passwordagain": {
                "name": "Passwordagain",
                "type": "match",
                "match": "password",
                "message": "Does not match the given password<!-- data-validator-paswordagain-message to override -->"
            },
            "positive": {
                "name": "Positive",
                "type": "shortcut",
                "shortcut": "number,positivenumber"
            },
            "negative": {
                "name": "Negative",
                "type": "shortcut",
                "shortcut": "number,negativenumber"
            },
            "number": {
                "name": "Number",
                "type": "regex",
                "regex": "([+-]?\\d+(\\.\\d*)?([eE][+-]?[0-9]+)?)?",
                "message": "Must be a number<!-- data-validator-number-message to override -->"
            },
            "integer": {
                "name": "Integer",
                "type": "regex",
                "regex": "[+-]?\\d+",
                "message": "No decimal places allowed<!-- data-validator-integer-message to override -->"
            },
            "positivenumber": {
                "name": "Positivenumber",
                "type": "min",
                "min": 0,
                "message": "Must be a positive number<!-- data-validator-positivenumber-message to override -->"
            },
            "negativenumber": {
                "name": "Negativenumber",
                "type": "max",
                "max": 0,
                "message": "Must be a negative number<!-- data-validator-negativenumber-message to override -->"
            },
            "required": {
                "name": "Required",
                "type": "required",
                "message": "This is required<!-- data-validator-required-message to override -->"
            },
            "checkone": {
                "name": "Checkone",
                "type": "minchecked",
                "minchecked": 1,
                "message": "Check at least one option<!-- data-validation-checkone-message to override -->"
            }
        }
    },
    s = function(t) {
        return t.toLowerCase().replace(/(^|\s)([a-z])/g,
        function(t, e, n) {
            return e + n.toUpperCase()
        })
    },
    a = function(e) {
        var n = e.val(),
        i = e.attr("type");
        return "checkbox" === i && (n = e.is(":checked") ? n: ""),
        "radio" === i && (n = t('input[name="' + e.attr("name") + '"]:checked').length > 0 ? n: ""),
        n
    };
    t.fn.jqBootstrapValidation = function(e) {
        return r.methods[e] ? r.methods[e].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof e && e ? (t.error("Method " + e + " does not exist on jQuery.jqBootstrapValidation"), null) : r.methods.init.apply(this, arguments)
    },
    t.jqBootstrapValidation = function() {
        t(":input").not("[type=image],[type=submit]").jqBootstrapValidation.apply(this, arguments)
    }
} (jQuery),
jQuery.easing.jswing = jQuery.easing.swing,
jQuery.extend(jQuery.easing, {
    "def": "easeOutQuad",
    "swing": function(t, e, n, i, r) {
        return jQuery.easing[jQuery.easing.def](t, e, n, i, r)
    },
    "easeInQuad": function(t, e, n, i, r) {
        return i * (e /= r) * e + n
    },
    "easeOutQuad": function(t, e, n, i, r) {
        return - i * (e /= r) * (e - 2) + n
    },
    "easeInOutQuad": function(t, e, n, i, r) {
        return (e /= r / 2) < 1 ? i / 2 * e * e + n: -i / 2 * (--e * (e - 2) - 1) + n
    },
    "easeInCubic": function(t, e, n, i, r) {
        return i * (e /= r) * e * e + n
    },
    "easeOutCubic": function(t, e, n, i, r) {
        return i * ((e = e / r - 1) * e * e + 1) + n
    },
    "easeInOutCubic": function(t, e, n, i, r) {
        return (e /= r / 2) < 1 ? i / 2 * e * e * e + n: i / 2 * ((e -= 2) * e * e + 2) + n
    },
    "easeInQuart": function(t, e, n, i, r) {
        return i * (e /= r) * e * e * e + n
    },
    "easeOutQuart": function(t, e, n, i, r) {
        return - i * ((e = e / r - 1) * e * e * e - 1) + n
    },
    "easeInOutQuart": function(t, e, n, i, r) {
        return (e /= r / 2) < 1 ? i / 2 * e * e * e * e + n: -i / 2 * ((e -= 2) * e * e * e - 2) + n
    },
    "easeInQuint": function(t, e, n, i, r) {
        return i * (e /= r) * e * e * e * e + n
    },
    "easeOutQuint": function(t, e, n, i, r) {
        return i * ((e = e / r - 1) * e * e * e * e + 1) + n
    },
    "easeInOutQuint": function(t, e, n, i, r) {
        return (e /= r / 2) < 1 ? i / 2 * e * e * e * e * e + n: i / 2 * ((e -= 2) * e * e * e * e + 2) + n
    },
    "easeInSine": function(t, e, n, i, r) {
        return - i * Math.cos(e / r * (Math.PI / 2)) + i + n
    },
    "easeOutSine": function(t, e, n, i, r) {
        return i * Math.sin(e / r * (Math.PI / 2)) + n
    },
    "easeInOutSine": function(t, e, n, i, r) {
        return - i / 2 * (Math.cos(Math.PI * e / r) - 1) + n
    },
    "easeInExpo": function(t, e, n, i, r) {
        return 0 == e ? n: i * Math.pow(2, 10 * (e / r - 1)) + n
    },
    "easeOutExpo": function(t, e, n, i, r) {
        return e == r ? n + i: i * ( - Math.pow(2, -10 * e / r) + 1) + n
    },
    "easeInOutExpo": function(t, e, n, i, r) {
        return 0 == e ? n: e == r ? n + i: (e /= r / 2) < 1 ? i / 2 * Math.pow(2, 10 * (e - 1)) + n: i / 2 * ( - Math.pow(2, -10 * --e) + 2) + n
    },
    "easeInCirc": function(t, e, n, i, r) {
        return - i * (Math.sqrt(1 - (e /= r) * e) - 1) + n
    },
    "easeOutCirc": function(t, e, n, i, r) {
        return i * Math.sqrt(1 - (e = e / r - 1) * e) + n
    },
    "easeInOutCirc": function(t, e, n, i, r) {
        return (e /= r / 2) < 1 ? -i / 2 * (Math.sqrt(1 - e * e) - 1) + n: i / 2 * (Math.sqrt(1 - (e -= 2) * e) + 1) + n
    },
    "easeInElastic": function(t, e, n, i, r) {
        var s = 1.70158,
        a = 0,
        o = i;
        if (0 == e) return n;
        if (1 == (e /= r)) return n + i;
        if (a || (a = .3 * r), o < Math.abs(i)) {
            o = i;
            var s = a / 4
        } else var s = a / (2 * Math.PI) * Math.asin(i / o);
        return - (o * Math.pow(2, 10 * (e -= 1)) * Math.sin(2 * (e * r - s) * Math.PI / a)) + n
    },
    "easeOutElastic": function(t, e, n, i, r) {
        var s = 1.70158,
        a = 0,
        o = i;
        if (0 == e) return n;
        if (1 == (e /= r)) return n + i;
        if (a || (a = .3 * r), o < Math.abs(i)) {
            o = i;
            var s = a / 4
        } else var s = a / (2 * Math.PI) * Math.asin(i / o);
        return o * Math.pow(2, -10 * e) * Math.sin(2 * (e * r - s) * Math.PI / a) + i + n
    },
    "easeInOutElastic": function(t, e, n, i, r) {
        var s = 1.70158,
        a = 0,
        o = i;
        if (0 == e) return n;
        if (2 == (e /= r / 2)) return n + i;
        if (a || (a = .3 * r * 1.5), o < Math.abs(i)) {
            o = i;
            var s = a / 4
        } else var s = a / (2 * Math.PI) * Math.asin(i / o);
        return 1 > e ? -.5 * o * Math.pow(2, 10 * (e -= 1)) * Math.sin(2 * (e * r - s) * Math.PI / a) + n: o * Math.pow(2, -10 * (e -= 1)) * Math.sin(2 * (e * r - s) * Math.PI / a) * .5 + i + n
    },
    "easeInBack": function(t, e, n, i, r, s) {
        return void 0 == s && (s = 1.70158),
        i * (e /= r) * e * ((s + 1) * e - s) + n
    },
    "easeOutBack": function(t, e, n, i, r, s) {
        return void 0 == s && (s = 1.70158),
        i * ((e = e / r - 1) * e * ((s + 1) * e + s) + 1) + n
    },
    "easeInOutBack": function(t, e, n, i, r, s) {
        return void 0 == s && (s = 1.70158),
        (e /= r / 2) < 1 ? i / 2 * e * e * (((s *= 1.525) + 1) * e - s) + n: i / 2 * ((e -= 2) * e * (((s *= 1.525) + 1) * e + s) + 2) + n
    },
    "easeInBounce": function(t, e, n, i, r) {
        return i - jQuery.easing.easeOutBounce(t, r - e, 0, i, r) + n
    },
    "easeOutBounce": function(t, e, n, i, r) {
        return (e /= r) < 1 / 2.75 ? 7.5625 * i * e * e + n: 2 / 2.75 > e ? i * (7.5625 * (e -= 1.5 / 2.75) * e + .75) + n: 2.5 / 2.75 > e ? i * (7.5625 * (e -= 2.25 / 2.75) * e + .9375) + n: i * (7.5625 * (e -= 2.625 / 2.75) * e + .984375) + n
    },
    "easeInOutBounce": function(t, e, n, i, r) {
        return r / 2 > e ? .5 * jQuery.easing.easeInBounce(t, 2 * e, 0, i, r) + n: .5 * jQuery.easing.easeOutBounce(t, 2 * e - r, 0, i, r) + .5 * i + n
    }
}),
function(t, e) {
    var n = t.fn.spinner,
    i = function(e, n) {
        this.$element = t(e),
        this.options = t.extend({},
        t.fn.spinner.defaults, n),
        this.$input = this.$element.find(".spinner-input"),
        this.$element.on("keyup", this.$input, t.proxy(this.change, this)),
        this.$element.on("keydown", this.$input, t.proxy(this.keydown, this)),
        this.options.hold ? (this.$element.on("mousedown", ".spinner-up", t.proxy(function() {
            this.startSpin(!0)
        },
        this)), this.$element.on("mouseup", ".spinner-up, .spinner-down", t.proxy(this.stopSpin, this)), this.$element.on("mouseout", ".spinner-up, .spinner-down", t.proxy(this.stopSpin, this)), this.$element.on("mousedown", ".spinner-down", t.proxy(function() {
            this.startSpin(!1)
        },
        this))) : (this.$element.on("click", ".spinner-up", t.proxy(function() {
            this.step(!0)
        },
        this)), this.$element.on("click", ".spinner-down", t.proxy(function() {
            this.step(!1)
        },
        this))),
        this.$element.find(".spinner-up, .spinner-down").attr("tabIndex", -1),
        this.switches = {
            "count": 1,
            "enabled": !0
        },
        this.switches.speed = "medium" === this.options.speed ? 300 : "fast" === this.options.speed ? 100 : 500,
        this.lastValue = null,
        this.render(),
        this.options.disabled && this.disable()
    };
    i.prototype = {
        "constructor": i,
        "render": function() {
            var t = this.$input.val();
            t ? this.value(t) : this.$input.val(this.options.value),
            this.$input.attr("maxlength", (this.options.max + "").split("").length)
        },
        "change": function() {
            var t = this.$input.val();
            t / 1 ? this.options.value = t / 1 : (t = t.replace(/[^0-9]/g, "") || "", this.$input.val(t), this.options.value = t / 1),
            this.triggerChangedEvent()
        },
        "stopSpin": function() {
            this.switches.timeout !== e && (clearTimeout(this.switches.timeout), this.switches.count = 1, this.triggerChangedEvent())
        },
        "triggerChangedEvent": function() {
            var t = this.value();
            t !== this.lastValue && (this.lastValue = t, this.$element.trigger("changed", t), this.$element.trigger("change"))
        },
        "startSpin": function(e) {
            if (!this.options.disabled) {
                var n = this.switches.count;
                1 === n ? (this.step(e), n = 1) : n = 3 > n ? 1.5 : 8 > n ? 2.5 : 4,
                this.switches.timeout = setTimeout(t.proxy(function() {
                    this.iterator(e)
                },
                this), this.switches.speed / n),
                this.switches.count++
            }
        },
        "iterator": function(t) {
            this.step(t),
            this.startSpin(t)
        },
        "step": function(t) {
            var e, n, i = this.options.value,
            r = t ? this.options.max: this.options.min;
            if (t ? r > i: i > r) {
                var s = i + (t ? 1 : -1) * this.options.step;
                this.options.step % 1 !== 0 && (e = (this.options.step + "").split(".")[1].length, n = Math.pow(10, e), s = Math.round(s * n) / n),
                this.value((t ? s > r: r > s) ? r: s)
            } else if (this.options.cycle) {
                var a = t ? this.options.min: this.options.max;
                this.value(a)
            }
        },
        "value": function(t) {
            return ! isNaN(parseFloat(t)) && isFinite(t) ? (t = parseFloat(t), this.options.value = t, this.$input.val(t), this) : this.options.value
        },
        "disable": function() {
            this.options.disabled = !0,
            this.$input.attr("disabled", ""),
            this.$element.find("button").addClass("disabled")
        },
        "enable": function() {
            this.options.disabled = !1,
            this.$input.removeAttr("disabled"),
            this.$element.find("button").removeClass("disabled")
        },
        "keydown": function(t) {
            var e = t.keyCode;
            38 === e ? this.step(!0) : 40 === e && this.step(!1)
        }
    },
    t.fn.spinner = function(n) {
        var r, s = Array.prototype.slice.call(arguments, 1),
        a = this.each(function() {
            var e = t(this),
            a = e.data("spinner"),
            o = "object" == typeof n && n;
            a || e.data("spinner", a = new i(this, o)),
            "string" == typeof n && (r = a[n].apply(a, s))
        });
        return r === e ? a: r
    },
    t.fn.spinner.defaults = {
        "value": 1,
        "min": 1,
        "max": 999,
        "step": 1,
        "hold": !0,
        "speed": "medium",
        "disabled": !1
    },
    t.fn.spinner.Constructor = i,
    t.fn.spinner.noConflict = function() {
        return t.fn.spinner = n,
        this
    },
    t(function() {
        t("body").on("mousedown.spinner.data-api", ".spinner",
        function() {
            var e = t(this);
            e.data("spinner") || e.spinner(e.data())
        })
    })
} (window.jQuery),
+
function(t) {
    "use strict";
    function e() {
        var t = document.createElement("bootstrap"),
        e = {
            "WebkitTransition": "webkitTransitionEnd",
            "MozTransition": "transitionend",
            "OTransition": "oTransitionEnd otransitionend",
            "transition": "transitionend"
        };
        for (var n in e) if (void 0 !== t.style[n]) return {
            "end": e[n]
        };
        return ! 1
    }
    t.fn.emulateTransitionEnd = function(e) {
        var n = !1,
        i = this;
        t(this).one("bsTransitionEnd",
        function() {
            n = !0
        });
        var r = function() {
            n || t(i).trigger(t.support.transition.end)
        };
        return setTimeout(r, e),
        this
    },
    t(function() {
        t.support.transition = e(),
        t.support.transition && (t.event.special.bsTransitionEnd = {
            "bindType": t.support.transition.end,
            "delegateType": t.support.transition.end,
            "handle": function(e) {
                return t(e.target).is(this) ? e.handleObj.handler.apply(this, arguments) : void 0
            }
        })
    })
} (jQuery),
+
function(t) {
    "use strict";
    function e(e) {
        return this.each(function() {
            var i = t(this),
            r = i.data("bs.tooltip"),
            s = "object" == typeof e && e,
            a = s && s.selector; (r || "destroy" != e) && (a ? (r || i.data("bs.tooltip", r = {}), r[a] || (r[a] = new n(this, s))) : r || i.data("bs.tooltip", r = new n(this, s)), "string" == typeof e && r[e]())
        })
    }
    var n = function(t, e) {
        this.type = this.options = this.enabled = this.timeout = this.hoverState = this.$element = null,
        this.init("tooltip", t, e)
    };
    n.VERSION = "3.3.1",
    n.TRANSITION_DURATION = 150,
    n.DEFAULTS = {
        "animation": !0,
        "placement": "top",
        "selector": !1,
        "template": '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        "trigger": "hover focus",
        "title": "",
        "delay": 0,
        "html": !1,
        "container": !1,
        "viewport": {
            "selector": "body",
            "padding": 0
        }
    },
    n.prototype.init = function(e, n, i) {
        this.enabled = !0,
        this.type = e,
        this.$element = t(n),
        this.options = this.getOptions(i),
        this.$viewport = this.options.viewport && t(this.options.viewport.selector || this.options.viewport);
        for (var r = this.options.trigger.split(" "), s = r.length; s--;) {
            var a = r[s];
            if ("click" == a) this.$element.on("click." + this.type, this.options.selector, t.proxy(this.toggle, this));
            else if ("manual" != a) {
                var o = "hover" == a ? "mouseenter": "focusin",
                l = "hover" == a ? "mouseleave": "focusout";
                this.$element.on(o + "." + this.type, this.options.selector, t.proxy(this.enter, this)),
                this.$element.on(l + "." + this.type, this.options.selector, t.proxy(this.leave, this))
            }
        }
        this.options.selector ? this._options = t.extend({},
        this.options, {
            "trigger": "manual",
            "selector": ""
        }) : this.fixTitle()
    },
    n.prototype.getDefaults = function() {
        return n.DEFAULTS
    },
    n.prototype.getOptions = function(e) {
        return e = t.extend({},
        this.getDefaults(), this.$element.data(), e),
        e.delay && "number" == typeof e.delay && (e.delay = {
            "show": e.delay,
            "hide": e.delay
        }),
        e
    },
    n.prototype.getDelegateOptions = function() {
        var e = {},
        n = this.getDefaults();
        return this._options && t.each(this._options,
        function(t, i) {
            n[t] != i && (e[t] = i)
        }),
        e
    },
    n.prototype.enter = function(e) {
        var n = e instanceof this.constructor ? e: t(e.currentTarget).data("bs." + this.type);
        return n && n.$tip && n.$tip.is(":visible") ? void(n.hoverState = "in") : (n || (n = new this.constructor(e.currentTarget, this.getDelegateOptions()), t(e.currentTarget).data("bs." + this.type, n)), clearTimeout(n.timeout), n.hoverState = "in", n.options.delay && n.options.delay.show ? void(n.timeout = setTimeout(function() {
            "in" == n.hoverState && n.show()
        },
        n.options.delay.show)) : n.show())
    },
    n.prototype.leave = function(e) {
        var n = e instanceof this.constructor ? e: t(e.currentTarget).data("bs." + this.type);
        return n || (n = new this.constructor(e.currentTarget, this.getDelegateOptions()), t(e.currentTarget).data("bs." + this.type, n)),
        clearTimeout(n.timeout),
        n.hoverState = "out",
        n.options.delay && n.options.delay.hide ? void(n.timeout = setTimeout(function() {
            "out" == n.hoverState && n.hide()
        },
        n.options.delay.hide)) : n.hide()
    },
    n.prototype.show = function() {
        var e = t.Event("show.bs." + this.type);
        if (this.hasContent() && this.enabled) {
            this.$element.trigger(e);
            var i = t.contains(this.$element[0].ownerDocument.documentElement, this.$element[0]);
            if (e.isDefaultPrevented() || !i) return;
            var r = this,
            s = this.tip(),
            a = this.getUID(this.type);
            this.setContent(),
            s.attr("id", a),
            this.$element.attr("aria-describedby", a),
            this.options.animation && s.addClass("fade");
            var o = "function" == typeof this.options.placement ? this.options.placement.call(this, s[0], this.$element[0]) : this.options.placement,
            l = /\s?auto?\s?/i,
            u = l.test(o);
            u && (o = o.replace(l, "") || "top"),
            s.detach().css({
                "top": 0,
                "left": 0,
                "display": "block"
            }).addClass(o).data("bs." + this.type, this),
            this.options.container ? s.appendTo(this.options.container) : s.insertAfter(this.$element);
            var c = this.getPosition(),
            h = s[0].offsetWidth,
            d = s[0].offsetHeight;
            if (u) {
                var f = o,
                p = this.options.container ? t(this.options.container) : this.$element.parent(),
                m = this.getPosition(p);
                o = "bottom" == o && c.bottom + d > m.bottom ? "top": "top" == o && c.top - d < m.top ? "bottom": "right" == o && c.right + h > m.width ? "left": "left" == o && c.left - h < m.left ? "right": o,
                s.removeClass(f).addClass(o)
            }
            var g = this.getCalculatedOffset(o, c, h, d);
            this.applyPlacement(g, o);
            var v = function() {
                var t = r.hoverState;
                r.$element.trigger("shown.bs." + r.type),
                r.hoverState = null,
                "out" == t && r.leave(r)
            };
            t.support.transition && this.$tip.hasClass("fade") ? s.one("bsTransitionEnd", v).emulateTransitionEnd(n.TRANSITION_DURATION) : v()
        }
    },
    n.prototype.applyPlacement = function(e, n) {
        var i = this.tip(),
        r = i[0].offsetWidth,
        s = i[0].offsetHeight,
        a = parseInt(i.css("margin-top"), 10),
        o = parseInt(i.css("margin-left"), 10);
        isNaN(a) && (a = 0),
        isNaN(o) && (o = 0),
        e.top = e.top + a,
        e.left = e.left + o,
        t.offset.setOffset(i[0], t.extend({
            "using": function(t) {
                i.css({
                    "top": Math.round(t.top),
                    "left": Math.round(t.left)
                })
            }
        },
        e), 0),
        i.addClass("in");
        var l = i[0].offsetWidth,
        u = i[0].offsetHeight;
        "top" == n && u != s && (e.top = e.top + s - u);
        var c = this.getViewportAdjustedDelta(n, e, l, u);
        c.left ? e.left += c.left: e.top += c.top;
        var h = /top|bottom/.test(n),
        d = h ? 2 * c.left - r + l: 2 * c.top - s + u,
        f = h ? "offsetWidth": "offsetHeight";
        i.offset(e),
        this.replaceArrow(d, i[0][f], h)
    },
    n.prototype.replaceArrow = function(t, e, n) {
        this.arrow().css(n ? "left": "top", 50 * (1 - t / e) + "%").css(n ? "top": "left", "")
    },
    n.prototype.setContent = function() {
        var t = this.tip(),
        e = this.getTitle();
        t.find(".tooltip-inner")[this.options.html ? "html": "text"](e),
        t.removeClass("fade in top bottom left right")
    },
    n.prototype.hide = function(e) {
        function i() {
            "in" != r.hoverState && s.detach(),
            r.$element.removeAttr("aria-describedby").trigger("hidden.bs." + r.type),
            e && e()
        }
        var r = this,
        s = this.tip(),
        a = t.Event("hide.bs." + this.type);
        return this.$element.trigger(a),
        a.isDefaultPrevented() ? void 0 : (s.removeClass("in"), t.support.transition && this.$tip.hasClass("fade") ? s.one("bsTransitionEnd", i).emulateTransitionEnd(n.TRANSITION_DURATION) : i(), this.hoverState = null, this)
    },
    n.prototype.fixTitle = function() {
        var t = this.$element; (t.attr("title") || "string" != typeof t.attr("data-original-title")) && t.attr("data-original-title", t.attr("title") || "").attr("title", "")
    },
    n.prototype.hasContent = function() {
        return this.getTitle()
    },
    n.prototype.getPosition = function(e) {
        e = e || this.$element;
        var n = e[0],
        i = "BODY" == n.tagName,
        r = n.getBoundingClientRect();
        null == r.width && (r = t.extend({},
        r, {
            "width": r.right - r.left,
            "height": r.bottom - r.top
        }));
        var s = i ? {
            "top": 0,
            "left": 0
        }: e.offset(),
        a = {
            "scroll": i ? document.documentElement.scrollTop || document.body.scrollTop: e.scrollTop()
        },
        o = i ? {
            "width": t(window).width(),
            "height": t(window).height()
        }: null;
        return t.extend({},
        r, a, o, s)
    },
    n.prototype.getCalculatedOffset = function(t, e, n, i) {
        return "bottom" == t ? {
            "top": e.top + e.height,
            "left": e.left + e.width / 2 - n / 2
        }: "top" == t ? {
            "top": e.top - i,
            "left": e.left + e.width / 2 - n / 2
        }: "left" == t ? {
            "top": e.top + e.height / 2 - i / 2,
            "left": e.left - n
        }: {
            "top": e.top + e.height / 2 - i / 2,
            "left": e.left + e.width
        }
    },
    n.prototype.getViewportAdjustedDelta = function(t, e, n, i) {
        var r = {
            "top": 0,
            "left": 0
        };
        if (!this.$viewport) return r;
        var s = this.options.viewport && this.options.viewport.padding || 0,
        a = this.getPosition(this.$viewport);
        if (/right|left/.test(t)) {
            var o = e.top - s - a.scroll,
            l = e.top + s - a.scroll + i;
            o < a.top ? r.top = a.top - o: l > a.top + a.height && (r.top = a.top + a.height - l)
        } else {
            var u = e.left - s,
            c = e.left + s + n;
            u < a.left ? r.left = a.left - u: c > a.width && (r.left = a.left + a.width - c)
        }
        return r
    },
    n.prototype.getTitle = function() {
        var t, e = this.$element,
        n = this.options;
        return t = e.attr("data-original-title") || ("function" == typeof n.title ? n.title.call(e[0]) : n.title)
    },
    n.prototype.getUID = function(t) {
        do t += ~~ (1e6 * Math.random());
        while (document.getElementById(t));
        return t
    },
    n.prototype.tip = function() {
        return this.$tip = this.$tip || t(this.options.template)
    },
    n.prototype.arrow = function() {
        return this.$arrow = this.$arrow || this.tip().find(".tooltip-arrow")
    },
    n.prototype.enable = function() {
        this.enabled = !0
    },
    n.prototype.disable = function() {
        this.enabled = !1
    },
    n.prototype.toggleEnabled = function() {
        this.enabled = !this.enabled
    },
    n.prototype.toggle = function(e) {
        var n = this;
        e && (n = t(e.currentTarget).data("bs." + this.type), n || (n = new this.constructor(e.currentTarget, this.getDelegateOptions()), t(e.currentTarget).data("bs." + this.type, n))),
        n.tip().hasClass("in") ? n.leave(n) : n.enter(n)
    },
    n.prototype.destroy = function() {
        var t = this;
        clearTimeout(this.timeout),
        this.hide(function() {
            t.$element.off("." + t.type).removeData("bs." + t.type)
        })
    };
    var i = t.fn.tooltip;
    t.fn.tooltip = e,
    t.fn.tooltip.Constructor = n,
    t.fn.tooltip.noConflict = function() {
        return t.fn.tooltip = i,
        this
    }
} (jQuery),
+
function(t) {
    "use strict";
    function e(e) {
        var n, i = e.attr("data-target") || (n = e.attr("href")) && n.replace(/.*(?=#[^\s]+$)/, "");
        return t(i)
    }
    function n(e) {
        return this.each(function() {
            var n = t(this),
            r = n.data("bs.collapse"),
            s = t.extend({},
            i.DEFAULTS, n.data(), "object" == typeof e && e); ! r && s.toggle && "show" == e && (s.toggle = !1),
            r || n.data("bs.collapse", r = new i(this, s)),
            "string" == typeof e && r[e]()
        })
    }
    var i = function(e, n) {
        this.$element = t(e),
        this.options = t.extend({},
        i.DEFAULTS, n),
        this.$trigger = t(this.options.trigger).filter('[href="#' + e.id + '"], [data-target="#' + e.id + '"]'),
        this.transitioning = null,
        this.options.parent ? this.$parent = this.getParent() : this.addAriaAndCollapsedClass(this.$element, this.$trigger),
        this.options.toggle && this.toggle()
    };
    i.VERSION = "3.3.1",
    i.TRANSITION_DURATION = 350,
    i.DEFAULTS = {
        "toggle": !0,
        "trigger": '[data-toggle="collapse"]'
    },
    i.prototype.dimension = function() {
        var t = this.$element.hasClass("width");
        return t ? "width": "height"
    },
    i.prototype.show = function() {
        if (!this.transitioning && !this.$element.hasClass("in")) {
            var e, r = this.$parent && this.$parent.find("> .panel").children(".in, .collapsing");
            if (! (r && r.length && (e = r.data("bs.collapse"), e && e.transitioning))) {
                var s = t.Event("show.bs.collapse");
                if (this.$element.trigger(s), !s.isDefaultPrevented()) {
                    r && r.length && (n.call(r, "hide"), e || r.data("bs.collapse", null));
                    var a = this.dimension();
                    this.$element.removeClass("collapse").addClass("collapsing")[a](0).attr("aria-expanded", !0),
                    this.$trigger.removeClass("collapsed").attr("aria-expanded", !0),
                    this.transitioning = 1;
                    var o = function() {
                        this.$element.removeClass("collapsing").addClass("collapse in")[a](""),
                        this.transitioning = 0,
                        this.$element.trigger("shown.bs.collapse")
                    };
                    if (!t.support.transition) return o.call(this);
                    var l = t.camelCase(["scroll", a].join("-"));
                    this.$element.one("bsTransitionEnd", t.proxy(o, this)).emulateTransitionEnd(i.TRANSITION_DURATION)[a](this.$element[0][l])
                }
            }
        }
    },
    i.prototype.hide = function() {
        if (!this.transitioning && this.$element.hasClass("in")) {
            var e = t.Event("hide.bs.collapse");
            if (this.$element.trigger(e), !e.isDefaultPrevented()) {
                var n = this.dimension();
                this.$element[n](this.$element[n]())[0].offsetHeight,
                this.$element.addClass("collapsing").removeClass("collapse in").attr("aria-expanded", !1),
                this.$trigger.addClass("collapsed").attr("aria-expanded", !1),
                this.transitioning = 1;
                var r = function() {
                    this.transitioning = 0,
                    this.$element.removeClass("collapsing").addClass("collapse").trigger("hidden.bs.collapse")
                };
                return t.support.transition ? void this.$element[n](0).one("bsTransitionEnd", t.proxy(r, this)).emulateTransitionEnd(i.TRANSITION_DURATION) : r.call(this)
            }
        }
    },
    i.prototype.toggle = function() {
        this[this.$element.hasClass("in") ? "hide": "show"]()
    },
    i.prototype.getParent = function() {
        return t(this.options.parent).find('[data-toggle="collapse"][data-parent="' + this.options.parent + '"]').each(t.proxy(function(n, i) {
            var r = t(i);
            this.addAriaAndCollapsedClass(e(r), r)
        },
        this)).end()
    },
    i.prototype.addAriaAndCollapsedClass = function(t, e) {
        var n = t.hasClass("in");
        t.attr("aria-expanded", n),
        e.toggleClass("collapsed", !n).attr("aria-expanded", n)
    };
    var r = t.fn.collapse;
    t.fn.collapse = n,
    t.fn.collapse.Constructor = i,
    t.fn.collapse.noConflict = function() {
        return t.fn.collapse = r,
        this
    },
    t(document).on("click.bs.collapse.data-api", '[data-toggle="collapse"]',
    function(i) {
        var r = t(this);
        r.attr("data-target") || i.preventDefault();
        var s = e(r),
        a = s.data("bs.collapse"),
        o = a ? "toggle": t.extend({},
        r.data(), {
            "trigger": this
        });
        n.call(s, o)
    })
} (jQuery),
+
function(t) {
    "use strict";
    function e(n, i) {
        var r = t.proxy(this.process, this);
        this.$body = t("body"),
        this.$scrollElement = t(t(n).is("body") ? window: n),
        this.options = t.extend({},
        e.DEFAULTS, i),
        this.selector = (this.options.target || "") + " .nav li > a",
        this.offsets = [],
        this.targets = [],
        this.activeTarget = null,
        this.scrollHeight = 0,
        this.$scrollElement.on("scroll.bs.scrollspy", r),
        this.refresh(),
        this.process()
    }
    function n(n) {
        return this.each(function() {
            var i = t(this),
            r = i.data("bs.scrollspy"),
            s = "object" == typeof n && n;
            r || i.data("bs.scrollspy", r = new e(this, s)),
            "string" == typeof n && r[n]()
        })
    }
    e.VERSION = "3.3.1",
    e.DEFAULTS = {
        "offset": 10
    },
    e.prototype.getScrollHeight = function() {
        return this.$scrollElement[0].scrollHeight || Math.max(this.$body[0].scrollHeight, document.documentElement.scrollHeight)
    },
    e.prototype.refresh = function() {
        var e = "offset",
        n = 0;
        t.isWindow(this.$scrollElement[0]) || (e = "position", n = this.$scrollElement.scrollTop()),
        this.offsets = [],
        this.targets = [],
        this.scrollHeight = this.getScrollHeight();
        var i = this;
        this.$body.find(this.selector).map(function() {
            var i = t(this),
            r = i.data("target") || i.attr("href"),
            s = /^#./.test(r) && t(r);
            return s && s.length && s.is(":visible") && [[s[e]().top + n, r]] || null
        }).sort(function(t, e) {
            return t[0] - e[0]
        }).each(function() {
            i.offsets.push(this[0]),
            i.targets.push(this[1])
        })
    },
    e.prototype.process = function() {
        var t, e = this.$scrollElement.scrollTop() + this.options.offset,
        n = this.getScrollHeight(),
        i = this.options.offset + n - this.$scrollElement.height(),
        r = this.offsets,
        s = this.targets,
        a = this.activeTarget;
        if (this.scrollHeight != n && this.refresh(), e >= i) return a != (t = s[s.length - 1]) && this.activate(t);
        if (a && e < r[0]) return this.activeTarget = null,
        this.clear();
        for (t = r.length; t--;) a != s[t] && e >= r[t] && (!r[t + 1] || e <= r[t + 1]) && this.activate(s[t])
    },
    e.prototype.activate = function(e) {
        this.activeTarget = e,
        this.clear();
        var n = this.selector + '[data-target="' + e + '"],' + this.selector + '[href="' + e + '"]',
        i = t(n).parents("li").addClass("active");
        i.parent(".dropdown-menu").length && (i = i.closest("li.dropdown").addClass("active")),
        i.trigger("activate.bs.scrollspy")
    },
    e.prototype.clear = function() {
        t(this.selector).parentsUntil(this.options.target, ".active").removeClass("active")
    };
    var i = t.fn.scrollspy;
    t.fn.scrollspy = n,
    t.fn.scrollspy.Constructor = e,
    t.fn.scrollspy.noConflict = function() {
        return t.fn.scrollspy = i,
        this
    },
    t(window).on("load.bs.scrollspy.data-api",
    function() {
        t('[data-spy="scroll"]').each(function() {
            var e = t(this);
            n.call(e, e.data())
        })
    })
} (jQuery),
+
function(t) {
    "use strict";
    function e(e) {
        return this.each(function() {
            var i = t(this),
            r = i.data("bs.popover"),
            s = "object" == typeof e && e,
            a = s && s.selector; (r || "destroy" != e) && (a ? (r || i.data("bs.popover", r = {}), r[a] || (r[a] = new n(this, s))) : r || i.data("bs.popover", r = new n(this, s)), "string" == typeof e && r[e]())
        })
    }
    var n = function(t, e) {
        this.init("popover", t, e)
    };
    if (!t.fn.tooltip) throw new Error("Popover requires tooltip.js");
    n.VERSION = "3.3.1",
    n.DEFAULTS = t.extend({},
    t.fn.tooltip.Constructor.DEFAULTS, {
        "placement": "right",
        "trigger": "click",
        "content": "",
        "template": '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
    }),
    n.prototype = t.extend({},
    t.fn.tooltip.Constructor.prototype),
    n.prototype.constructor = n,
    n.prototype.getDefaults = function() {
        return n.DEFAULTS
    },
    n.prototype.setContent = function() {
        var t = this.tip(),
        e = this.getTitle(),
        n = this.getContent();
        t.find(".popover-title")[this.options.html ? "html": "text"](e),
        t.find(".popover-content").children().detach().end()[this.options.html ? "string" == typeof n ? "html": "append": "text"](n),
        t.removeClass("fade top bottom left right in"),
        t.find(".popover-title").html() || t.find(".popover-title").hide()
    },
    n.prototype.hasContent = function() {
        return this.getTitle() || this.getContent()
    },
    n.prototype.getContent = function() {
        var t = this.$element,
        e = this.options;
        return t.attr("data-content") || ("function" == typeof e.content ? e.content.call(t[0]) : e.content)
    },
    n.prototype.arrow = function() {
        return this.$arrow = this.$arrow || this.tip().find(".arrow")
    },
    n.prototype.tip = function() {
        return this.$tip || (this.$tip = t(this.options.template)),
        this.$tip
    };
    var i = t.fn.popover;
    t.fn.popover = e,
    t.fn.popover.Constructor = n,
    t.fn.popover.noConflict = function() {
        return t.fn.popover = i,
        this
    }
} (jQuery),
+
function(t) {
    "use strict";
    function e(e) {
        return this.each(function() {
            var i = t(this),
            r = i.data("bs.affix"),
            s = "object" == typeof e && e;
            r || i.data("bs.affix", r = new n(this, s)),
            "string" == typeof e && r[e]()
        })
    }
    var n = function(e, i) {
        this.options = t.extend({},
        n.DEFAULTS, i),
        this.$target = t(this.options.target).on("scroll.bs.affix.data-api", t.proxy(this.checkPosition, this)).on("click.bs.affix.data-api", t.proxy(this.checkPositionWithEventLoop, this)),
        this.$element = t(e),
        this.affixed = this.unpin = this.pinnedOffset = null,
        this.checkPosition()
    };
    n.VERSION = "3.3.1",
    n.RESET = "affix affix-top affix-bottom",
    n.DEFAULTS = {
        "offset": 0,
        "target": window
    },
    n.prototype.getState = function(t, e, n, i) {
        var r = this.$target.scrollTop(),
        s = this.$element.offset(),
        a = this.$target.height();
        if (null != n && "top" == this.affixed) return n > r ? "top": !1;
        if ("bottom" == this.affixed) return null != n ? r + this.unpin <= s.top ? !1 : "bottom": t - i >= r + a ? !1 : "bottom";
        var o = null == this.affixed,
        l = o ? r: s.top,
        u = o ? a: e;
        return null != n && n >= l ? "top": null != i && l + u >= t - i ? "bottom": !1
    },
    n.prototype.getPinnedOffset = function() {
        if (this.pinnedOffset) return this.pinnedOffset;
        this.$element.removeClass(n.RESET).addClass("affix");
        var t = this.$target.scrollTop(),
        e = this.$element.offset();
        return this.pinnedOffset = e.top - t
    },
    n.prototype.checkPositionWithEventLoop = function() {
        setTimeout(t.proxy(this.checkPosition, this), 1)
    },
    n.prototype.checkPosition = function() {
        if (this.$element.is(":visible")) {
            var e = this.$element.height(),
            i = this.options.offset,
            r = i.top,
            s = i.bottom,
            a = t("body").height();
            "object" != typeof i && (s = r = i),
            "function" == typeof r && (r = i.top(this.$element)),
            "function" == typeof s && (s = i.bottom(this.$element));
            var o = this.getState(a, e, r, s);
            if (this.affixed != o) {
                null != this.unpin && this.$element.css("top", "");
                var l = "affix" + (o ? "-" + o: ""),
                u = t.Event(l + ".bs.affix");
                if (this.$element.trigger(u), u.isDefaultPrevented()) return;
                this.affixed = o,
                this.unpin = "bottom" == o ? this.getPinnedOffset() : null,
                this.$element.removeClass(n.RESET).addClass(l).trigger(l.replace("affix", "affixed") + ".bs.affix")
            }
            "bottom" == o && this.$element.offset({
                "top": a - e - s
            })
        }
    };
    var i = t.fn.affix;
    t.fn.affix = e,
    t.fn.affix.Constructor = n,
    t.fn.affix.noConflict = function() {
        return t.fn.affix = i,
        this
    },
    t(window).on("load",
    function() {
        t('[data-spy="affix"]').each(function() {
            var n = t(this),
            i = n.data();
            i.offset = i.offset || {},
            null != i.offsetBottom && (i.offset.bottom = i.offsetBottom),
            null != i.offsetTop && (i.offset.top = i.offsetTop),
            e.call(n, i)
        })
    })
} (jQuery),
+
function(t) {
    "use strict";
    function e(e) {
        return this.each(function() {
            var i = t(this),
            r = i.data("bs.tab");
            r || i.data("bs.tab", r = new n(this)),
            "string" == typeof e && r[e]()
        })
    }
    var n = function(e) {
        this.element = t(e)
    };
    n.VERSION = "3.3.1",
    n.TRANSITION_DURATION = 150,
    n.prototype.show = function() {
        var e = this.element,
        n = e.closest("ul:not(.dropdown-menu)"),
        i = e.data("target");
        if (i || (i = e.attr("href"), i = i && i.replace(/.*(?=#[^\s]*$)/, "")), !e.parent("li").hasClass("active")) {
            var r = n.find(".active:last a"),
            s = t.Event("hide.bs.tab", {
                "relatedTarget": e[0]
            }),
            a = t.Event("show.bs.tab", {
                "relatedTarget": r[0]
            });
            if (r.trigger(s), e.trigger(a), !a.isDefaultPrevented() && !s.isDefaultPrevented()) {
                var o = t(i);
                this.activate(e.closest("li"), n),
                this.activate(o, o.parent(),
                function() {
                    r.trigger({
                        "type": "hidden.bs.tab",
                        "relatedTarget": e[0]
                    }),
                    e.trigger({
                        "type": "shown.bs.tab",
                        "relatedTarget": r[0]
                    })
                })
            }
        }
    },
    n.prototype.activate = function(e, i, r) {
        function s() {
            a.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !1),
            e.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded", !0),
            o ? (e[0].offsetWidth, e.addClass("in")) : e.removeClass("fade"),
            e.parent(".dropdown-menu") && e.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !0),
            r && r()
        }
        var a = i.find("> .active"),
        o = r && t.support.transition && (a.length && a.hasClass("fade") || !!i.find("> .fade").length);
        a.length && o ? a.one("bsTransitionEnd", s).emulateTransitionEnd(n.TRANSITION_DURATION) : s(),
        a.removeClass("in")
    };
    var i = t.fn.tab;
    t.fn.tab = e,
    t.fn.tab.Constructor = n,
    t.fn.tab.noConflict = function() {
        return t.fn.tab = i,
        this
    };
    var r = function(n) {
        n.preventDefault(),
        e.call(t(this), "show")
    };
    t(document).on("click.bs.tab.data-api", '[data-toggle="tab"]', r).on("click.bs.tab.data-api", '[data-toggle="pill"]', r)
} (jQuery),
+
function(t) {
    "use strict";
    function e(e) {
        return this.each(function() {
            var n = t(this),
            r = n.data("bs.alert");
            r || n.data("bs.alert", r = new i(this)),
            "string" == typeof e && r[e].call(n)
        })
    }
    var n = '[data-dismiss="alert"]',
    i = function(e) {
        t(e).on("click", n, this.close)
    };
    i.VERSION = "3.3.1",
    i.TRANSITION_DURATION = 150,
    i.prototype.close = function(e) {
        function n() {
            a.detach().trigger("closed.bs.alert").remove()
        }
        var r = t(this),
        s = r.attr("data-target");
        s || (s = r.attr("href"), s = s && s.replace(/.*(?=#[^\s]*$)/, ""));
        var a = t(s);
        e && e.preventDefault(),
        a.length || (a = r.closest(".alert")),
        a.trigger(e = t.Event("close.bs.alert")),
        e.isDefaultPrevented() || (a.removeClass("in"), t.support.transition && a.hasClass("fade") ? a.one("bsTransitionEnd", n).emulateTransitionEnd(i.TRANSITION_DURATION) : n())
    };
    var r = t.fn.alert;
    t.fn.alert = e,
    t.fn.alert.Constructor = i,
    t.fn.alert.noConflict = function() {
        return t.fn.alert = r,
        this
    },
    t(document).on("click.bs.alert.data-api", n, i.prototype.close)
} (jQuery),
+
function(t) {
    "use strict";
    function e(e) {
        e && 3 === e.which || (t(r).remove(), t(s).each(function() {
            var i = t(this),
            r = n(i),
            s = {
                "relatedTarget": this
            };
            r.hasClass("open") && (r.trigger(e = t.Event("hide.bs.dropdown", s)), e.isDefaultPrevented() || (i.attr("aria-expanded", "false"), r.removeClass("open").trigger("hidden.bs.dropdown", s)))
        }))
    }
    function n(e) {
        var n = e.attr("data-target");
        n || (n = e.attr("href"), n = n && /#[A-Za-z]/.test(n) && n.replace(/.*(?=#[^\s]*$)/, ""));
        var i = n && t(n);
        return i && i.length ? i: e.parent()
    }
    function i(e) {
        return this.each(function() {
            var n = t(this),
            i = n.data("bs.dropdown");
            i || n.data("bs.dropdown", i = new a(this)),
            "string" == typeof e && i[e].call(n)
        })
    }
    var r = ".dropdown-backdrop",
    s = '[data-toggle="dropdown"]',
    a = function(e) {
        t(e).on("click.bs.dropdown", this.toggle)
    };
    a.VERSION = "3.3.1",
    a.prototype.toggle = function(i) {
        var r = t(this);
        if (!r.is(".disabled, :disabled")) {
            var s = n(r),
            a = s.hasClass("open");
            if (e(), !a) {
                "ontouchstart" in document.documentElement && !s.closest(".navbar-nav").length && t('<div class="dropdown-backdrop"/>').insertAfter(t(this)).on("click", e);
                var o = {
                    "relatedTarget": this
                };
                if (s.trigger(i = t.Event("show.bs.dropdown", o)), i.isDefaultPrevented()) return;
                r.trigger("focus").attr("aria-expanded", "true"),
                s.toggleClass("open").trigger("shown.bs.dropdown", o)
            }
            return ! 1
        }
    },
    a.prototype.keydown = function(e) {
        if (/(38|40|27|32)/.test(e.which) && !/input|textarea/i.test(e.target.tagName)) {
            var i = t(this);
            if (e.preventDefault(), e.stopPropagation(), !i.is(".disabled, :disabled")) {
                var r = n(i),
                a = r.hasClass("open");
                if (!a && 27 != e.which || a && 27 == e.which) return 27 == e.which && r.find(s).trigger("focus"),
                i.trigger("click");
                var o = " li:not(.divider):visible a",
                l = r.find('[role="menu"]' + o + ', [role="listbox"]' + o);
                if (l.length) {
                    var u = l.index(e.target);
                    38 == e.which && u > 0 && u--,
                    40 == e.which && u < l.length - 1 && u++,
                    ~u || (u = 0),
                    l.eq(u).trigger("focus")
                }
            }
        }
    };
    var o = t.fn.dropdown;
    t.fn.dropdown = i,
    t.fn.dropdown.Constructor = a,
    t.fn.dropdown.noConflict = function() {
        return t.fn.dropdown = o,
        this
    },
    t(document).on("click.bs.dropdown.data-api", e).on("click.bs.dropdown.data-api", ".dropdown form",
    function(t) {
        t.stopPropagation()
    }).on("click.bs.dropdown.data-api", s, a.prototype.toggle).on("keydown.bs.dropdown.data-api", s, a.prototype.keydown).on("keydown.bs.dropdown.data-api", '[role="menu"]', a.prototype.keydown).on("keydown.bs.dropdown.data-api", '[role="listbox"]', a.prototype.keydown)
} (jQuery),
function(t, e) {
    if ("function" == typeof define && define.amd) define(["underscore", "jquery", "exports"],
    function(n, i, r) {
        t.Backbone = e(t, r, n, i)
    });
    else if ("undefined" != typeof exports) {
        var n = require("underscore");
        e(t, exports, n)
    } else t.Backbone = e(t, {},
    t._, t.jQuery || t.Zepto || t.ender || t.$)
} (this,
function(t, e, n, i) {
    {
        var r = t.Backbone,
        s = [],
        a = (s.push, s.slice);
        s.splice
    }
    e.VERSION = "1.1.2",
    e.$ = i,
    e.noConflict = function() {
        return t.Backbone = r,
        this
    },
    e.emulateHTTP = !1,
    e.emulateJSON = !1;
    var o = e.Events = {
        "on": function(t, e, n) {
            if (!u(this, "on", t, [e, n]) || !e) return this;
            this._events || (this._events = {});
            var i = this._events[t] || (this._events[t] = []);
            return i.push({
                "callback": e,
                "context": n,
                "ctx": n || this
            }),
            this
        },
        "once": function(t, e, i) {
            if (!u(this, "once", t, [e, i]) || !e) return this;
            var r = this,
            s = n.once(function() {
                r.off(t, s),
                e.apply(this, arguments)
            });
            return s._callback = e,
            this.on(t, s, i)
        },
        "off": function(t, e, i) {
            var r, s, a, o, l, c, h, d;
            if (!this._events || !u(this, "off", t, [e, i])) return this;
            if (!t && !e && !i) return this._events = void 0,
            this;
            for (o = t ? [t] : n.keys(this._events), l = 0, c = o.length; c > l; l++) if (t = o[l], a = this._events[t]) {
                if (this._events[t] = r = [], e || i) for (h = 0, d = a.length; d > h; h++) s = a[h],
                (e && e !== s.callback && e !== s.callback._callback || i && i !== s.context) && r.push(s);
                r.length || delete this._events[t]
            }
            return this
        },
        "trigger": function(t) {
            if (!this._events) return this;
            var e = a.call(arguments, 1);
            if (!u(this, "trigger", t, e)) return this;
            var n = this._events[t],
            i = this._events.all;
            return n && c(n, e),
            i && c(i, arguments),
            this
        },
        "stopListening": function(t, e, i) {
            var r = this._listeningTo;
            if (!r) return this;
            var s = !e && !i;
            i || "object" != typeof e || (i = this),
            t && ((r = {})[t._listenId] = t);
            for (var a in r) t = r[a],
            t.off(e, i, this),
            (s || n.isEmpty(t._events)) && delete this._listeningTo[a];
            return this
        }
    },
    l = /\s+/,
    u = function(t, e, n, i) {
        if (!n) return ! 0;
        if ("object" == typeof n) {
            for (var r in n) t[e].apply(t, [r, n[r]].concat(i));
            return ! 1
        }
        if (l.test(n)) {
            for (var s = n.split(l), a = 0, o = s.length; o > a; a++) t[e].apply(t, [s[a]].concat(i));
            return ! 1
        }
        return ! 0
    },
    c = function(t, e) {
        var n, i = -1,
        r = t.length,
        s = e[0],
        a = e[1],
        o = e[2];
        switch (e.length) {
        case 0:
            for (; ++i < r;)(n = t[i]).callback.call(n.ctx);
            return;
        case 1:
            for (; ++i < r;)(n = t[i]).callback.call(n.ctx, s);
            return;
        case 2:
            for (; ++i < r;)(n = t[i]).callback.call(n.ctx, s, a);
            return;
        case 3:
            for (; ++i < r;)(n = t[i]).callback.call(n.ctx, s, a, o);
            return;
        default:
            for (; ++i < r;)(n = t[i]).callback.apply(n.ctx, e);
            return
        }
    },
    h = {
        "listenTo": "on",
        "listenToOnce": "once"
    };
    n.each(h,
    function(t, e) {
        o[e] = function(e, i, r) {
            var s = this._listeningTo || (this._listeningTo = {}),
            a = e._listenId || (e._listenId = n.uniqueId("l"));
            return s[a] = e,
            r || "object" != typeof i || (r = this),
            e[t](i, r, this),
            this
        }
    }),
    o.bind = o.on,
    o.unbind = o.off,
    n.extend(e, o);
    var d = e.Model = function(t, e) {
        var i = t || {};
        e || (e = {}),
        this.cid = n.uniqueId("c"),
        this.attributes = {},
        e.collection && (this.collection = e.collection),
        e.parse && (i = this.parse(i, e) || {}),
        i = n.defaults({},
        i, n.result(this, "defaults")),
        this.set(i, e),
        this.changed = {},
        this.initialize.apply(this, arguments)
    };
    n.extend(d.prototype, o, {
        "changed": null,
        "validationError": null,
        "idAttribute": "id",
        "initialize": function() {},
        "toJSON": function() {
            return n.clone(this.attributes)
        },
        "sync": function() {
            return e.sync.apply(this, arguments)
        },
        "get": function(t) {
            return this.attributes[t]
        },
        "escape": function(t) {
            return n.escape(this.get(t))
        },
        "has": function(t) {
            return null != this.get(t)
        },
        "set": function(t, e, i) {
            var r, s, a, o, l, u, c, h;
            if (null == t) return this;
            if ("object" == typeof t ? (s = t, i = e) : (s = {})[t] = e, i || (i = {}), !this._validate(s, i)) return ! 1;
            a = i.unset,
            l = i.silent,
            o = [],
            u = this._changing,
            this._changing = !0,
            u || (this._previousAttributes = n.clone(this.attributes), this.changed = {}),
            h = this.attributes,
            c = this._previousAttributes,
            this.idAttribute in s && (this.id = s[this.idAttribute]);
            for (r in s) e = s[r],
            n.isEqual(h[r], e) || o.push(r),
            n.isEqual(c[r], e) ? delete this.changed[r] : this.changed[r] = e,
            a ? delete h[r] : h[r] = e;
            if (!l) {
                o.length && (this._pending = i);
                for (var d = 0,
                f = o.length; f > d; d++) this.trigger("change:" + o[d], this, h[o[d]], i)
            }
            if (u) return this;
            if (!l) for (; this._pending;) i = this._pending,
            this._pending = !1,
            this.trigger("change", this, i);
            return this._pending = !1,
            this._changing = !1,
            this
        },
        "unset": function(t, e) {
            return this.set(t, void 0, n.extend({},
            e, {
                "unset": !0
            }))
        },
        "clear": function(t) {
            var e = {};
            for (var i in this.attributes) e[i] = void 0;
            return this.set(e, n.extend({},
            t, {
                "unset": !0
            }))
        },
        "hasChanged": function(t) {
            return null == t ? !n.isEmpty(this.changed) : n.has(this.changed, t)
        },
        "changedAttributes": function(t) {
            if (!t) return this.hasChanged() ? n.clone(this.changed) : !1;
            var e, i = !1,
            r = this._changing ? this._previousAttributes: this.attributes;
            for (var s in t) n.isEqual(r[s], e = t[s]) || ((i || (i = {}))[s] = e);
            return i
        },
        "previous": function(t) {
            return null != t && this._previousAttributes ? this._previousAttributes[t] : null
        },
        "previousAttributes": function() {
            return n.clone(this._previousAttributes)
        },
        "fetch": function(t) {
            t = t ? n.clone(t) : {},
            void 0 === t.parse && (t.parse = !0);
            var e = this,
            i = t.success;
            return t.success = function(n) {
                return e.set(e.parse(n, t), t) ? (i && i(e, n, t), void e.trigger("sync", e, n, t)) : !1
            },
            I(this, t),
            this.sync("read", this, t)
        },
        "save": function(t, e, i) {
            var r, s, a, o = this.attributes;
            if (null == t || "object" == typeof t ? (r = t, i = e) : (r = {})[t] = e, i = n.extend({
                "validate": !0
            },
            i), r && !i.wait) {
                if (!this.set(r, i)) return ! 1
            } else if (!this._validate(r, i)) return ! 1;
            r && i.wait && (this.attributes = n.extend({},
            o, r)),
            void 0 === i.parse && (i.parse = !0);
            var l = this,
            u = i.success;
            return i.success = function(t) {
                l.attributes = o;
                var e = l.parse(t, i);
                return i.wait && (e = n.extend(r || {},
                e)),
                n.isObject(e) && !l.set(e, i) ? !1 : (u && u(l, t, i), void l.trigger("sync", l, t, i))
            },
            I(this, i),
            s = this.isNew() ? "create": i.patch ? "patch": "update",
            "patch" === s && (i.attrs = r),
            a = this.sync(s, this, i),
            r && i.wait && (this.attributes = o),
            a
        },
        "destroy": function(t) {
            t = t ? n.clone(t) : {};
            var e = this,
            i = t.success,
            r = function() {
                e.trigger("destroy", e, e.collection, t)
            };
            if (t.success = function(n) { (t.wait || e.isNew()) && r(),
                i && i(e, n, t),
                e.isNew() || e.trigger("sync", e, n, t)
            },
            this.isNew()) return t.success(),
            !1;
            I(this, t);
            var s = this.sync("delete", this, t);
            return t.wait || r(),
            s
        },
        "url": function() {
            var t = n.result(this, "urlRoot") || n.result(this.collection, "url") || P();
            return this.isNew() ? t: t.replace(/([^\/])$/, "$1/") + encodeURIComponent(this.id)
        },
        "parse": function(t) {
            return t
        },
        "clone": function() {
            return new this.constructor(this.attributes)
        },
        "isNew": function() {
            return ! this.has(this.idAttribute)
        },
        "isValid": function(t) {
            return this._validate({},
            n.extend(t || {},
            {
                "validate": !0
            }))
        },
        "_validate": function(t, e) {
            if (!e.validate || !this.validate) return ! 0;
            t = n.extend({},
            this.attributes, t);
            var i = this.validationError = this.validate(t, e) || null;
            return i ? (this.trigger("invalid", this, i, n.extend(e, {
                "validationError": i
            })), !1) : !0
        }
    });
    var f = ["keys", "values", "pairs", "invert", "pick", "omit"];
    n.each(f,
    function(t) {
        d.prototype[t] = function() {
            var e = a.call(arguments);
            return e.unshift(this.attributes),
            n[t].apply(n, e)
        }
    });
    var p = e.Collection = function(t, e) {
        e || (e = {}),
        e.model && (this.model = e.model),
        void 0 !== e.comparator && (this.comparator = e.comparator),
        this._reset(),
        this.initialize.apply(this, arguments),
        t && this.reset(t, n.extend({
            "silent": !0
        },
        e))
    },
    m = {
        "add": !0,
        "remove": !0,
        "merge": !0
    },
    g = {
        "add": !0,
        "remove": !1
    };
    n.extend(p.prototype, o, {
        "model": d,
        "initialize": function() {},
        "toJSON": function(t) {
            return this.map(function(e) {
                return e.toJSON(t)
            })
        },
        "sync": function() {
            return e.sync.apply(this, arguments)
        },
        "add": function(t, e) {
            return this.set(t, n.extend({
                "merge": !1
            },
            e, g))
        },
        "remove": function(t, e) {
            var i = !n.isArray(t);
            t = i ? [t] : n.clone(t),
            e || (e = {});
            var r, s, a, o;
            for (r = 0, s = t.length; s > r; r++) o = t[r] = this.get(t[r]),
            o && (delete this._byId[o.id], delete this._byId[o.cid], a = this.indexOf(o), this.models.splice(a, 1), this.length--, e.silent || (e.index = a, o.trigger("remove", o, this, e)), this._removeReference(o, e));
            return i ? t[0] : t
        },
        "set": function(t, e) {
            e = n.defaults({},
            e, m),
            e.parse && (t = this.parse(t, e));
            var i = !n.isArray(t);
            t = i ? t ? [t] : [] : n.clone(t);
            var r, s, a, o, l, u, c, h = e.at,
            f = this.model,
            p = this.comparator && null == h && e.sort !== !1,
            g = n.isString(this.comparator) ? this.comparator: null,
            v = [],
            y = [],
            b = {},
            w = e.add,
            $ = e.merge,
            x = e.remove,
            C = !p && w && x ? [] : !1;
            for (r = 0, s = t.length; s > r; r++) {
                if (l = t[r] || {},
                a = l instanceof d ? o = l: l[f.prototype.idAttribute || "id"], u = this.get(a)) x && (b[u.cid] = !0),
                $ && (l = l === o ? o.attributes: l, e.parse && (l = u.parse(l, e)), u.set(l, e), p && !c && u.hasChanged(g) && (c = !0)),
                t[r] = u;
                else if (w) {
                    if (o = t[r] = this._prepareModel(l, e), !o) continue;
                    v.push(o),
                    this._addReference(o, e)
                }
                o = u || o,
                !C || !o.isNew() && b[o.id] || C.push(o),
                b[o.id] = !0
            }
            if (x) {
                for (r = 0, s = this.length; s > r; ++r) b[(o = this.models[r]).cid] || y.push(o);
                y.length && this.remove(y, e)
            }
            if (v.length || C && C.length) if (p && (c = !0), this.length += v.length, null != h) for (r = 0, s = v.length; s > r; r++) this.models.splice(h + r, 0, v[r]);
            else {
                C && (this.models.length = 0);
                var k = C || v;
                for (r = 0, s = k.length; s > r; r++) this.models.push(k[r])
            }
            if (c && this.sort({
                "silent": !0
            }), !e.silent) {
                for (r = 0, s = v.length; s > r; r++)(o = v[r]).trigger("add", o, this, e); (c || C && C.length) && this.trigger("sort", this, e)
            }
            return i ? t[0] : t
        },
        "reset": function(t, e) {
            e || (e = {});
            for (var i = 0,
            r = this.models.length; r > i; i++) this._removeReference(this.models[i], e);
            return e.previousModels = this.models,
            this._reset(),
            t = this.add(t, n.extend({
                "silent": !0
            },
            e)),
            e.silent || this.trigger("reset", this, e),
            t
        },
        "push": function(t, e) {
            return this.add(t, n.extend({
                "at": this.length
            },
            e))
        },
        "pop": function(t) {
            var e = this.at(this.length - 1);
            return this.remove(e, t),
            e
        },
        "unshift": function(t, e) {
            return this.add(t, n.extend({
                "at": 0
            },
            e))
        },
        "shift": function(t) {
            var e = this.at(0);
            return this.remove(e, t),
            e
        },
        "slice": function() {
            return a.apply(this.models, arguments)
        },
        "get": function(t) {
            return null == t ? void 0 : this._byId[t] || this._byId[t.id] || this._byId[t.cid]
        },
        "at": function(t) {
            return this.models[t]
        },
        "where": function(t, e) {
            return n.isEmpty(t) ? e ? void 0 : [] : this[e ? "find": "filter"](function(e) {
                for (var n in t) if (t[n] !== e.get(n)) return ! 1;
                return ! 0
            })
        },
        "findWhere": function(t) {
            return this.where(t, !0)
        },
        "sort": function(t) {
            if (!this.comparator) throw new Error("Cannot sort a set without a comparator");
            return t || (t = {}),
            n.isString(this.comparator) || 1 === this.comparator.length ? this.models = this.sortBy(this.comparator, this) : this.models.sort(n.bind(this.comparator, this)),
            t.silent || this.trigger("sort", this, t),
            this
        },
        "pluck": function(t) {
            return n.invoke(this.models, "get", t)
        },
        "fetch": function(t) {
            t = t ? n.clone(t) : {},
            void 0 === t.parse && (t.parse = !0);
            var e = t.success,
            i = this;
            return t.success = function(n) {
                var r = t.reset ? "reset": "set";
                i[r](n, t),
                e && e(i, n, t),
                i.trigger("sync", i, n, t)
            },
            I(this, t),
            this.sync("read", this, t)
        },
        "create": function(t, e) {
            if (e = e ? n.clone(e) : {},
            !(t = this._prepareModel(t, e))) return ! 1;
            e.wait || this.add(t, e);
            var i = this,
            r = e.success;
            return e.success = function(t, n) {
                e.wait && i.add(t, e),
                r && r(t, n, e)
            },
            t.save(null, e),
            t
        },
        "parse": function(t) {
            return t
        },
        "clone": function() {
            return new this.constructor(this.models)
        },
        "_reset": function() {
            this.length = 0,
            this.models = [],
            this._byId = {}
        },
        "_prepareModel": function(t, e) {
            if (t instanceof d) return t;
            e = e ? n.clone(e) : {},
            e.collection = this;
            var i = new this.model(t, e);
            return i.validationError ? (this.trigger("invalid", this, i.validationError, e), !1) : i
        },
        "_addReference": function(t) {
            this._byId[t.cid] = t,
            null != t.id && (this._byId[t.id] = t),
            t.collection || (t.collection = this),
            t.on("all", this._onModelEvent, this)
        },
        "_removeReference": function(t) {
            this === t.collection && delete t.collection,
            t.off("all", this._onModelEvent, this)
        },
        "_onModelEvent": function(t, e, n, i) { ("add" !== t && "remove" !== t || n === this) && ("destroy" === t && this.remove(e, i), e && t === "change:" + e.idAttribute && (delete this._byId[e.previous(e.idAttribute)], null != e.id && (this._byId[e.id] = e)), this.trigger.apply(this, arguments))
        }
    });
    var v = ["forEach", "each", "map", "collect", "reduce", "foldl", "inject", "reduceRight", "foldr", "find", "detect", "filter", "select", "reject", "every", "all", "some", "any", "include", "contains", "invoke", "max", "min", "toArray", "size", "first", "head", "take", "initial", "rest", "tail", "drop", "last", "without", "difference", "indexOf", "shuffle", "lastIndexOf", "isEmpty", "chain", "sample"];
    n.each(v,
    function(t) {
        p.prototype[t] = function() {
            var e = a.call(arguments);
            return e.unshift(this.models),
            n[t].apply(n, e)
        }
    });
    var y = ["groupBy", "countBy", "sortBy", "indexBy"];
    n.each(y,
    function(t) {
        p.prototype[t] = function(e, i) {
            var r = n.isFunction(e) ? e: function(t) {
                return t.get(e)
            };
            return n[t](this.models, r, i)
        }
    });
    var b = e.View = function(t) {
        this.cid = n.uniqueId("view"),
        t || (t = {}),
        n.extend(this, n.pick(t, $)),
        this._ensureElement(),
        this.initialize.apply(this, arguments),
        this.delegateEvents()
    },
    w = /^(\S+)\s*(.*)$/,
    $ = ["model", "collection", "el", "id", "attributes", "className", "tagName", "events"];
    n.extend(b.prototype, o, {
        "tagName": "div",
        "$": function(t) {
            return this.$el.find(t)
        },
        "initialize": function() {},
        "render": function() {
            return this
        },
        "remove": function() {
            return this.$el.remove(),
            this.stopListening(),
            this
        },
        "setElement": function(t, n) {
            return this.$el && this.undelegateEvents(),
            this.$el = t instanceof e.$ ? t: e.$(t),
            this.el = this.$el[0],
            n !== !1 && this.delegateEvents(),
            this
        },
        "delegateEvents": function(t) {
            if (!t && !(t = n.result(this, "events"))) return this;
            this.undelegateEvents();
            for (var e in t) {
                var i = t[e];
                if (n.isFunction(i) || (i = this[t[e]]), i) {
                    var r = e.match(w),
                    s = r[1],
                    a = r[2];
                    i = n.bind(i, this),
                    s += ".delegateEvents" + this.cid,
                    "" === a ? this.$el.on(s, i) : this.$el.on(s, a, i)
                }
            }
            return this
        },
        "undelegateEvents": function() {
            return this.$el.off(".delegateEvents" + this.cid),
            this
        },
        "_ensureElement": function() {
            if (this.el) this.setElement(n.result(this, "el"), !1);
            else {
                var t = n.extend({},
                n.result(this, "attributes"));
                this.id && (t.id = n.result(this, "id")),
                this.className && (t["class"] = n.result(this, "className"));
                var i = e.$("<" + n.result(this, "tagName") + ">").attr(t);
                this.setElement(i, !1)
            }
        }
    }),
    e.sync = function(t, i, r) {
        var s = C[t];
        n.defaults(r || (r = {}), {
            "emulateHTTP": e.emulateHTTP,
            "emulateJSON": e.emulateJSON
        });
        var a = {
            "type": s,
            "dataType": "json"
        };
        if (r.url || (a.url = n.result(i, "url") || P()), null != r.data || !i || "create" !== t && "update" !== t && "patch" !== t || (a.contentType = "application/json", a.data = JSON.stringify(r.attrs || i.toJSON(r))), r.emulateJSON && (a.contentType = "application/x-www-form-urlencoded", a.data = a.data ? {
            "model": a.data
        }: {}), r.emulateHTTP && ("PUT" === s || "DELETE" === s || "PATCH" === s)) {
            a.type = "POST",
            r.emulateJSON && (a.data._method = s);
            var o = r.beforeSend;
            r.beforeSend = function(t) {
                return t.setRequestHeader("X-HTTP-Method-Override", s),
                o ? o.apply(this, arguments) : void 0
            }
        }
        "GET" === a.type || r.emulateJSON || (a.processData = !1),
        "PATCH" === a.type && x && (a.xhr = function() {
            return new ActiveXObject("Microsoft.XMLHTTP")
        });
        var l = r.xhr = e.ajax(n.extend(a, r));
        return i.trigger("request", i, l, r),
        l
    };
    var x = !("undefined" == typeof window || !window.ActiveXObject || window.XMLHttpRequest && (new XMLHttpRequest).dispatchEvent),
    C = {
        "create": "POST",
        "update": "PUT",
        "patch": "PATCH",
        "delete": "DELETE",
        "read": "GET"
    };
    e.ajax = function() {
        return e.$.ajax.apply(e.$, arguments)
    };
    var k = e.Router = function(t) {
        t || (t = {}),
        t.routes && (this.routes = t.routes),
        this._bindRoutes(),
        this.initialize.apply(this, arguments)
    },
    T = /\((.*?)\)/g,
    S = /(\(\?)?:\w+/g,
    D = /\*\w+/g,
    E = /[\-{}\[\]+?.,\\\^$|#\s]/g;
    n.extend(k.prototype, o, {
        "initialize": function() {},
        "route": function(t, i, r) {
            n.isRegExp(t) || (t = this._routeToRegExp(t)),
            n.isFunction(i) && (r = i, i = ""),
            r || (r = this[i]);
            var s = this;
            return e.history.route(t,
            function(n) {
                var a = s._extractParameters(t, n);
                s.execute(r, a),
                s.trigger.apply(s, ["route:" + i].concat(a)),
                s.trigger("route", i, a),
                e.history.trigger("route", s, i, a)
            }),
            this
        },
        "execute": function(t, e) {
            t && t.apply(this, e)
        },
        "navigate": function(t, n) {
            return e.history.navigate(t, n),
            this
        },
        "_bindRoutes": function() {
            if (this.routes) {
                this.routes = n.result(this, "routes");
                for (var t, e = n.keys(this.routes); null != (t = e.pop());) this.route(t, this.routes[t])
            }
        },
        "_routeToRegExp": function(t) {
            return t = t.replace(E, "\\$&").replace(T, "(?:$1)?").replace(S,
            function(t, e) {
                return e ? t: "([^/?]+)"
            }).replace(D, "([^?]*?)"),
            new RegExp("^" + t + "(?:\\?([\\s\\S]*))?$")
        },
        "_extractParameters": function(t, e) {
            var i = t.exec(e).slice(1);
            return n.map(i,
            function(t, e) {
                return e === i.length - 1 ? t || null: t ? decodeURIComponent(t) : null
            })
        }
    });
    var _ = e.History = function() {
        this.handlers = [],
        n.bindAll(this, "checkUrl"),
        "undefined" != typeof window && (this.location = window.location, this.history = window.history)
    },
    M = /^[#\/]|\s+$/g,
    A = /^\/+|\/+$/g,
    O = /msie [\w.]+/,
    F = /\/$/,
    N = /#.*$/;
    _.started = !1,
    n.extend(_.prototype, o, {
        "interval": 50,
        "atRoot": function() {
            return this.location.pathname.replace(/[^\/]$/, "$&/") === this.root
        },
        "getHash": function(t) {
            var e = (t || this).location.href.match(/#(.*)$/);
            return e ? e[1] : ""
        },
        "getFragment": function(t, e) {
            if (null == t) if (this._hasPushState || !this._wantsHashChange || e) {
                t = decodeURI(this.location.pathname + this.location.search);
                var n = this.root.replace(F, "");
                t.indexOf(n) || (t = t.slice(n.length))
            } else t = this.getHash();
            return t.replace(M, "")
        },
        "start": function(t) {
            if (_.started) throw new Error("Backbone.history has already been started");
            _.started = !0,
            this.options = n.extend({
                "root": "/"
            },
            this.options, t),
            this.root = this.options.root,
            this._wantsHashChange = this.options.hashChange !== !1,
            this._wantsPushState = !!this.options.pushState,
            this._hasPushState = !!(this.options.pushState && this.history && this.history.pushState);
            var i = this.getFragment(),
            r = document.documentMode,
            s = O.exec(navigator.userAgent.toLowerCase()) && (!r || 7 >= r);
            if (this.root = ("/" + this.root + "/").replace(A, "/"), s && this._wantsHashChange) {
                var a = e.$('<iframe src="javascript:0" tabindex="-1">');
                this.iframe = a.hide().appendTo("body")[0].contentWindow,
                this.navigate(i)
            }
            this._hasPushState ? e.$(window).on("popstate", this.checkUrl) : this._wantsHashChange && "onhashchange" in window && !s ? e.$(window).on("hashchange", this.checkUrl) : this._wantsHashChange && (this._checkUrlInterval = setInterval(this.checkUrl, this.interval)),
            this.fragment = i;
            var o = this.location;
            if (this._wantsHashChange && this._wantsPushState) {
                if (!this._hasPushState && !this.atRoot()) return this.fragment = this.getFragment(null, !0),
                this.location.replace(this.root + "#" + this.fragment),
                !0;
                this._hasPushState && this.atRoot() && o.hash && (this.fragment = this.getHash().replace(M, ""), this.history.replaceState({},
                document.title, this.root + this.fragment))
            }
            return this.options.silent ? void 0 : this.loadUrl()
        },
        "stop": function() {
            e.$(window).off("popstate", this.checkUrl).off("hashchange", this.checkUrl),
            this._checkUrlInterval && clearInterval(this._checkUrlInterval),
            _.started = !1
        },
        "route": function(t, e) {
            this.handlers.unshift({
                "route": t,
                "callback": e
            })
        },
        "checkUrl": function() {
            var t = this.getFragment();
            return t === this.fragment && this.iframe && (t = this.getFragment(this.getHash(this.iframe))),
            t === this.fragment ? !1 : (this.iframe && this.navigate(t), void this.loadUrl())
        },
        "loadUrl": function(t) {
            return t = this.fragment = this.getFragment(t),
            n.any(this.handlers,
            function(e) {
                return e.route.test(t) ? (e.callback(t), !0) : void 0
            })
        },
        "navigate": function(t, e) {
            if (!_.started) return ! 1;
            e && e !== !0 || (e = {
                "trigger": !!e
            });
            var n = this.root + (t = this.getFragment(t || ""));
            if (t = t.replace(N, ""), this.fragment !== t) {
                if (this.fragment = t, "" === t && "/" !== n && (n = n.slice(0, -1)), this._hasPushState) this.history[e.replace ? "replaceState": "pushState"]({},
                document.title, n);
                else {
                    if (!this._wantsHashChange) return this.location.assign(n);
                    this._updateHash(this.location, t, e.replace),
                    this.iframe && t !== this.getFragment(this.getHash(this.iframe)) && (e.replace || this.iframe.document.open().close(), this._updateHash(this.iframe.location, t, e.replace))
                }
                return e.trigger ? this.loadUrl(t) : void 0
            }
        },
        "_updateHash": function(t, e, n) {
            if (n) {
                var i = t.href.replace(/(javascript:|#).*$/, "");
                t.replace(i + "#" + e)
            } else t.hash = "#" + e
        }
    }),
    e.history = new _;
    var j = function(t, e) {
        var i, r = this;
        i = t && n.has(t, "constructor") ? t.constructor: function() {
            return r.apply(this, arguments)
        },
        n.extend(i, r, e);
        var s = function() {
            this.constructor = i
        };
        return s.prototype = r.prototype,
        i.prototype = new s,
        t && n.extend(i.prototype, t),
        i.__super__ = r.prototype,
        i
    };
    d.extend = p.extend = k.extend = b.extend = _.extend = j;
    var P = function() {
        throw new Error('A "url" property or function must be specified')
    },
    I = function(t, e) {
        var n = e.error;
        e.error = function(i) {
            n && n(t, i, e),
            t.trigger("error", t, i, e)
        }
    };
    return e
}),
function(t) {
    function e() {
        var t = document.createElement("input"),
        e = "onpaste";
        return t.setAttribute(e, ""),
        "function" == typeof t[e] ? "paste": "input"
    }
    var n, i = e() + ".mask",
    r = navigator.userAgent,
    s = /iphone/i.test(r),
    a = /android/i.test(r);
    t.mask = {
        "definitions": {
            "9": "[0-9]",
            "a": "[A-Za-z]",
            "*": "[A-Za-z0-9]"
        },
        "dataName": "rawMaskFn",
        "placeholder": "_"
    },
    t.fn.extend({
        "caret": function(t, e) {
            var n;
            return 0 === this.length || this.is(":hidden") ? void 0 : "number" == typeof t ? (e = "number" == typeof e ? e: t, this.each(function() {
                this.setSelectionRange ? this.setSelectionRange(t, e) : this.createTextRange && (n = this.createTextRange(), n.collapse(!0), n.moveEnd("character", e), n.moveStart("character", t), n.select())
            })) : (this[0].setSelectionRange ? (t = this[0].selectionStart, e = this[0].selectionEnd) : document.selection && document.selection.createRange && (n = document.selection.createRange(), t = 0 - n.duplicate().moveStart("character", -1e5), e = t + n.text.length), {
                "begin": t,
                "end": e
            })
        },
        "unmask": function() {
            return this.trigger("unmask")
        },
        "mask": function(e, r) {
            var o, l, u, c, h, d;
            return ! e && this.length > 0 ? (o = t(this[0]), o.data(t.mask.dataName)()) : (r = t.extend({
                "placeholder": t.mask.placeholder,
                "completed": null
            },
            r), l = t.mask.definitions, u = [], c = d = e.length, h = null, t.each(e.split(""),
            function(t, e) {
                "?" == e ? (d--, c = t) : l[e] ? (u.push(RegExp(l[e])), null === h && (h = u.length - 1)) : u.push(null)
            }), this.trigger("unmask").each(function() {
                function o(t) {
                    for (; d > ++t && !u[t];);
                    return t
                }
                function f(t) {
                    for (; --t >= 0 && !u[t];);
                    return t
                }
                function p(t, e) {
                    var n, i;
                    if (! (0 > t)) {
                        for (n = t, i = o(e); d > n; n++) if (u[n]) {
                            if (! (d > i && u[n].test(x[i]))) break;
                            x[n] = x[i],
                            x[i] = r.placeholder,
                            i = o(i)
                        }
                        b(),
                        $.caret(Math.max(h, t))
                    }
                }
                function m(t) {
                    var e, n, i, s;
                    for (e = t, n = r.placeholder; d > e; e++) if (u[e]) {
                        if (i = o(e), s = x[e], x[e] = n, !(d > i && u[i].test(s))) break;
                        n = s
                    }
                }
                function g(t) {
                    var e, n, i, r = t.which;
                    8 === r || 46 === r || s && 127 === r ? (e = $.caret(), n = e.begin, i = e.end, 0 === i - n && (n = 46 !== r ? f(n) : i = o(n - 1), i = 46 === r ? o(i) : i), y(n, i), p(n, i - 1), t.preventDefault()) : 27 == r && ($.val(C), $.caret(0, w()), t.preventDefault())
                }
                function v(e) {
                    var n, i, s, l = e.which,
                    c = $.caret();
                    e.ctrlKey || e.altKey || e.metaKey || 32 > l || l && (0 !== c.end - c.begin && (y(c.begin, c.end), p(c.begin, c.end - 1)), n = o(c.begin - 1), d > n && (i = String.fromCharCode(l), u[n].test(i) && (m(n), x[n] = i, b(), s = o(n), a ? setTimeout(t.proxy(t.fn.caret, $, s), 0) : $.caret(s), r.completed && s >= d && r.completed.call($))), e.preventDefault())
                }
                function y(t, e) {
                    var n;
                    for (n = t; e > n && d > n; n++) u[n] && (x[n] = r.placeholder)
                }
                function b() {
                    $.val(x.join(""))
                }
                function w(t) {
                    var e, n, i = $.val(),
                    s = -1;
                    for (e = 0, pos = 0; d > e; e++) if (u[e]) {
                        for (x[e] = r.placeholder; pos++<i.length;) if (n = i.charAt(pos - 1), u[e].test(n)) {
                            x[e] = n,
                            s = e;
                            break
                        }
                        if (pos > i.length) break
                    } else x[e] === i.charAt(pos) && e !== c && (pos++, s = e);
                    return t ? b() : c > s + 1 ? ($.val(""), y(0, d)) : (b(), $.val($.val().substring(0, s + 1))),
                    c ? e: h
                }
                var $ = t(this),
                x = t.map(e.split(""),
                function(t) {
                    return "?" != t ? l[t] ? r.placeholder: t: void 0
                }),
                C = $.val();
                $.data(t.mask.dataName,
                function() {
                    return t.map(x,
                    function(t, e) {
                        return u[e] && t != r.placeholder ? t: null
                    }).join("")
                }),
                $.attr("readonly") || $.one("unmask",
                function() {
                    $.unbind(".mask").removeData(t.mask.dataName)
                }).bind("focus.mask",
                function() {
                    clearTimeout(n);
                    var t;
                    C = $.val(),
                    t = w(),
                    n = setTimeout(function() {
                        b(),
                        t == e.length ? $.caret(0, t) : $.caret(t)
                    },
                    10)
                }).bind("blur.mask",
                function() {
                    w(),
                    $.val() != C && $.change()
                }).bind("keydown.mask", g).bind("keypress.mask", v).bind(i,
                function() {
                    setTimeout(function() {
                        var t = w(!0);
                        $.caret(t),
                        r.completed && t == $.val().length && r.completed.call($)
                    },
                    0)
                }),
                w()
            }))
        }
    })
} (jQuery),
function(t, e, n) {
    function i(t) {
        var e = {},
        i = /^jQuery\d+$/;
        return n.each(t.attributes,
        function(t, n) {
            n.specified && !i.test(n.name) && (e[n.name] = n.value)
        }),
        e
    }
    function r(t, e) {
        var i = this,
        r = n(i);
        if (i.value == r.attr("placeholder") && r.hasClass("placeholder")) if (r.data("placeholder-password")) {
            if (r = r.hide().next().show().attr("id", r.removeAttr("id").data("placeholder-id")), t === !0) return r[0].value = e;
            r.focus()
        } else i.value = "",
        r.removeClass("placeholder"),
        i == a() && i.select()
    }
    function s() {
        var t, e = this,
        s = n(e),
        a = this.id;
        if ("" == e.value) {
            if ("password" == e.type) {
                if (!s.data("placeholder-textinput")) {
                    try {
                        t = s.clone().attr({
                            "type": "text"
                        })
                    } catch(o) {
                        t = n("<input>").attr(n.extend(i(this), {
                            "type": "text"
                        }))
                    }
                    t.removeAttr("name").data({
                        "placeholder-password": s,
                        "placeholder-id": a
                    }).bind("focus.placeholder", r),
                    s.data({
                        "placeholder-textinput": t,
                        "placeholder-id": a
                    }).before(t)
                }
                s = s.removeAttr("id").hide().prev().attr("id", a).show()
            }
            s.addClass("placeholder"),
            s[0].value = s.attr("placeholder")
        } else s.removeClass("placeholder")
    }
    function a() {
        try {
            return e.activeElement
        } catch(t) {}
    }
    var o, l, u = "[object OperaMini]" == Object.prototype.toString.call(t.operamini),
    c = "placeholder" in e.createElement("input") && !u,
    h = "placeholder" in e.createElement("textarea") && !u,
    d = n.fn,
    f = n.valHooks,
    p = n.propHooks;
    c && h ? (l = d.placeholder = function() {
        return this
    },
    l.input = l.textarea = !0) : (l = d.placeholder = function() {
        var t = this;
        return t.filter((c ? "textarea": ":input") + "[placeholder]").not(".placeholder").bind({
            "focus.placeholder": r,
            "blur.placeholder": s
        }).data("placeholder-enabled", !0).trigger("blur.placeholder"),
        t
    },
    l.input = c, l.textarea = h, o = {
        "get": function(t) {
            var e = n(t),
            i = e.data("placeholder-password");
            return i ? i[0].value: e.data("placeholder-enabled") && e.hasClass("placeholder") ? "": t.value
        },
        "set": function(t, e) {
            var i = n(t),
            o = i.data("placeholder-password");
            return o ? o[0].value = e: i.data("placeholder-enabled") ? ("" == e ? (t.value = e, t != a() && s.call(t)) : i.hasClass("placeholder") ? r.call(t, !0, e) || (t.value = e) : t.value = e, i) : t.value = e
        }
    },
    c || (f.input = o, p.value = o), h || (f.textarea = o, p.value = o), n(function() {
        n(e).delegate("form", "submit.placeholder",
        function() {
            var t = n(".placeholder", this).each(r);
            setTimeout(function() {
                t.each(s)
            },
            10)
        })
    }), n(t).bind("beforeunload.placeholder",
    function() {
        n(".placeholder").each(function() {
            this.value = ""
        })
    }))
} (this, document, jQuery),
function(t) {
    "function" == typeof define && define.amd ? define(["jquery"], t) : t(jQuery)
} (function(t) {
    t.extend(t.fn, {
        "validate": function(e) {
            if (!this.length) return void(e && e.debug && window.console);
            var n = t.data(this[0], "validator");
            return n ? n: (this.attr("novalidate", "novalidate"), n = new t.validator(e, this[0]), t.data(this[0], "validator", n), n.settings.onsubmit && (this.validateDelegate(":submit", "click",
            function(e) {
                n.settings.submitHandler && (n.submitButton = e.target),
                t(e.target).hasClass("cancel") && (n.cancelSubmit = !0),
                void 0 !== t(e.target).attr("formnovalidate") && (n.cancelSubmit = !0)
            }), this.submit(function(e) {
                function i() {
                    var i;
                    return n.settings.submitHandler ? (n.submitButton && (i = t("<input type='hidden'/>").attr("name", n.submitButton.name).val(t(n.submitButton).val()).appendTo(n.currentForm)), n.settings.submitHandler.call(n, n.currentForm, e), n.submitButton && i.remove(), !1) : !0
                }
                return n.settings.debug && e.preventDefault(),
                n.cancelSubmit ? (n.cancelSubmit = !1, i()) : n.form() ? n.pendingRequest ? (n.formSubmitted = !0, !1) : i() : (n.focusInvalid(), !1)
            })), n)
        },
        "valid": function() {
            var e, n;
            return t(this[0]).is("form") ? e = this.validate().form() : (e = !0, n = t(this[0].form).validate(), this.each(function() {
                e = n.element(this) && e
            })),
            e
        },
        "removeAttrs": function(e) {
            var n = {},
            i = this;
            return t.each(e.split(/\s/),
            function(t, e) {
                n[e] = i.attr(e),
                i.removeAttr(e)
            }),
            n
        },
        "rules": function(e, n) {
            var i, r, s, a, o, l, u = this[0];
            if (e) switch (i = t.data(u.form, "validator").settings, r = i.rules, s = t.validator.staticRules(u), e) {
            case "add":
                t.extend(s, t.validator.normalizeRule(n)),
                delete s.messages,
                r[u.name] = s,
                n.messages && (i.messages[u.name] = t.extend(i.messages[u.name], n.messages));
                break;
            case "remove":
                return n ? (l = {},
                t.each(n.split(/\s/),
                function(e, n) {
                    l[n] = s[n],
                    delete s[n],
                    "required" === n && t(u).removeAttr("aria-required")
                }), l) : (delete r[u.name], s)
            }
            return a = t.validator.normalizeRules(t.extend({},
            t.validator.classRules(u), t.validator.attributeRules(u), t.validator.dataRules(u), t.validator.staticRules(u)), u),
            a.required && (o = a.required, delete a.required, a = t.extend({
                "required": o
            },
            a), t(u).attr("aria-required", "true")),
            a.remote && (o = a.remote, delete a.remote, a = t.extend(a, {
                "remote": o
            })),
            a
        }
    }),
    t.extend(t.expr[":"], {
        "blank": function(e) {
            return ! t.trim("" + t(e).val())
        },
        "filled": function(e) {
            return !! t.trim("" + t(e).val())
        },
        "unchecked": function(e) {
            return ! t(e).prop("checked")
        }
    }),
    t.validator = function(e, n) {
        this.settings = t.extend(!0, {},
        t.validator.defaults, e),
        this.currentForm = n,
        this.init()
    },
    t.validator.format = function(e, n) {
        return 1 === arguments.length ?
        function() {
            var n = t.makeArray(arguments);
            return n.unshift(e),
            t.validator.format.apply(this, n)
        }: (arguments.length > 2 && n.constructor !== Array && (n = t.makeArray(arguments).slice(1)), n.constructor !== Array && (n = [n]), t.each(n,
        function(t, n) {
            e = e.replace(new RegExp("\\{" + t + "\\}", "g"),
            function() {
                return n
            })
        }), e)
    },
    t.extend(t.validator, {
        "defaults": {
            "messages": {},
            "groups": {},
            "rules": {},
            "errorClass": "error",
            "validClass": "valid",
            "errorElement": "label",
            "focusInvalid": !0,
            "errorContainer": t([]),
            "errorLabelContainer": t([]),
            "onsubmit": !0,
            "ignore": ":hidden",
            "ignoreTitle": !1,
            "onfocusin": function(t) {
                this.lastActive = t,
                this.settings.focusCleanup && !this.blockFocusCleanup && (this.settings.unhighlight && this.settings.unhighlight.call(this, t, this.settings.errorClass, this.settings.validClass), this.hideThese(this.errorsFor(t)))
            },
            "onfocusout": function(t) {
                this.checkable(t) || !(t.name in this.submitted) && this.optional(t) || this.element(t)
            },
            "onkeyup": function(t, e) { (9 !== e.which || "" !== this.elementValue(t)) && (t.name in this.submitted || t === this.lastElement) && this.element(t)
            },
            "onclick": function(t) {
                t.name in this.submitted ? this.element(t) : t.parentNode.name in this.submitted && this.element(t.parentNode)
            },
            "highlight": function(e, n, i) {
                "radio" === e.type ? this.findByName(e.name).addClass(n).removeClass(i) : t(e).addClass(n).removeClass(i)
            },
            "unhighlight": function(e, n, i) {
                "radio" === e.type ? this.findByName(e.name).removeClass(n).addClass(i) : t(e).removeClass(n).addClass(i)
            }
        },
        "setDefaults": function(e) {
            t.extend(t.validator.defaults, e)
        },
        "messages": {
            "required": "This field is required.",
            "remote": "Please fix this field.",
            "email": "Please enter a valid email address.",
            "url": "Please enter a valid URL.",
            "date": "Please enter a valid date.",
            "dateISO": "Please enter a valid date ( ISO ).",
            "number": "Please enter a valid number.",
            "digits": "Please enter only digits.",
            "creditcard": "Please enter a valid credit card number.",
            "equalTo": "Please enter the same value again.",
            "maxlength": t.validator.format("Please enter no more than {0} characters."),
            "minlength": t.validator.format("Please enter at least {0} characters."),
            "rangelength": t.validator.format("Please enter a value between {0} and {1} characters long."),
            "range": t.validator.format("Please enter a value between {0} and {1}."),
            "max": t.validator.format("Please enter a value less than or equal to {0}."),
            "min": t.validator.format("Please enter a value greater than or equal to {0}.")
        },
        "autoCreateRanges": !1,
        "prototype": {
            "init": function() {
                function e(e) {
                    var n = t.data(this[0].form, "validator"),
                    i = "on" + e.type.replace(/^validate/, ""),
                    r = n.settings;
                    r[i] && !this.is(r.ignore) && r[i].call(n, this[0], e)
                }
                this.labelContainer = t(this.settings.errorLabelContainer),
                this.errorContext = this.labelContainer.length && this.labelContainer || t(this.currentForm),
                this.containers = t(this.settings.errorContainer).add(this.settings.errorLabelContainer),
                this.submitted = {},
                this.valueCache = {},
                this.pendingRequest = 0,
                this.pending = {},
                this.invalid = {},
                this.reset();
                var n, i = this.groups = {};
                t.each(this.settings.groups,
                function(e, n) {
                    "string" == typeof n && (n = n.split(/\s/)),
                    t.each(n,
                    function(t, n) {
                        i[n] = e
                    })
                }),
                n = this.settings.rules,
                t.each(n,
                function(e, i) {
                    n[e] = t.validator.normalizeRule(i)
                }),
                t(this.currentForm).validateDelegate(":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'] ,[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], [type='radio'], [type='checkbox']", "focusin focusout keyup", e).validateDelegate("select, option, [type='radio'], [type='checkbox']", "click", e),
                this.settings.invalidHandler && t(this.currentForm).bind("invalid-form.validate", this.settings.invalidHandler),
                t(this.currentForm).find("[required], [data-rule-required], .required").attr("aria-required", "true")
            },
            "form": function() {
                return this.checkForm(),
                t.extend(this.submitted, this.errorMap),
                this.invalid = t.extend({},
                this.errorMap),
                this.valid() || t(this.currentForm).triggerHandler("invalid-form", [this]),
                this.showErrors(),
                this.valid()
            },
            "checkForm": function() {
                this.prepareForm();
                for (var t = 0,
                e = this.currentElements = this.elements(); e[t]; t++) this.check(e[t]);
                return this.valid()
            },
            "element": function(e) {
                var n = this.clean(e),
                i = this.validationTargetFor(n),
                r = !0;
                return this.lastElement = i,
                void 0 === i ? delete this.invalid[n.name] : (this.prepareElement(i), this.currentElements = t(i), r = this.check(i) !== !1, r ? delete this.invalid[i.name] : this.invalid[i.name] = !0),
                t(e).attr("aria-invalid", !r),
                this.numberOfInvalids() || (this.toHide = this.toHide.add(this.containers)),
                this.showErrors(),
                r
            },
            "showErrors": function(e) {
                if (e) {
                    t.extend(this.errorMap, e),
                    this.errorList = [];
                    for (var n in e) this.errorList.push({
                        "message": e[n],
                        "element": this.findByName(n)[0]
                    });
                    this.successList = t.grep(this.successList,
                    function(t) {
                        return ! (t.name in e)
                    })
                }
                this.settings.showErrors ? this.settings.showErrors.call(this, this.errorMap, this.errorList) : this.defaultShowErrors()
            },
            "resetForm": function() {
                t.fn.resetForm && t(this.currentForm).resetForm(),
                this.submitted = {},
                this.lastElement = null,
                this.prepareForm(),
                this.hideErrors(),
                this.elements().removeClass(this.settings.errorClass).removeData("previousValue").removeAttr("aria-invalid")
            },
            "numberOfInvalids": function() {
                return this.objectLength(this.invalid)
            },
            "objectLength": function(t) {
                var e, n = 0;
                for (e in t) n++;
                return n
            },
            "hideErrors": function() {
                this.hideThese(this.toHide)
            },
            "hideThese": function(t) {
                t.not(this.containers).text(""),
                this.addWrapper(t).hide()
            },
            "valid": function() {
                return 0 === this.size()
            },
            "size": function() {
                return this.errorList.length
            },
            "focusInvalid": function() {
                if (this.settings.focusInvalid) try {
                    t(this.findLastActive() || this.errorList.length && this.errorList[0].element || []).filter(":visible").focus().trigger("focusin")
                } catch(e) {}
            },
            "findLastActive": function() {
                var e = this.lastActive;
                return e && 1 === t.grep(this.errorList,
                function(t) {
                    return t.element.name === e.name
                }).length && e
            },
            "elements": function() {
                var e = this,
                n = {};
                return t(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function() {
                    return ! this.name && e.settings.debug && window.console,
                    this.name in n || !e.objectLength(t(this).rules()) ? !1 : (n[this.name] = !0, !0)
                })
            },
            "clean": function(e) {
                return t(e)[0]
            },
            "errors": function() {
                var e = this.settings.errorClass.split(" ").join(".");
                return t(this.settings.errorElement + "." + e, this.errorContext)
            },
            "reset": function() {
                this.successList = [],
                this.errorList = [],
                this.errorMap = {},
                this.toShow = t([]),
                this.toHide = t([]),
                this.currentElements = t([])
            },
            "prepareForm": function() {
                this.reset(),
                this.toHide = this.errors().add(this.containers)
            },
            "prepareElement": function(t) {
                this.reset(),
                this.toHide = this.errorsFor(t)
            },
            "elementValue": function(e) {
                var n, i = t(e),
                r = e.type;
                return "radio" === r || "checkbox" === r ? t("input[name='" + e.name + "']:checked").val() : "number" === r && "undefined" != typeof e.validity ? e.validity.badInput ? !1 : i.val() : (n = i.val(), "string" == typeof n ? n.replace(/\r/g, "") : n)
            },
            "check": function(e) {
                e = this.validationTargetFor(this.clean(e));
                var n, i, r, s = t(e).rules(),
                a = t.map(s,
                function(t, e) {
                    return e
                }).length,
                o = !1,
                l = this.elementValue(e);
                for (i in s) {
                    r = {
                        "method": i,
                        "parameters": s[i]
                    };
                    try {
                        if (n = t.validator.methods[i].call(this, l, e, r.parameters), "dependency-mismatch" === n && 1 === a) {
                            o = !0;
                            continue
                        }
                        if (o = !1, "pending" === n) return void(this.toHide = this.toHide.not(this.errorsFor(e)));
                        if (!n) return this.formatAndAdd(e, r),
                        !1
                    } catch(u) {
                        throw this.settings.debug && window.console,
                        u
                    }
                }
                if (!o) return this.objectLength(s) && this.successList.push(e),
                !0
            },
            "customDataMessage": function(e, n) {
                return t(e).data("msg" + n.charAt(0).toUpperCase() + n.substring(1).toLowerCase()) || t(e).data("msg")
            },
            "customMessage": function(t, e) {
                var n = this.settings.messages[t];
                return n && (n.constructor === String ? n: n[e])
            },
            "findDefined": function() {
                for (var t = 0; t < arguments.length; t++) if (void 0 !== arguments[t]) return arguments[t];
                return void 0
            },
            "defaultMessage": function(e, n) {
                return this.findDefined(this.customMessage(e.name, n), this.customDataMessage(e, n), !this.settings.ignoreTitle && e.title || void 0, t.validator.messages[n], "<strong>Warning: No message defined for " + e.name + "</strong>")
            },
            "formatAndAdd": function(e, n) {
                var i = this.defaultMessage(e, n.method),
                r = /\$?\{(\d+)\}/g;
                "function" == typeof i ? i = i.call(this, n.parameters, e) : r.test(i) && (i = t.validator.format(i.replace(r, "{$1}"), n.parameters)),
                this.errorList.push({
                    "message": i,
                    "element": e,
                    "method": n.method
                }),
                this.errorMap[e.name] = i,
                this.submitted[e.name] = i
            },
            "addWrapper": function(t) {
                return this.settings.wrapper && (t = t.add(t.parent(this.settings.wrapper))),
                t
            },
            "defaultShowErrors": function() {
                var t, e, n;
                for (t = 0; this.errorList[t]; t++) n = this.errorList[t],
                this.settings.highlight && this.settings.highlight.call(this, n.element, this.settings.errorClass, this.settings.validClass),
                this.showLabel(n.element, n.message);
                if (this.errorList.length && (this.toShow = this.toShow.add(this.containers)), this.settings.success) for (t = 0; this.successList[t]; t++) this.showLabel(this.successList[t]);
                if (this.settings.unhighlight) for (t = 0, e = this.validElements(); e[t]; t++) this.settings.unhighlight.call(this, e[t], this.settings.errorClass, this.settings.validClass);
                this.toHide = this.toHide.not(this.toShow),
                this.hideErrors(),
                this.addWrapper(this.toShow).show()
            },
            "validElements": function() {
                return this.currentElements.not(this.invalidElements())
            },
            "invalidElements": function() {
                return t(this.errorList).map(function() {
                    return this.element
                })
            },
            "showLabel": function(e, n) {
                var i, r, s, a = this.errorsFor(e),
                o = this.idOrName(e),
                l = t(e).attr("aria-describedby");
                a.length ? (a.removeClass(this.settings.validClass).addClass(this.settings.errorClass), a.html(n)) : (a = t("<" + this.settings.errorElement + ">").attr("id", o + "-error").addClass(this.settings.errorClass).html(n || ""), i = a, this.settings.wrapper && (i = a.hide().show().wrap("<" + this.settings.wrapper + "/>").parent()), this.labelContainer.length ? this.labelContainer.append(i) : this.settings.errorPlacement ? this.settings.errorPlacement(i, t(e)) : i.insertAfter(e), a.is("label") ? a.attr("for", o) : 0 === a.parents("label[for='" + o + "']").length && (s = a.attr("id"), l ? l.match(new RegExp("\b" + s + "\b")) || (l += " " + s) : l = s, t(e).attr("aria-describedby", l), r = this.groups[e.name], r && t.each(this.groups,
                function(e, n) {
                    n === r && t("[name='" + e + "']", this.currentForm).attr("aria-describedby", a.attr("id"))
                }))),
                !n && this.settings.success && (a.text(""), "string" == typeof this.settings.success ? a.addClass(this.settings.success) : this.settings.success(a, e)),
                this.toShow = this.toShow.add(a)
            },
            "errorsFor": function(e) {
                var n = this.idOrName(e),
                i = t(e).attr("aria-describedby"),
                r = "label[for='" + n + "'], label[for='" + n + "'] *";
                return i && (r = r + ", #" + i.replace(/\s+/g, ", #")),
                this.errors().filter(r)
            },
            "idOrName": function(t) {
                return this.groups[t.name] || (this.checkable(t) ? t.name: t.id || t.name)
            },
            "validationTargetFor": function(t) {
                return this.checkable(t) && (t = this.findByName(t.name).not(this.settings.ignore)[0]),
                t
            },
            "checkable": function(t) {
                return /radio|checkbox/i.test(t.type)
            },
            "findByName": function(e) {
                return t(this.currentForm).find("[name='" + e + "']")
            },
            "getLength": function(e, n) {
                switch (n.nodeName.toLowerCase()) {
                case "select":
                    return t("option:selected", n).length;
                case "input":
                    if (this.checkable(n)) return this.findByName(n.name).filter(":checked").length
                }
                return e.length
            },
            "depend": function(t, e) {
                return this.dependTypes[typeof t] ? this.dependTypes[typeof t](t, e) : !0
            },
            "dependTypes": {
                "boolean": function(t) {
                    return t
                },
                "string": function(e, n) {
                    return !! t(e, n.form).length
                },
                "function": function(t, e) {
                    return t(e)
                }
            },
            "optional": function(e) {
                var n = this.elementValue(e);
                return ! t.validator.methods.required.call(this, n, e) && "dependency-mismatch"
            },
            "startRequest": function(t) {
                this.pending[t.name] || (this.pendingRequest++, this.pending[t.name] = !0)
            },
            "stopRequest": function(e, n) {
                this.pendingRequest--,
                this.pendingRequest < 0 && (this.pendingRequest = 0),
                delete this.pending[e.name],
                n && 0 === this.pendingRequest && this.formSubmitted && this.form() ? (t(this.currentForm).submit(), this.formSubmitted = !1) : !n && 0 === this.pendingRequest && this.formSubmitted && (t(this.currentForm).triggerHandler("invalid-form", [this]), this.formSubmitted = !1)
            },
            "previousValue": function(e) {
                return t.data(e, "previousValue") || t.data(e, "previousValue", {
                    "old": null,
                    "valid": !0,
                    "message": this.defaultMessage(e, "remote")
                })
            }
        },
        "classRuleSettings": {
            "required": {
                "required": !0
            },
            "email": {
                "email": !0
            },
            "url": {
                "url": !0
            },
            "date": {
                "date": !0
            },
            "dateISO": {
                "dateISO": !0
            },
            "number": {
                "number": !0
            },
            "digits": {
                "digits": !0
            },
            "creditcard": {
                "creditcard": !0
            }
        },
        "addClassRules": function(e, n) {
            e.constructor === String ? this.classRuleSettings[e] = n: t.extend(this.classRuleSettings, e)
        },
        "classRules": function(e) {
            var n = {},
            i = t(e).attr("class");
            return i && t.each(i.split(" "),
            function() {
                this in t.validator.classRuleSettings && t.extend(n, t.validator.classRuleSettings[this])
            }),
            n
        },
        "attributeRules": function(e) {
            var n, i, r = {},
            s = t(e),
            a = e.getAttribute("type");
            for (n in t.validator.methods)"required" === n ? (i = e.getAttribute(n), "" === i && (i = !0), i = !!i) : i = s.attr(n),
            /min|max/.test(n) && (null === a || /number|range|text/.test(a)) && (i = Number(i)),
            i || 0 === i ? r[n] = i: a === n && "range" !== a && (r[n] = !0);
            return r.maxlength && /-1|2147483647|524288/.test(r.maxlength) && delete r.maxlength,
            r
        },
        "dataRules": function(e) {
            var n, i, r = {},
            s = t(e);
            for (n in t.validator.methods) i = s.data("rule" + n.charAt(0).toUpperCase() + n.substring(1).toLowerCase()),
            void 0 !== i && (r[n] = i);
            return r
        },
        "staticRules": function(e) {
            var n = {},
            i = t.data(e.form, "validator");
            return i.settings.rules && (n = t.validator.normalizeRule(i.settings.rules[e.name]) || {}),
            n
        },
        "normalizeRules": function(e, n) {
            return t.each(e,
            function(i, r) {
                if (r === !1) return void delete e[i];
                if (r.param || r.depends) {
                    var s = !0;
                    switch (typeof r.depends) {
                    case "string":
                        s = !!t(r.depends, n.form).length;
                        break;
                    case "function":
                        s = r.depends.call(n, n)
                    }
                    s ? e[i] = void 0 !== r.param ? r.param: !0 : delete e[i]
                }
            }),
            t.each(e,
            function(i, r) {
                e[i] = t.isFunction(r) ? r(n) : r
            }),
            t.each(["minlength", "maxlength"],
            function() {
                e[this] && (e[this] = Number(e[this]))
            }),
            t.each(["rangelength", "range"],
            function() {
                var n;
                e[this] && (t.isArray(e[this]) ? e[this] = [Number(e[this][0]), Number(e[this][1])] : "string" == typeof e[this] && (n = e[this].replace(/[\[\]]/g, "").split(/[\s,]+/), e[this] = [Number(n[0]), Number(n[1])]))
            }),
            t.validator.autoCreateRanges && (e.min && e.max && (e.range = [e.min, e.max], delete e.min, delete e.max), e.minlength && e.maxlength && (e.rangelength = [e.minlength, e.maxlength], delete e.minlength, delete e.maxlength)),
            e
        },
        "normalizeRule": function(e) {
            if ("string" == typeof e) {
                var n = {};
                t.each(e.split(/\s/),
                function() {
                    n[this] = !0
                }),
                e = n
            }
            return e
        },
        "addMethod": function(e, n, i) {
            t.validator.methods[e] = n,
            t.validator.messages[e] = void 0 !== i ? i: t.validator.messages[e],
            n.length < 3 && t.validator.addClassRules(e, t.validator.normalizeRule(e))
        },
        "methods": {
            "required": function(e, n, i) {
                if (!this.depend(i, n)) return "dependency-mismatch";
                if ("select" === n.nodeName.toLowerCase()) {
                    var r = t(n).val();
                    return r && r.length > 0
                }
                return this.checkable(n) ? this.getLength(e, n) > 0 : t.trim(e).length > 0
            },
            "email": function(t, e) {
                return this.optional(e) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(t)
            },
            "url": function(t, e) {
                return this.optional(e) || /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(t)
            },
            "date": function(t, e) {
                return this.optional(e) || !/Invalid|NaN/.test(new Date(t).toString())
            },
            "dateISO": function(t, e) {
                return this.optional(e) || /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test(t)
            },
            "number": function(t, e) {
                return this.optional(e) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(t)
            },
            "digits": function(t, e) {
                return this.optional(e) || /^\d+$/.test(t)
            },
            "creditcard": function(t, e) {
                if (this.optional(e)) return "dependency-mismatch";
                if (/[^0-9 \-]+/.test(t)) return ! 1;
                var n, i, r = 0,
                s = 0,
                a = !1;
                if (t = t.replace(/\D/g, ""), t.length < 13 || t.length > 19) return ! 1;
                for (n = t.length - 1; n >= 0; n--) i = t.charAt(n),
                s = parseInt(i, 10),
                a && (s *= 2) > 9 && (s -= 9),
                r += s,
                a = !a;
                return r % 10 === 0
            },
            "minlength": function(e, n, i) {
                var r = t.isArray(e) ? e.length: this.getLength(t.trim(e), n);
                return this.optional(n) || r >= i
            },
            "maxlength": function(e, n, i) {
                var r = t.isArray(e) ? e.length: this.getLength(t.trim(e), n);
                return this.optional(n) || i >= r
            },
            "rangelength": function(e, n, i) {
                var r = t.isArray(e) ? e.length: this.getLength(t.trim(e), n);
                return this.optional(n) || r >= i[0] && r <= i[1]
            },
            "min": function(t, e, n) {
                return this.optional(e) || t >= n
            },
            "max": function(t, e, n) {
                return this.optional(e) || n >= t
            },
            "range": function(t, e, n) {
                return this.optional(e) || t >= n[0] && t <= n[1]
            },
            "equalTo": function(e, n, i) {
                var r = t(i);
                return this.settings.onfocusout && r.unbind(".validate-equalTo").bind("blur.validate-equalTo",
                function() {
                    t(n).valid()
                }),
                e === r.val()
            },
            "remote": function(e, n, i) {
                if (this.optional(n)) return "dependency-mismatch";
                var r, s, a = this.previousValue(n);
                return this.settings.messages[n.name] || (this.settings.messages[n.name] = {}),
                a.originalMessage = this.settings.messages[n.name].remote,
                this.settings.messages[n.name].remote = a.message,
                i = "string" == typeof i && {
                    "url": i
                } || i,
                a.old === e ? a.valid: (a.old = e, r = this, this.startRequest(n), s = {},
                s[n.name] = e, t.ajax(t.extend(!0, {
                    "url": i,
                    "mode": "abort",
                    "port": "validate" + n.name,
                    "dataType": "json",
                    "data": s,
                    "context": r.currentForm,
                    "success": function(i) {
                        var s, o, l, u = i === !0 || "true" === i;
                        r.settings.messages[n.name].remote = a.originalMessage,
                        u ? (l = r.formSubmitted, r.prepareElement(n), r.formSubmitted = l, r.successList.push(n), delete r.invalid[n.name], r.showErrors()) : (s = {},
                        o = i || r.defaultMessage(n, "remote"), s[n.name] = a.message = t.isFunction(o) ? o(e) : o, r.invalid[n.name] = !0, r.showErrors(s)),
                        a.valid = u,
                        r.stopRequest(n, u)
                    }
                },
                i)), "pending")
            }
        }
    }),
    t.format = function() {
        throw "$.format has been deprecated. Please use $.validator.format instead."
    };
    var e, n = {};
    t.ajaxPrefilter ? t.ajaxPrefilter(function(t, e, i) {
        var r = t.port;
        "abort" === t.mode && (n[r] && n[r].abort(), n[r] = i)
    }) : (e = t.ajax, t.ajax = function(i) {
        var r = ("mode" in i ? i: t.ajaxSettings).mode,
        s = ("port" in i ? i: t.ajaxSettings).port;
        return "abort" === r ? (n[s] && n[s].abort(), n[s] = e.apply(this, arguments), n[s]) : e.apply(this, arguments)
    }),
    t.extend(t.fn, {
        "validateDelegate": function(e, n, i) {
            return this.bind(n,
            function(n) {
                var r = t(n.target);
                return r.is(e) ? i.apply(r, arguments) : void 0
            })
        }
    })
}),
function(t) {
    "use strict";
    var e = function(e, n) {
        this.options = t.extend({},
        t.fn.editableform.defaults, n),
        this.$div = t(e),
        this.options.scope || (this.options.scope = this)
    };
    e.prototype = {
        "constructor": e,
        "initInput": function() {
            this.input = this.options.input,
            this.value = this.input.str2value(this.options.value),
            this.input.prerender()
        },
        "initTemplate": function() {
            this.$form = t(t.fn.editableform.template)
        },
        "initButtons": function() {
            var e = this.$form.find(".editable-buttons");
            e.append(t.fn.editableform.buttons),
            "bottom" === this.options.showbuttons && e.addClass("editable-buttons-bottom")
        },
        "render": function() {
            this.$loading = t(t.fn.editableform.loading),
            this.$div.empty().append(this.$loading),
            this.initTemplate(),
            this.options.showbuttons ? this.initButtons() : this.$form.find(".editable-buttons").remove(),
            this.showLoading(),
            this.isSaving = !1,
            this.$div.triggerHandler("rendering"),
            this.initInput(),
            this.$form.find("div.editable-input").append(this.input.$tpl),
            this.$div.append(this.$form),
            t.when(this.input.render()).then(t.proxy(function() {
                if (this.options.showbuttons || this.input.autosubmit(), this.$form.find(".editable-cancel").click(t.proxy(this.cancel, this)), this.input.error) this.error(this.input.error),
                this.$form.find(".editable-submit").attr("disabled", !0),
                this.input.$input.attr("disabled", !0),
                this.$form.submit(function(t) {
                    t.preventDefault()
                });
                else {
                    this.error(!1),
                    this.input.$input.removeAttr("disabled"),
                    this.$form.find(".editable-submit").removeAttr("disabled");
                    var e = null === this.value || void 0 === this.value || "" === this.value ? this.options.defaultValue: this.value;
                    this.input.value2input(e),
                    this.$form.submit(t.proxy(this.submit, this))
                }
                this.$div.triggerHandler("rendered"),
                this.showForm(),
                this.input.postrender && this.input.postrender()
            },
            this))
        },
        "cancel": function() {
            this.$div.triggerHandler("cancel")
        },
        "showLoading": function() {
            var t, e;
            this.$form ? (t = this.$form.outerWidth(), e = this.$form.outerHeight(), t && this.$loading.width(t), e && this.$loading.height(e), this.$form.hide()) : (t = this.$loading.parent().width(), t && this.$loading.width(t)),
            this.$loading.show()
        },
        "showForm": function(t) {
            this.$loading.hide(),
            this.$form.show(),
            t !== !1 && this.input.activate(),
            this.$div.triggerHandler("show")
        },
        "error": function(e) {
            var n, i = this.$form.find(".control-group"),
            r = this.$form.find(".editable-error-block");
            if (e === !1) i.removeClass(t.fn.editableform.errorGroupClass),
            r.removeClass(t.fn.editableform.errorBlockClass).empty().hide();
            else {
                if (e) {
                    n = ("" + e).split("\n");
                    for (var s = 0; s < n.length; s++) n[s] = t("<div>").text(n[s]).html();
                    e = n.join("<br>")
                }
                i.addClass(t.fn.editableform.errorGroupClass),
                r.addClass(t.fn.editableform.errorBlockClass).html(e).show()
            }
        },
        "submit": function(e) {
            e.stopPropagation(),
            e.preventDefault();
            var n = this.input.input2value(),
            i = this.validate(n);
            if ("object" === t.type(i) && void 0 !== i.newValue) {
                if (n = i.newValue, this.input.value2input(n), "string" == typeof i.msg) return this.error(i.msg),
                void this.showForm()
            } else if (i) return this.error(i),
            void this.showForm();
            if (!this.options.savenochange && this.input.value2str(n) == this.input.value2str(this.value)) return void this.$div.triggerHandler("nochange");
            var r = this.input.value2submit(n);
            this.isSaving = !0,
            t.when(this.save(r)).done(t.proxy(function(t) {
                this.isSaving = !1;
                var e = "function" == typeof this.options.success ? this.options.success.call(this.options.scope, t, n) : null;
                return e === !1 ? (this.error(!1), void this.showForm(!1)) : "string" == typeof e ? (this.error(e), void this.showForm()) : (e && "object" == typeof e && e.hasOwnProperty("newValue") && (n = e.newValue), this.error(!1), this.value = n, void this.$div.triggerHandler("save", {
                    "newValue": n,
                    "submitValue": r,
                    "response": t
                }))
            },
            this)).fail(t.proxy(function(t) {
                this.isSaving = !1;
                var e;
                e = "function" == typeof this.options.error ? this.options.error.call(this.options.scope, t, n) : "string" == typeof t ? t: t.responseText || t.statusText || "Unknown error!",
                this.error(e),
                this.showForm()
            },
            this))
        },
        "save": function(e) {
            this.options.pk = t.fn.editableutils.tryParseJson(this.options.pk, !0);
            var n, i = "function" == typeof this.options.pk ? this.options.pk.call(this.options.scope) : this.options.pk,
            r = !!("function" == typeof this.options.url || this.options.url && ("always" === this.options.send || "auto" === this.options.send && null !== i && void 0 !== i));
            return r ? (this.showLoading(), n = {
                "name": this.options.name || "",
                "value": e,
                "pk": i
            },
            "function" == typeof this.options.params ? n = this.options.params.call(this.options.scope, n) : (this.options.params = t.fn.editableutils.tryParseJson(this.options.params, !0), t.extend(n, this.options.params)), "function" == typeof this.options.url ? this.options.url.call(this.options.scope, n) : t.ajax(t.extend({
                "url": this.options.url,
                "data": n,
                "type": "POST"
            },
            this.options.ajaxOptions))) : void 0
        },
        "validate": function(t) {
            return void 0 === t && (t = this.value),
            "function" == typeof this.options.validate ? this.options.validate.call(this.options.scope, t) : void 0
        },
        "option": function(t, e) {
            t in this.options && (this.options[t] = e),
            "value" === t && this.setValue(e)
        },
        "setValue": function(t, e) {
            this.value = e ? this.input.str2value(t) : t,
            this.$form && this.$form.is(":visible") && this.input.value2input(this.value)
        }
    },
    t.fn.editableform = function(n) {
        var i = arguments;
        return this.each(function() {
            var r = t(this),
            s = r.data("editableform"),
            a = "object" == typeof n && n;
            s || r.data("editableform", s = new e(this, a)),
            "string" == typeof n && s[n].apply(s, Array.prototype.slice.call(i, 1))
        })
    },
    t.fn.editableform.Constructor = e,
    t.fn.editableform.defaults = {
        "type": "text",
        "url": null,
        "params": null,
        "name": null,
        "pk": null,
        "value": null,
        "defaultValue": null,
        "send": "auto",
        "validate": null,
        "success": null,
        "error": null,
        "ajaxOptions": null,
        "showbuttons": !0,
        "scope": null,
        "savenochange": !1
    },
    t.fn.editableform.template = '<form class="form-inline editableform"><div class="control-group"><div><div class="editable-input"></div><div class="editable-buttons"></div></div><div class="editable-error-block"></div></div></form>',
    t.fn.editableform.loading = '<div class="editableform-loading"></div>',
    t.fn.editableform.buttons = '<button type="submit" class="editable-submit">ok</button><button type="button" class="editable-cancel">cancel</button>',
    t.fn.editableform.errorGroupClass = null,
    t.fn.editableform.errorBlockClass = "editable-error",
    t.fn.editableform.engine = "jquery"
} (window.jQuery),
function(t) {
    "use strict";
    t.fn.editableutils = {
        "inherit": function(t, e) {
            var n = function() {};
            n.prototype = e.prototype,
            t.prototype = new n,
            t.prototype.constructor = t,
            t.superclass = e.prototype
        },
        "setCursorPosition": function(t, e) {
            if (t.setSelectionRange) t.setSelectionRange(e, e);
            else if (t.createTextRange) {
                var n = t.createTextRange();
                n.collapse(!0),
                n.moveEnd("character", e),
                n.moveStart("character", e),
                n.select()
            }
        },
        "tryParseJson": function(t, e) {
            if ("string" == typeof t && t.length && t.match(/^[\{\[].*[\}\]]$/)) if (e) try {
                t = new Function("return " + t)()
            } catch(n) {} finally {
                return t
            } else t = new Function("return " + t)();
            return t
        },
        "sliceObj": function(e, n, i) {
            var r, s, a = {};
            if (!t.isArray(n) || !n.length) return a;
            for (var o = 0; o < n.length; o++) r = n[o],
            e.hasOwnProperty(r) && (a[r] = e[r]),
            i !== !0 && (s = r.toLowerCase(), e.hasOwnProperty(s) && (a[r] = e[s]));
            return a
        },
        "getConfigData": function(e) {
            var n = {};
            return t.each(e.data(),
            function(t, e) { ("object" != typeof e || e && "object" == typeof e && (e.constructor === Object || e.constructor === Array)) && (n[t] = e)
            }),
            n
        },
        "objectKeys": function(t) {
            if (Object.keys) return Object.keys(t);
            if (t !== Object(t)) throw new TypeError("Object.keys called on a non-object");
            var e, n = [];
            for (e in t) Object.prototype.hasOwnProperty.call(t, e) && n.push(e);
            return n
        },
        "escape": function(e) {
            return t("<div>").text(e).html()
        },
        "itemsByValue": function(e, n, i) {
            if (!n || null === e) return [];
            if ("function" != typeof i) {
                var r = i || "value";
                i = function(t) {
                    return t[r]
                }
            }
            var s = t.isArray(e),
            a = [],
            o = this;
            return t.each(n,
            function(n, r) {
                if (r.children) a = a.concat(o.itemsByValue(e, r.children, i));
                else if (s) t.grep(e,
                function(t) {
                    return t == (r && "object" == typeof r ? i(r) : r)
                }).length && a.push(r);
                else {
                    var l = r && "object" == typeof r ? i(r) : r;
                    e == l && a.push(r)
                }
            }),
            a
        },
        "createInput": function(e) {
            var n, i, r, s = e.type;
            return "date" === s && ("inline" === e.mode ? t.fn.editabletypes.datefield ? s = "datefield": t.fn.editabletypes.dateuifield && (s = "dateuifield") : t.fn.editabletypes.date ? s = "date": t.fn.editabletypes.dateui && (s = "dateui"), "date" !== s || t.fn.editabletypes.date || (s = "combodate")),
            "datetime" === s && "inline" === e.mode && (s = "datetimefield"),
            "wysihtml5" !== s || t.fn.editabletypes[s] || (s = "textarea"),
            "function" == typeof t.fn.editabletypes[s] ? (n = t.fn.editabletypes[s], i = this.sliceObj(e, this.objectKeys(n.defaults)), r = new n(i)) : (t.error("Unknown type: " + s), !1)
        },
        "supportsTransitions": function() {
            var t = document.body || document.documentElement,
            e = t.style,
            n = "transition",
            i = ["Moz", "Webkit", "Khtml", "O", "ms"];
            if ("string" == typeof e[n]) return ! 0;
            n = n.charAt(0).toUpperCase() + n.substr(1);
            for (var r = 0; r < i.length; r++) if ("string" == typeof e[i[r] + n]) return ! 0;
            return ! 1
        }
    }
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t, e) {
        this.init(t, e)
    },
    n = function(t, e) {
        this.init(t, e)
    };
    e.prototype = {
        "containerName": null,
        "containerDataName": null,
        "innerCss": null,
        "containerClass": "editable-container editable-popup",
        "defaults": {},
        "init": function(n, i) {
            this.$element = t(n),
            this.options = t.extend({},
            t.fn.editableContainer.defaults, i),
            this.splitOptions(),
            this.formOptions.scope = this.$element[0],
            this.initContainer(),
            this.delayedHide = !1,
            this.$element.on("destroyed", t.proxy(function() {
                this.destroy()
            },
            this)),
            t(document).data("editable-handlers-attached") || (t(document).on("keyup.editable",
            function(e) {
                27 === e.which && t(".editable-open").editableContainer("hide")
            }), t(document).on("click.editable",
            function(n) {
                var i, r = t(n.target),
                s = [".editable-container", ".ui-datepicker-header", ".datepicker", ".modal-backdrop", ".bootstrap-wysihtml5-insert-image-modal", ".bootstrap-wysihtml5-insert-link-modal"];
                if (t.contains(document.documentElement, n.target) && !r.is(document)) {
                    for (i = 0; i < s.length; i++) if (r.is(s[i]) || r.parents(s[i]).length) return;
                    e.prototype.closeOthers(n.target)
                }
            }), t(document).data("editable-handlers-attached", !0))
        },
        "splitOptions": function() {
            if (this.containerOptions = {},
            this.formOptions = {},
            !t.fn[this.containerName]) throw new Error(this.containerName + " not found. Have you included corresponding js file?");
            for (var e in this.options) e in this.defaults ? this.containerOptions[e] = this.options[e] : this.formOptions[e] = this.options[e]
        },
        "tip": function() {
            return this.container() ? this.container().$tip: null
        },
        "container": function() {
            var t;
            return this.containerDataName && (t = this.$element.data(this.containerDataName)) ? t: t = this.$element.data(this.containerName)
        },
        "call": function() {
            this.$element[this.containerName].apply(this.$element, arguments)
        },
        "initContainer": function() {
            this.call(this.containerOptions)
        },
        "renderForm": function() {
            this.$form.editableform(this.formOptions).on({
                "save": t.proxy(this.save, this),
                "nochange": t.proxy(function() {
                    this.hide("nochange")
                },
                this),
                "cancel": t.proxy(function() {
                    this.hide("cancel")
                },
                this),
                "show": t.proxy(function() {
                    this.delayedHide ? (this.hide(this.delayedHide.reason), this.delayedHide = !1) : this.setPosition()
                },
                this),
                "rendering": t.proxy(this.setPosition, this),
                "resize": t.proxy(this.setPosition, this),
                "rendered": t.proxy(function() {
                    this.$element.triggerHandler("shown", t(this.options.scope).data("editable"))
                },
                this)
            }).editableform("render")
        },
        "show": function(e) {
            this.$element.addClass("editable-open"),
            e !== !1 && this.closeOthers(this.$element[0]),
            this.innerShow(),
            this.tip().addClass(this.containerClass),
            this.$form,
            this.$form = t("<div>"),
            this.tip().is(this.innerCss) ? this.tip().append(this.$form) : this.tip().find(this.innerCss).append(this.$form),
            this.renderForm()
        },
        "hide": function(t) {
            if (this.tip() && this.tip().is(":visible") && this.$element.hasClass("editable-open")) {
                if (this.$form.data("editableform").isSaving) return void(this.delayedHide = {
                    "reason": t
                });
                this.delayedHide = !1,
                this.$element.removeClass("editable-open"),
                this.innerHide(),
                this.$element.triggerHandler("hidden", t || "manual")
            }
        },
        "innerShow": function() {},
        "innerHide": function() {},
        "toggle": function(t) {
            this.container() && this.tip() && this.tip().is(":visible") ? this.hide() : this.show(t)
        },
        "setPosition": function() {},
        "save": function(t, e) {
            this.$element.triggerHandler("save", e),
            this.hide("save")
        },
        "option": function(t, e) {
            this.options[t] = e,
            t in this.containerOptions ? (this.containerOptions[t] = e, this.setContainerOption(t, e)) : (this.formOptions[t] = e, this.$form && this.$form.editableform("option", t, e))
        },
        "setContainerOption": function(t, e) {
            this.call("option", t, e)
        },
        "destroy": function() {
            this.hide(),
            this.innerDestroy(),
            this.$element.off("destroyed"),
            this.$element.removeData("editableContainer")
        },
        "innerDestroy": function() {},
        "closeOthers": function(e) {
            t(".editable-open").each(function(n, i) {
                if (i !== e && !t(i).find(e).length) {
                    var r = t(i),
                    s = r.data("editableContainer");
                    s && ("cancel" === s.options.onblur ? r.data("editableContainer").hide("onblur") : "submit" === s.options.onblur && r.data("editableContainer").tip().find("form").submit())
                }
            })
        },
        "activate": function() {
            this.tip && this.tip().is(":visible") && this.$form && this.$form.data("editableform").input.activate()
        }
    },
    t.fn.editableContainer = function(i) {
        var r = arguments;
        return this.each(function() {
            var s = t(this),
            a = "editableContainer",
            o = s.data(a),
            l = "object" == typeof i && i,
            u = "inline" === l.mode ? n: e;
            o || s.data(a, o = new u(this, l)),
            "string" == typeof i && o[i].apply(o, Array.prototype.slice.call(r, 1))
        })
    },
    t.fn.editableContainer.Popup = e,
    t.fn.editableContainer.Inline = n,
    t.fn.editableContainer.defaults = {
        "value": null,
        "placement": "top",
        "autohide": !0,
        "onblur": "cancel",
        "anim": !1,
        "mode": "popup"
    },
    jQuery.event.special.destroyed = {
        "remove": function(t) {
            t.handler && t.handler()
        }
    }
} (window.jQuery),
function(t) {
    "use strict";
    t.extend(t.fn.editableContainer.Inline.prototype, t.fn.editableContainer.Popup.prototype, {
        "containerName": "editableform",
        "innerCss": ".editable-inline",
        "containerClass": "editable-container editable-inline",
        "initContainer": function() {
            this.$tip = t("<span></span>"),
            this.options.anim || (this.options.anim = 0)
        },
        "splitOptions": function() {
            this.containerOptions = {},
            this.formOptions = this.options
        },
        "tip": function() {
            return this.$tip
        },
        "innerShow": function() {
            this.$element.hide(),
            this.tip().insertAfter(this.$element).show()
        },
        "innerHide": function() {
            this.$tip.hide(this.options.anim, t.proxy(function() {
                this.$element.show(),
                this.innerDestroy()
            },
            this))
        },
        "innerDestroy": function() {
            this.tip() && this.tip().empty().remove()
        }
    })
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(e, n) {
        this.$element = t(e),
        this.options = t.extend({},
        t.fn.editable.defaults, n, t.fn.editableutils.getConfigData(this.$element)),
        this.options.selector ? this.initLive() : this.init(),
        this.options.highlight && !t.fn.editableutils.supportsTransitions() && (this.options.highlight = !1)
    };
    e.prototype = {
        "constructor": e,
        "init": function() {
            var e, n = !1;
            if (this.options.name = this.options.name || this.$element.attr("id"), this.options.scope = this.$element[0], this.input = t.fn.editableutils.createInput(this.options), this.input) {
                switch (void 0 === this.options.value || null === this.options.value ? (this.value = this.input.html2value(t.trim(this.$element.html())), n = !0) : (this.options.value = t.fn.editableutils.tryParseJson(this.options.value, !0), this.value = "string" == typeof this.options.value ? this.input.str2value(this.options.value) : this.options.value), this.$element.addClass("editable"), "textarea" === this.input.type && this.$element.addClass("editable-pre-wrapped"), "manual" !== this.options.toggle ? (this.$element.addClass("editable-click"), this.$element.on(this.options.toggle + ".editable", t.proxy(function(t) {
                    if (this.options.disabled || t.preventDefault(), "mouseenter" === this.options.toggle) this.show();
                    else {
                        var e = "click" !== this.options.toggle;
                        this.toggle(e)
                    }
                },
                this))) : this.$element.attr("tabindex", -1), "function" == typeof this.options.display && (this.options.autotext = "always"), this.options.autotext) {
                case "always":
                    e = !0;
                    break;
                case "auto":
                    e = !t.trim(this.$element.text()).length && null !== this.value && void 0 !== this.value && !n;
                    break;
                default:
                    e = !1
                }
                t.when(e ? this.render() : !0).then(t.proxy(function() {
                    this.options.disabled ? this.disable() : this.enable(),
                    this.$element.triggerHandler("init", this)
                },
                this))
            }
        },
        "initLive": function() {
            var e = this.options.selector;
            this.options.selector = !1,
            this.options.autotext = "never",
            this.$element.on(this.options.toggle + ".editable", e, t.proxy(function(e) {
                var n = t(e.target);
                n.data("editable") || (n.hasClass(this.options.emptyclass) && n.empty(), n.editable(this.options).trigger(e))
            },
            this))
        },
        "render": function(t) {
            return this.options.display !== !1 ? this.input.value2htmlFinal ? this.input.value2html(this.value, this.$element[0], this.options.display, t) : "function" == typeof this.options.display ? this.options.display.call(this.$element[0], this.value, t) : this.input.value2html(this.value, this.$element[0]) : void 0
        },
        "enable": function() {
            this.options.disabled = !1,
            this.$element.removeClass("editable-disabled"),
            this.handleEmpty(this.isEmpty),
            "manual" !== this.options.toggle && "-1" === this.$element.attr("tabindex") && this.$element.removeAttr("tabindex")
        },
        "disable": function() {
            this.options.disabled = !0,
            this.hide(),
            this.$element.addClass("editable-disabled"),
            this.handleEmpty(this.isEmpty),
            this.$element.attr("tabindex", -1)
        },
        "toggleDisabled": function() {
            this.options.disabled ? this.enable() : this.disable()
        },
        "option": function(e, n) {
            return e && "object" == typeof e ? void t.each(e, t.proxy(function(e, n) {
                this.option(t.trim(e), n)
            },
            this)) : (this.options[e] = n, "disabled" === e ? n ? this.disable() : this.enable() : ("value" === e && this.setValue(n), this.container && this.container.option(e, n), void(this.input.option && this.input.option(e, n))))
        },
        "handleEmpty": function(e) {
            this.options.display !== !1 && (this.isEmpty = void 0 !== e ? e: "function" == typeof this.input.isEmpty ? this.input.isEmpty(this.$element) : "" === t.trim(this.$element.html()), this.options.disabled ? this.isEmpty && (this.$element.empty(), this.options.emptyclass && this.$element.removeClass(this.options.emptyclass)) : this.isEmpty ? (this.$element.html(this.options.emptytext), this.options.emptyclass && this.$element.addClass(this.options.emptyclass)) : this.options.emptyclass && this.$element.removeClass(this.options.emptyclass))
        },
        "show": function(e) {
            if (!this.options.disabled) {
                if (this.container) {
                    if (this.container.tip().is(":visible")) return
                } else {
                    var n = t.extend({},
                    this.options, {
                        "value": this.value,
                        "input": this.input
                    });
                    this.$element.editableContainer(n),
                    this.$element.on("save.internal", t.proxy(this.save, this)),
                    this.container = this.$element.data("editableContainer")
                }
                this.container.show(e)
            }
        },
        "hide": function() {
            this.container && this.container.hide()
        },
        "toggle": function(t) {
            this.container && this.container.tip().is(":visible") ? this.hide() : this.show(t)
        },
        "save": function(t, e) {
            if (this.options.unsavedclass) {
                var n = !1;
                n = n || "function" == typeof this.options.url,
                n = n || this.options.display === !1,
                n = n || void 0 !== e.response,
                n = n || this.options.savenochange && this.input.value2str(this.value) !== this.input.value2str(e.newValue),
                n ? this.$element.removeClass(this.options.unsavedclass) : this.$element.addClass(this.options.unsavedclass)
            }
            if (this.options.highlight) {
                var i = this.$element,
                r = i.css("background-color");
                i.css("background-color", this.options.highlight),
                setTimeout(function() {
                    "transparent" === r && (r = ""),
                    i.css("background-color", r),
                    i.addClass("editable-bg-transition"),
                    setTimeout(function() {
                        i.removeClass("editable-bg-transition")
                    },
                    1700)
                },
                10)
            }
            this.setValue(e.newValue, !1, e.response)
        },
        "validate": function() {
            return "function" == typeof this.options.validate ? this.options.validate.call(this, this.value) : void 0
        },
        "setValue": function(e, n, i) {
            this.value = n ? this.input.str2value(e) : e,
            this.container && this.container.option("value", this.value),
            t.when(this.render(i)).then(t.proxy(function() {
                this.handleEmpty()
            },
            this))
        },
        "activate": function() {
            this.container && this.container.activate()
        },
        "destroy": function() {
            this.disable(),
            this.container && this.container.destroy(),
            this.input.destroy(),
            "manual" !== this.options.toggle && (this.$element.removeClass("editable-click"), this.$element.off(this.options.toggle + ".editable")),
            this.$element.off("save.internal"),
            this.$element.removeClass("editable editable-open editable-disabled"),
            this.$element.removeData("editable")
        }
    },
    t.fn.editable = function(n) {
        var i = {},
        r = arguments,
        s = "editable";
        switch (n) {
        case "validate":
            return this.each(function() {
                var e, n = t(this),
                r = n.data(s);
                r && (e = r.validate()) && (i[r.options.name] = e)
            }),
            i;
        case "getValue":
            return 2 === arguments.length && arguments[1] === !0 ? i = this.eq(0).data(s).value: this.each(function() {
                var e = t(this),
                n = e.data(s);
                n && void 0 !== n.value && null !== n.value && (i[n.options.name] = n.input.value2submit(n.value))
            }),
            i;
        case "submit":
            var a = arguments[1] || {},
            o = this,
            l = this.editable("validate");
            if (t.isEmptyObject(l)) {
                var u = {};
                if (1 === o.length) {
                    var c = o.data("editable"),
                    h = {
                        "name": c.options.name || "",
                        "value": c.input.value2submit(c.value),
                        "pk": "function" == typeof c.options.pk ? c.options.pk.call(c.options.scope) : c.options.pk
                    };
                    "function" == typeof c.options.params ? h = c.options.params.call(c.options.scope, h) : (c.options.params = t.fn.editableutils.tryParseJson(c.options.params, !0), t.extend(h, c.options.params)),
                    u = {
                        "url": c.options.url,
                        "data": h,
                        "type": "POST"
                    },
                    a.success = a.success || c.options.success,
                    a.error = a.error || c.options.error
                } else {
                    var d = this.editable("getValue");
                    u = {
                        "url": a.url,
                        "data": d,
                        "type": "POST"
                    }
                }
                u.success = "function" == typeof a.success ?
                function(t) {
                    a.success.call(o, t, a)
                }: t.noop,
                u.error = "function" == typeof a.error ?
                function() {
                    a.error.apply(o, arguments)
                }: t.noop,
                a.ajaxOptions && t.extend(u, a.ajaxOptions),
                a.data && t.extend(u.data, a.data),
                t.ajax(u)
            } else "function" == typeof a.error && a.error.call(o, l);
            return this
        }
        return this.each(function() {
            var i = t(this),
            a = i.data(s),
            o = "object" == typeof n && n;
            return o && o.selector ? void(a = new e(this, o)) : (a || i.data(s, a = new e(this, o)), void("string" == typeof n && a[n].apply(a, Array.prototype.slice.call(r, 1))))
        })
    },
    t.fn.editable.defaults = {
        "type": "text",
        "disabled": !1,
        "toggle": "click",
        "emptytext": "Empty",
        "autotext": "auto",
        "value": null,
        "display": null,
        "emptyclass": "editable-empty",
        "unsavedclass": "editable-unsaved",
        "selector": null,
        "highlight": "#FFFF80"
    }
} (window.jQuery),
function(t) {
    "use strict";
    t.fn.editabletypes = {};
    var e = function() {};
    e.prototype = {
        "init": function(e, n, i) {
            this.type = e,
            this.options = t.extend({},
            i, n)
        },
        "prerender": function() {
            this.$tpl = t(this.options.tpl),
            this.$input = this.$tpl,
            this.$clear = null,
            this.error = null
        },
        "render": function() {},
        "value2html": function(e, n) {
            t(n)[this.options.escape ? "text": "html"](t.trim(e))
        },
        "html2value": function(e) {
            return t("<div>").html(e).text()
        },
        "value2str": function(t) {
            return t
        },
        "str2value": function(t) {
            return t
        },
        "value2submit": function(t) {
            return t
        },
        "value2input": function(t) {
            this.$input.val(t)
        },
        "input2value": function() {
            return this.$input.val()
        },
        "activate": function() {
            this.$input.is(":visible") && this.$input.focus()
        },
        "clear": function() {
            this.$input.val(null)
        },
        "escape": function(e) {
            return t("<div>").text(e).html()
        },
        "autosubmit": function() {},
        "destroy": function() {},
        "setClass": function() {
            this.options.inputclass && this.$input.addClass(this.options.inputclass)
        },
        "setAttr": function(t) {
            void 0 !== this.options[t] && null !== this.options[t] && this.$input.attr(t, this.options[t])
        },
        "option": function(t, e) {
            this.options[t] = e
        }
    },
    e.defaults = {
        "tpl": "",
        "inputclass": null,
        "escape": !0,
        "scope": null,
        "showbuttons": !0
    },
    t.extend(t.fn.editabletypes, {
        "abstractinput": e
    })
} (window.jQuery),
function(t) {
    "use strict";
    var e = function() {};
    t.fn.editableutils.inherit(e, t.fn.editabletypes.abstractinput),
    t.extend(e.prototype, {
        "render": function() {
            var e = t.Deferred();
            return this.error = null,
            this.onSourceReady(function() {
                this.renderList(),
                e.resolve()
            },
            function() {
                this.error = this.options.sourceError,
                e.resolve()
            }),
            e.promise()
        },
        "html2value": function() {
            return null
        },
        "value2html": function(e, n, i, r) {
            var s = t.Deferred(),
            a = function() {
                "function" == typeof i ? i.call(n, e, this.sourceData, r) : this.value2htmlFinal(e, n),
                s.resolve()
            };
            return null === e ? a.call(this) : this.onSourceReady(a,
            function() {
                s.resolve()
            }),
            s.promise()
        },
        "onSourceReady": function(e, n) {
            var i;
            if (t.isFunction(this.options.source) ? (i = this.options.source.call(this.options.scope), this.sourceData = null) : i = this.options.source, this.options.sourceCache && t.isArray(this.sourceData)) return void e.call(this);
            try {
                i = t.fn.editableutils.tryParseJson(i, !1)
            } catch(r) {
                return void n.call(this)
            }
            if ("string" == typeof i) {
                if (this.options.sourceCache) {
                    var s, a = i;
                    if (t(document).data(a) || t(document).data(a, {}), s = t(document).data(a), s.loading === !1 && s.sourceData) return this.sourceData = s.sourceData,
                    this.doPrepend(),
                    void e.call(this);
                    if (s.loading === !0) return s.callbacks.push(t.proxy(function() {
                        this.sourceData = s.sourceData,
                        this.doPrepend(),
                        e.call(this)
                    },
                    this)),
                    void s.err_callbacks.push(t.proxy(n, this));
                    s.loading = !0,
                    s.callbacks = [],
                    s.err_callbacks = []
                }
                var o = t.extend({
                    "url": i,
                    "type": "get",
                    "cache": !1,
                    "dataType": "json",
                    "success": t.proxy(function(i) {
                        s && (s.loading = !1),
                        this.sourceData = this.makeArray(i),
                        t.isArray(this.sourceData) ? (s && (s.sourceData = this.sourceData, t.each(s.callbacks,
                        function() {
                            this.call()
                        })), this.doPrepend(), e.call(this)) : (n.call(this), s && t.each(s.err_callbacks,
                        function() {
                            this.call()
                        }))
                    },
                    this),
                    "error": t.proxy(function() {
                        n.call(this),
                        s && (s.loading = !1, t.each(s.err_callbacks,
                        function() {
                            this.call()
                        }))
                    },
                    this)
                },
                this.options.sourceOptions);
                t.ajax(o)
            } else this.sourceData = this.makeArray(i),
            t.isArray(this.sourceData) ? (this.doPrepend(), e.call(this)) : n.call(this)
        },
        "doPrepend": function() {
            null !== this.options.prepend && void 0 !== this.options.prepend && (t.isArray(this.prependData) || (t.isFunction(this.options.prepend) && (this.options.prepend = this.options.prepend.call(this.options.scope)), this.options.prepend = t.fn.editableutils.tryParseJson(this.options.prepend, !0), "string" == typeof this.options.prepend && (this.options.prepend = {
                "": this.options.prepend
            }), this.prependData = this.makeArray(this.options.prepend)), t.isArray(this.prependData) && t.isArray(this.sourceData) && (this.sourceData = this.prependData.concat(this.sourceData)))
        },
        "renderList": function() {},
        "value2htmlFinal": function() {},
        "makeArray": function(e) {
            var n, i, r, s, a = [];
            if (!e || "string" == typeof e) return null;
            if (t.isArray(e)) {
                s = function(t, e) {
                    return i = {
                        "value": t,
                        "text": e
                    },
                    n++>=2 ? !1 : void 0
                };
                for (var o = 0; o < e.length; o++) r = e[o],
                "object" == typeof r ? (n = 0, t.each(r, s), 1 === n ? a.push(i) : n > 1 && (r.children && (r.children = this.makeArray(r.children)), a.push(r))) : a.push({
                    "value": r,
                    "text": r
                })
            } else t.each(e,
            function(t, e) {
                a.push({
                    "value": t,
                    "text": e
                })
            });
            return a
        },
        "option": function(t, e) {
            this.options[t] = e,
            "source" === t && (this.sourceData = null),
            "prepend" === t && (this.prependData = null)
        }
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.abstractinput.defaults, {
        "source": null,
        "prepend": !1,
        "sourceError": "Error when loading list",
        "sourceCache": !0,
        "sourceOptions": null
    }),
    t.fn.editabletypes.list = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t) {
        this.init("text", t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.abstractinput),
    t.extend(e.prototype, {
        "render": function() {
            this.renderClear(),
            this.setClass(),
            this.setAttr("placeholder")
        },
        "activate": function() {
            this.$input.is(":visible") && (this.$input.focus(), t.fn.editableutils.setCursorPosition(this.$input.get(0), this.$input.val().length), this.toggleClear && this.toggleClear())
        },
        "renderClear": function() {
            this.options.clear && (this.$clear = t('<span class="editable-clear-x"></span>'), this.$input.after(this.$clear).css("padding-right", 24).keyup(t.proxy(function(e) {
                if (!~t.inArray(e.keyCode, [40, 38, 9, 13, 27])) {
                    clearTimeout(this.t);
                    var n = this;
                    this.t = setTimeout(function() {
                        n.toggleClear(e)
                    },
                    100)
                }
            },
            this)).parent().css("position", "relative"), this.$clear.click(t.proxy(this.clear, this)))
        },
        "postrender": function() {},
        "toggleClear": function() {
            if (this.$clear) {
                var t = this.$input.val().length,
                e = this.$clear.is(":visible");
                t && !e && this.$clear.show(),
                !t && e && this.$clear.hide()
            }
        },
        "clear": function() {
            this.$clear.hide(),
            this.$input.val("").focus()
        }
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.abstractinput.defaults, {
        "tpl": '<input type="text">',
        "placeholder": null,
        "clear": !0
    }),
    t.fn.editabletypes.text = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t) {
        this.init("textarea", t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.abstractinput),
    t.extend(e.prototype, {
        "render": function() {
            this.setClass(),
            this.setAttr("placeholder"),
            this.setAttr("rows"),
            this.$input.keydown(function(e) {
                e.ctrlKey && 13 === e.which && t(this).closest("form").submit()
            })
        },
        "activate": function() {
            t.fn.editabletypes.text.prototype.activate.call(this)
        }
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.abstractinput.defaults, {
        "tpl": "<textarea></textarea>",
        "inputclass": "input-large",
        "placeholder": null,
        "rows": 7
    }),
    t.fn.editabletypes.textarea = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t) {
        this.init("select", t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.list),
    t.extend(e.prototype, {
        "renderList": function() {
            this.$input.empty();
            var e = function(n, i) {
                var r;
                if (t.isArray(i)) for (var s = 0; s < i.length; s++) r = {},
                i[s].children ? (r.label = i[s].text, n.append(e(t("<optgroup>", r), i[s].children))) : (r.value = i[s].value, i[s].disabled && (r.disabled = !0), n.append(t("<option>", r).text(i[s].text)));
                return n
            };
            e(this.$input, this.sourceData),
            this.setClass(),
            this.$input.on("keydown.editable",
            function(e) {
                13 === e.which && t(this).closest("form").submit()
            })
        },
        "value2htmlFinal": function(e, n) {
            var i = "",
            r = t.fn.editableutils.itemsByValue(e, this.sourceData);
            r.length && (i = r[0].text),
            t.fn.editabletypes.abstractinput.prototype.value2html.call(this, i, n)
        },
        "autosubmit": function() {
            this.$input.off("keydown.editable").on("change.editable",
            function() {
                t(this).closest("form").submit()
            })
        }
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.list.defaults, {
        "tpl": "<select></select>"
    }),
    t.fn.editabletypes.select = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t) {
        this.init("checklist", t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.list),
    t.extend(e.prototype, {
        "renderList": function() {
            var e;
            if (this.$tpl.empty(), t.isArray(this.sourceData)) {
                for (var n = 0; n < this.sourceData.length; n++) e = t("<label>").append(t("<input>", {
                    "type": "checkbox",
                    "value": this.sourceData[n].value
                })).append(t("<span>").text(" " + this.sourceData[n].text)),
                t("<div>").append(e).appendTo(this.$tpl);
                this.$input = this.$tpl.find('input[type="checkbox"]'),
                this.setClass()
            }
        },
        "value2str": function(e) {
            return t.isArray(e) ? e.sort().join(t.trim(this.options.separator)) : ""
        },
        "str2value": function(e) {
            var n, i = null;
            return "string" == typeof e && e.length ? (n = new RegExp("\\s*" + t.trim(this.options.separator) + "\\s*"), i = e.split(n)) : i = t.isArray(e) ? e: [e],
            i
        },
        "value2input": function(e) {
            this.$input.prop("checked", !1),
            t.isArray(e) && e.length && this.$input.each(function(n, i) {
                var r = t(i);
                t.each(e,
                function(t, e) {
                    r.val() == e && r.prop("checked", !0)
                })
            })
        },
        "input2value": function() {
            var e = [];
            return this.$input.filter(":checked").each(function(n, i) {
                e.push(t(i).val())
            }),
            e
        },
        "value2htmlFinal": function(e, n) {
            var i = [],
            r = t.fn.editableutils.itemsByValue(e, this.sourceData),
            s = this.options.escape;
            r.length ? (t.each(r,
            function(e, n) {
                var r = s ? t.fn.editableutils.escape(n.text) : n.text;
                i.push(r)
            }), t(n).html(i.join("<br>"))) : t(n).empty()
        },
        "activate": function() {
            this.$input.first().focus()
        },
        "autosubmit": function() {
            this.$input.on("keydown",
            function(e) {
                13 === e.which && t(this).closest("form").submit()
            })
        }
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.list.defaults, {
        "tpl": '<div class="editable-checklist"></div>',
        "inputclass": null,
        "separator": ","
    }),
    t.fn.editabletypes.checklist = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t) {
        this.init("password", t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.text),
    t.extend(e.prototype, {
        "value2html": function(e, n) {
            e ? t(n).text("[hidden]") : t(n).empty()
        },
        "html2value": function() {
            return null
        }
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.text.defaults, {
        "tpl": '<input type="password">'
    }),
    t.fn.editabletypes.password = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t) {
        this.init("email", t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.text),
    e.defaults = t.extend({},
    t.fn.editabletypes.text.defaults, {
        "tpl": '<input type="email">'
    }),
    t.fn.editabletypes.email = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t) {
        this.init("url", t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.text),
    e.defaults = t.extend({},
    t.fn.editabletypes.text.defaults, {
        "tpl": '<input type="url">'
    }),
    t.fn.editabletypes.url = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t) {
        this.init("tel", t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.text),
    e.defaults = t.extend({},
    t.fn.editabletypes.text.defaults, {
        "tpl": '<input type="tel">'
    }),
    t.fn.editabletypes.tel = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t) {
        this.init("number", t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.text),
    t.extend(e.prototype, {
        "render": function() {
            e.superclass.render.call(this),
            this.setAttr("min"),
            this.setAttr("max"),
            this.setAttr("step")
        },
        "postrender": function() {
            this.$clear && this.$clear.css({
                "right": 24
            })
        }
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.text.defaults, {
        "tpl": '<input type="number">',
        "inputclass": "input-mini",
        "min": null,
        "max": null,
        "step": null
    }),
    t.fn.editabletypes.number = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t) {
        this.init("range", t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.number),
    t.extend(e.prototype, {
        "render": function() {
            this.$input = this.$tpl.filter("input"),
            this.setClass(),
            this.setAttr("min"),
            this.setAttr("max"),
            this.setAttr("step"),
            this.$input.on("input",
            function() {
                t(this).siblings("output").text(t(this).val())
            })
        },
        "activate": function() {
            this.$input.focus()
        }
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.number.defaults, {
        "tpl": '<input type="range"><output style="width: 30px; display: inline-block"></output>',
        "inputclass": "input-medium"
    }),
    t.fn.editabletypes.range = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t) {
        this.init("time", t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.abstractinput),
    t.extend(e.prototype, {
        "render": function() {
            this.setClass()
        }
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.abstractinput.defaults, {
        "tpl": '<input type="time">'
    }),
    t.fn.editabletypes.time = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(n) {
        if (this.init("select2", n, e.defaults), n.select2 = n.select2 || {},
        this.sourceData = null, n.placeholder && (n.select2.placeholder = n.placeholder), !n.select2.tags && n.source) {
            var i = n.source;
            t.isFunction(n.source) && (i = n.source.call(n.scope)),
            "string" == typeof i ? (n.select2.ajax = n.select2.ajax || {},
            n.select2.ajax.data || (n.select2.ajax.data = function(t) {
                return {
                    "query": t
                }
            }), n.select2.ajax.results || (n.select2.ajax.results = function(t) {
                return {
                    "results": t
                }
            }), n.select2.ajax.url = i) : (this.sourceData = this.convertSource(i), n.select2.data = this.sourceData)
        }
        if (this.options.select2 = t.extend({},
        e.defaults.select2, n.select2), this.isMultiple = this.options.select2.tags || this.options.select2.multiple, this.isRemote = "ajax" in this.options.select2, this.idFunc = this.options.select2.id, "function" != typeof this.idFunc) {
            var r = this.idFunc || "id";
            this.idFunc = function(t) {
                return t[r]
            }
        }
        this.formatSelection = this.options.select2.formatSelection,
        "function" != typeof this.formatSelection && (this.formatSelection = function(t) {
            return t.text
        })
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.abstractinput),
    t.extend(e.prototype, {
        "render": function() {
            this.setClass(),
            this.isRemote && this.$input.on("select2-loaded", t.proxy(function(t) {
                this.sourceData = t.items.results
            },
            this)),
            this.isMultiple && this.$input.on("change",
            function() {
                t(this).closest("form").parent().triggerHandler("resize")
            })
        },
        "value2html": function(n, i) {
            var r, s = "",
            a = this;
            this.options.select2.tags ? r = n: this.sourceData && (r = t.fn.editableutils.itemsByValue(n, this.sourceData, this.idFunc)),
            t.isArray(r) ? (s = [], t.each(r,
            function(t, e) {
                s.push(e && "object" == typeof e ? a.formatSelection(e) : e)
            })) : r && (s = a.formatSelection(r)),
            s = t.isArray(s) ? s.join(this.options.viewseparator) : s,
            e.superclass.value2html.call(this, s, i)
        },
        "html2value": function(t) {
            return this.options.select2.tags ? this.str2value(t, this.options.viewseparator) : null
        },
        "value2input": function(e) {
            if (t.isArray(e) && (e = e.join(this.getSeparator())), this.$input.data("select2") ? this.$input.val(e).trigger("change", !0) : (this.$input.val(e), this.$input.select2(this.options.select2)), this.isRemote && !this.isMultiple && !this.options.select2.initSelection) {
                var n = this.options.select2.id,
                i = this.options.select2.formatSelection;
                if (!n && !i) {
                    var r = t(this.options.scope);
                    if (!r.data("editable").isEmpty) {
                        var s = {
                            "id": e,
                            "text": r.text()
                        };
                        this.$input.select2("data", s)
                    }
                }
            }
        },
        "input2value": function() {
            return this.$input.select2("val")
        },
        "str2value": function(e, n) {
            if ("string" != typeof e || !this.isMultiple) return e;
            n = n || this.getSeparator();
            var i, r, s;
            if (null === e || e.length < 1) return null;
            for (i = e.split(n), r = 0, s = i.length; s > r; r += 1) i[r] = t.trim(i[r]);
            return i
        },
        "autosubmit": function() {
            this.$input.on("change",
            function(e, n) {
                n || t(this).closest("form").submit()
            })
        },
        "getSeparator": function() {
            return this.options.select2.separator || t.fn.select2.defaults.separator
        },
        "convertSource": function(e) {
            if (t.isArray(e) && e.length && void 0 !== e[0].value) for (var n = 0; n < e.length; n++) void 0 !== e[n].value && (e[n].id = e[n].value, delete e[n].value);
            return e
        },
        "destroy": function() {
            this.$input.data("select2") && this.$input.select2("destroy")
        }
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.abstractinput.defaults, {
        "tpl": '<input type="hidden">',
        "select2": null,
        "placeholder": null,
        "source": null,
        "viewseparator": ", "
    }),
    t.fn.editabletypes.select2 = e
} (window.jQuery),
function(t) {
    var e = function(e, n) {
        return this.$element = t(e),
        this.$element.is("input") ? (this.options = t.extend({},
        t.fn.combodate.defaults, n, this.$element.data()), void this.init()) : void t.error("Combodate should be applied to INPUT element")
    };
    e.prototype = {
        "constructor": e,
        "init": function() {
            this.map = {
                "day": ["D", "date"],
                "month": ["M", "month"],
                "year": ["Y", "year"],
                "hour": ["[Hh]", "hours"],
                "minute": ["m", "minutes"],
                "second": ["s", "seconds"],
                "ampm": ["[Aa]", ""]
            },
            this.$widget = t('<span class="combodate"></span>').html(this.getTemplate()),
            this.initCombos(),
            this.$widget.on("change", "select", t.proxy(function(e) {
                this.$element.val(this.getValue()).change(),
                this.options.smartDays && (t(e.target).is(".month") || t(e.target).is(".year")) && this.fillCombo("day")
            },
            this)),
            this.$widget.find("select").css("width", "auto"),
            this.$element.hide().after(this.$widget),
            this.setValue(this.$element.val() || this.options.value)
        },
        "getTemplate": function() {
            var e = this.options.template;
            return t.each(this.map,
            function(t, n) {
                n = n[0];
                var i = new RegExp(n + "+"),
                r = n.length > 1 ? n.substring(1, 2) : n;
                e = e.replace(i, "{" + r + "}")
            }),
            e = e.replace(/ /g, "&nbsp;"),
            t.each(this.map,
            function(t, n) {
                n = n[0];
                var i = n.length > 1 ? n.substring(1, 2) : n;
                e = e.replace("{" + i + "}", '<select class="' + t + '"></select>')
            }),
            e
        },
        "initCombos": function() {
            for (var t in this.map) {
                var e = this.$widget.find("." + t);
                this["$" + t] = e.length ? e: null,
                this.fillCombo(t)
            }
        },
        "fillCombo": function(t) {
            var e = this["$" + t];
            if (e) {
                var n = "fill" + t.charAt(0).toUpperCase() + t.slice(1),
                i = this[n](),
                r = e.val();
                e.empty();
                for (var s = 0; s < i.length; s++) e.append('<option value="' + i[s][0] + '">' + i[s][1] + "</option>");
                e.val(r)
            }
        },
        "fillCommon": function(t) {
            var e, n = [];
            if ("name" === this.options.firstItem) {
                e = moment.relativeTime || moment.langData()._relativeTime;
                var i = "function" == typeof e[t] ? e[t](1, !0, t, !1) : e[t];
                i = i.split(" ").reverse()[0],
                n.push(["", i])
            } else "empty" === this.options.firstItem && n.push(["", ""]);
            return n
        },
        "fillDay": function() {
            var t, e, n = this.fillCommon("d"),
            i = -1 !== this.options.template.indexOf("DD"),
            r = 31;
            if (this.options.smartDays && this.$month && this.$year) {
                var s = parseInt(this.$month.val(), 10),
                a = parseInt(this.$year.val(), 10);
                isNaN(s) || isNaN(a) || (r = moment([a, s]).daysInMonth())
            }
            for (e = 1; r >= e; e++) t = i ? this.leadZero(e) : e,
            n.push([e, t]);
            return n
        },
        "fillMonth": function() {
            var t, e, n = this.fillCommon("M"),
            i = -1 !== this.options.template.indexOf("MMMM"),
            r = -1 !== this.options.template.indexOf("MMM"),
            s = -1 !== this.options.template.indexOf("MM");
            for (e = 0; 11 >= e; e++) t = i ? moment().date(1).month(e).format("MMMM") : r ? moment().date(1).month(e).format("MMM") : s ? this.leadZero(e + 1) : e + 1,
            n.push([e, t]);
            return n
        },
        "fillYear": function() {
            var t, e, n = [],
            i = -1 !== this.options.template.indexOf("YYYY");
            for (e = this.options.maxYear; e >= this.options.minYear; e--) t = i ? e: (e + "").substring(2),
            n[this.options.yearDescending ? "push": "unshift"]([e, t]);
            return n = this.fillCommon("y").concat(n)
        },
        "fillHour": function() {
            var t, e, n = this.fillCommon("h"),
            i = -1 !== this.options.template.indexOf("h"),
            r = ( - 1 !== this.options.template.indexOf("H"), -1 !== this.options.template.toLowerCase().indexOf("hh")),
            s = i ? 1 : 0,
            a = i ? 12 : 23;
            for (e = s; a >= e; e++) t = r ? this.leadZero(e) : e,
            n.push([e, t]);
            return n
        },
        "fillMinute": function() {
            var t, e, n = this.fillCommon("m"),
            i = -1 !== this.options.template.indexOf("mm");
            for (e = 0; 59 >= e; e += this.options.minuteStep) t = i ? this.leadZero(e) : e,
            n.push([e, t]);
            return n
        },
        "fillSecond": function() {
            var t, e, n = this.fillCommon("s"),
            i = -1 !== this.options.template.indexOf("ss");
            for (e = 0; 59 >= e; e += this.options.secondStep) t = i ? this.leadZero(e) : e,
            n.push([e, t]);
            return n
        },
        "fillAmpm": function() {
            var t = -1 !== this.options.template.indexOf("a"),
            e = ( - 1 !== this.options.template.indexOf("A"), [["am", t ? "am": "AM"], ["pm", t ? "pm": "PM"]]);
            return e
        },
        "getValue": function(e) {
            var n, i = {},
            r = this,
            s = !1;
            return t.each(this.map,
            function(t) {
                if ("ampm" !== t) {
                    var e = "day" === t ? 1 : 0;
                    return i[t] = r["$" + t] ? parseInt(r["$" + t].val(), 10) : e,
                    isNaN(i[t]) ? (s = !0, !1) : void 0
                }
            }),
            s ? "": (this.$ampm && (i.hour = 12 === i.hour ? "am" === this.$ampm.val() ? 0 : 12 : "am" === this.$ampm.val() ? i.hour: i.hour + 12), n = moment([i.year, i.month, i.day, i.hour, i.minute, i.second]), this.highlight(n), e = void 0 === e ? this.options.format: e, null === e ? n.isValid() ? n: null: n.isValid() ? n.format(e) : "")
        },
        "setValue": function(e) {
            function n(e, n) {
                var i = {};
                return e.children("option").each(function(e, r) {
                    var s, a = t(r).attr("value");
                    "" !== a && (s = Math.abs(a - n), ("undefined" == typeof i.distance || s < i.distance) && (i = {
                        "value": a,
                        "distance": s
                    }))
                }),
                i.value
            }
            if (e) {
                var i = "string" == typeof e ? moment(e, this.options.format) : moment(e),
                r = this,
                s = {};
                i.isValid() && (t.each(this.map,
                function(t, e) {
                    "ampm" !== t && (s[t] = i[e[1]]())
                }), this.$ampm && (s.hour >= 12 ? (s.ampm = "pm", s.hour > 12 && (s.hour -= 12)) : (s.ampm = "am", 0 === s.hour && (s.hour = 12))), t.each(s,
                function(t, e) {
                    r["$" + t] && ("minute" === t && r.options.minuteStep > 1 && r.options.roundTime && (e = n(r["$" + t], e)), "second" === t && r.options.secondStep > 1 && r.options.roundTime && (e = n(r["$" + t], e)), r["$" + t].val(e))
                }), this.options.smartDays && this.fillCombo("day"), this.$element.val(i.format(this.options.format)).change())
            }
        },
        "highlight": function(t) {
            t.isValid() ? this.options.errorClass ? this.$widget.removeClass(this.options.errorClass) : this.$widget.find("select").css("border-color", this.borderColor) : this.options.errorClass ? this.$widget.addClass(this.options.errorClass) : (this.borderColor || (this.borderColor = this.$widget.find("select").css("border-color")), this.$widget.find("select").css("border-color", "red"))
        },
        "leadZero": function(t) {
            return 9 >= t ? "0" + t: t
        },
        "destroy": function() {
            this.$widget.remove(),
            this.$element.removeData("combodate").show()
        }
    },
    t.fn.combodate = function(n) {
        var i, r = Array.apply(null, arguments);
        return r.shift(),
        "getValue" === n && this.length && (i = this.eq(0).data("combodate")) ? i.getValue.apply(i, r) : this.each(function() {
            var i = t(this),
            s = i.data("combodate"),
            a = "object" == typeof n && n;
            s || i.data("combodate", s = new e(this, a)),
            "string" == typeof n && "function" == typeof s[n] && s[n].apply(s, r)
        })
    },
    t.fn.combodate.defaults = {
        "format": "DD-MM-YYYY HH:mm",
        "template": "D / MMM / YYYY   H : mm",
        "value": null,
        "minYear": 1970,
        "maxYear": 2015,
        "yearDescending": !0,
        "minuteStep": 5,
        "secondStep": 1,
        "firstItem": "empty",
        "errorClass": null,
        "roundTime": !0,
        "smartDays": !1
    }
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(n) {
        this.init("combodate", n, e.defaults),
        this.options.viewformat || (this.options.viewformat = this.options.format),
        n.combodate = t.fn.editableutils.tryParseJson(n.combodate, !0),
        this.options.combodate = t.extend({},
        e.defaults.combodate, n.combodate, {
            "format": this.options.format,
            "template": this.options.template
        })
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.abstractinput),
    t.extend(e.prototype, {
        "render": function() {
            this.$input.combodate(this.options.combodate),
            "bs3" === t.fn.editableform.engine && this.$input.siblings().find("select").addClass("form-control"),
            this.options.inputclass && this.$input.siblings().find("select").addClass(this.options.inputclass)
        },
        "value2html": function(t, n) {
            var i = t ? t.format(this.options.viewformat) : "";
            e.superclass.value2html.call(this, i, n)
        },
        "html2value": function(t) {
            return t ? moment(t, this.options.viewformat) : null
        },
        "value2str": function(t) {
            return t ? t.format(this.options.format) : ""
        },
        "str2value": function(t) {
            return t ? moment(t, this.options.format) : null
        },
        "value2submit": function(t) {
            return this.value2str(t)
        },
        "value2input": function(t) {
            this.$input.combodate("setValue", t)
        },
        "input2value": function() {
            return this.$input.combodate("getValue", null)
        },
        "activate": function() {
            this.$input.siblings(".combodate").find("select").eq(0).focus()
        },
        "autosubmit": function() {}
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.abstractinput.defaults, {
        "tpl": '<input type="text">',
        "inputclass": null,
        "format": "YYYY-MM-DD",
        "viewformat": null,
        "template": "D / MMM / YYYY",
        "combodate": null
    }),
    t.fn.editabletypes.combodate = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = t.fn.editableform.Constructor.prototype.initInput;
    t.extend(t.fn.editableform.Constructor.prototype, {
        "initTemplate": function() {
            this.$form = t(t.fn.editableform.template),
            this.$form.find(".control-group").addClass("form-group"),
            this.$form.find(".editable-error-block").addClass("help-block")
        },
        "initInput": function() {
            e.apply(this);
            var n = null === this.input.options.inputclass || this.input.options.inputclass === !1,
            i = "input-sm",
            r = "text,select,textarea,password,email,url,tel,number,range,time,typeaheadjs".split(",");~t.inArray(this.input.type, r) && (this.input.$input.addClass("form-control"), n && (this.input.options.inputclass = i, this.input.$input.addClass(i)));
            for (var s = this.$form.find(".editable-buttons"), a = n ? [i] : this.input.options.inputclass.split(" "), o = 0; o < a.length; o++)"input-lg" === a[o].toLowerCase() && s.find("button").removeClass("btn-sm").addClass("btn-lg")
        }
    }),
    t.fn.editableform.buttons = '<button type="submit" class="btn btn-primary btn-sm editable-submit"><i class="glyphicon glyphicon-ok"></i></button><button type="button" class="btn btn-default btn-sm editable-cancel"><i class="glyphicon glyphicon-remove"></i></button>',
    t.fn.editableform.errorGroupClass = "has-error",
    t.fn.editableform.errorBlockClass = null,
    t.fn.editableform.engine = "bs3"
} (window.jQuery),
function(t) {
    "use strict";
    t.extend(t.fn.editableContainer.Popup.prototype, {
        "containerName": "popover",
        "containerDataName": "bs.popover",
        "innerCss": ".popover-content",
        "defaults": t.fn.popover.Constructor.DEFAULTS,
        "initContainer": function() {
            t.extend(this.containerOptions, {
                "trigger": "manual",
                "selector": !1,
                "content": " ",
                "template": this.defaults.template
            });
            var e;
            this.$element.data("template") && (e = this.$element.data("template"), this.$element.removeData("template")),
            this.call(this.containerOptions),
            e && this.$element.data("template", e)
        },
        "innerShow": function() {
            this.call("show")
        },
        "innerHide": function() {
            this.call("hide")
        },
        "innerDestroy": function() {
            this.call("destroy")
        },
        "setContainerOption": function(t, e) {
            this.container().options[t] = e
        },
        "setPosition": function() { (function() {
                var t = this.tip(),
                e = "function" == typeof this.options.placement ? this.options.placement.call(this, t[0], this.$element[0]) : this.options.placement,
                n = /\s?auto?\s?/i,
                i = n.test(e);
                i && (e = e.replace(n, "") || "top");
                var r = this.getPosition(),
                s = t[0].offsetWidth,
                a = t[0].offsetHeight;
                if (i) {
                    var o = this.$element.parent(),
                    l = e,
                    u = document.documentElement.scrollTop || document.body.scrollTop,
                    c = "body" == this.options.container ? window.innerWidth: o.outerWidth(),
                    h = "body" == this.options.container ? window.innerHeight: o.outerHeight(),
                    d = "body" == this.options.container ? 0 : o.offset().left;
                    e = "bottom" == e && r.top + r.height + a - u > h ? "top": "top" == e && r.top - u - a < 0 ? "bottom": "right" == e && r.right + s > c ? "left": "left" == e && r.left - s < d ? "right": e,
                    t.removeClass(l).addClass(e)
                }
                var f = this.getCalculatedOffset(e, r, s, a);
                this.applyPlacement(f, e)
            }).call(this.container())
        }
    })
} (window.jQuery),
function(t) {
    function e() {
        return new Date(Date.UTC.apply(Date, arguments))
    }
    function n(e, n) {
        var i, r = t(e).data(),
        s = {},
        a = new RegExp("^" + n.toLowerCase() + "([A-Z])"),
        n = new RegExp("^" + n.toLowerCase());
        for (var o in r) n.test(o) && (i = o.replace(a,
        function(t, e) {
            return e.toLowerCase()
        }), s[i] = r[o]);
        return s
    }
    function i(e) {
        var n = {};
        if (c[e] || (e = e.split("-")[0], c[e])) {
            var i = c[e];
            return t.each(u,
            function(t, e) {
                e in i && (n[e] = i[e])
            }),
            n
        }
    }
    var r = function(e, n) {
        this._process_options(n),
        this.element = t(e),
        this.isInline = !1,
        this.isInput = this.element.is("input"),
        this.component = this.element.is(".date") ? this.element.find(".add-on, .btn") : !1,
        this.hasInput = this.component && this.element.find("input").length,
        this.component && 0 === this.component.length && (this.component = !1),
        this.picker = t(h.template),
        this._buildEvents(),
        this._attachEvents(),
        this.isInline ? this.picker.addClass("datepicker-inline").appendTo(this.element) : this.picker.addClass("datepicker-dropdown dropdown-menu"),
        this.o.rtl && (this.picker.addClass("datepicker-rtl"), this.picker.find(".prev i, .next i").toggleClass("icon-arrow-left icon-arrow-right")),
        this.viewMode = this.o.startView,
        this.o.calendarWeeks && this.picker.find("tfoot th.today").attr("colspan",
        function(t, e) {
            return parseInt(e) + 1
        }),
        this._allow_update = !1,
        this.setStartDate(this.o.startDate),
        this.setEndDate(this.o.endDate),
        this.setDaysOfWeekDisabled(this.o.daysOfWeekDisabled),
        this.fillDow(),
        this.fillMonths(),
        this._allow_update = !0,
        this.update(),
        this.showMode(),
        this.isInline && this.show()
    };
    r.prototype = {
        "constructor": r,
        "_process_options": function(e) {
            this._o = t.extend({},
            this._o, e);
            var n = this.o = t.extend({},
            this._o),
            i = n.language;
            switch (c[i] || (i = i.split("-")[0], c[i] || (i = l.language)), n.language = i, n.startView) {
            case 2:
            case "decade":
                n.startView = 2;
                break;
            case 1:
            case "year":
                n.startView = 1;
                break;
            default:
                n.startView = 0
            }
            switch (n.minViewMode) {
            case 1:
            case "months":
                n.minViewMode = 1;
                break;
            case 2:
            case "years":
                n.minViewMode = 2;
                break;
            default:
                n.minViewMode = 0
            }
            n.startView = Math.max(n.startView, n.minViewMode),
            n.weekStart %= 7,
            n.weekEnd = (n.weekStart + 6) % 7;
            var r = h.parseFormat(n.format);
            n.startDate !== -1 / 0 && (n.startDate = h.parseDate(n.startDate, r, n.language)),
            1 / 0 !== n.endDate && (n.endDate = h.parseDate(n.endDate, r, n.language)),
            n.daysOfWeekDisabled = n.daysOfWeekDisabled || [],
            t.isArray(n.daysOfWeekDisabled) || (n.daysOfWeekDisabled = n.daysOfWeekDisabled.split(/[,\s]*/)),
            n.daysOfWeekDisabled = t.map(n.daysOfWeekDisabled,
            function(t) {
                return parseInt(t, 10)
            })
        },
        "_events": [],
        "_secondaryEvents": [],
        "_applyEvents": function(t) {
            for (var e, n, i = 0; i < t.length; i++) e = t[i][0],
            n = t[i][1],
            e.on(n)
        },
        "_unapplyEvents": function(t) {
            for (var e, n, i = 0; i < t.length; i++) e = t[i][0],
            n = t[i][1],
            e.off(n)
        },
        "_buildEvents": function() {
            this.isInput ? this._events = [[this.element, {
                "focus": t.proxy(this.show, this),
                "keyup": t.proxy(this.update, this),
                "keydown": t.proxy(this.keydown, this)
            }]] : this.component && this.hasInput ? this._events = [[this.element.find("input"), {
                "focus": t.proxy(this.show, this),
                "keyup": t.proxy(this.update, this),
                "keydown": t.proxy(this.keydown, this)
            }], [this.component, {
                "click": t.proxy(this.show, this)
            }]] : this.element.is("div") ? this.isInline = !0 : this._events = [[this.element, {
                "click": t.proxy(this.show, this)
            }]],
            this._secondaryEvents = [[this.picker, {
                "click": t.proxy(this.click, this)
            }], [t(window), {
                "resize": t.proxy(this.place, this)
            }], [t(document), {
                "mousedown": t.proxy(function(t) {
                    this.element.is(t.target) || this.element.find(t.target).size() || this.picker.is(t.target) || this.picker.find(t.target).size() || this.hide()
                },
                this)
            }]]
        },
        "_attachEvents": function() {
            this._detachEvents(),
            this._applyEvents(this._events)
        },
        "_detachEvents": function() {
            this._unapplyEvents(this._events)
        },
        "_attachSecondaryEvents": function() {
            this._detachSecondaryEvents(),
            this._applyEvents(this._secondaryEvents)
        },
        "_detachSecondaryEvents": function() {
            this._unapplyEvents(this._secondaryEvents)
        },
        "_trigger": function(e, n) {
            var i = n || this.date,
            r = new Date(i.getTime() + 6e4 * i.getTimezoneOffset());
            this.element.trigger({
                "type": e,
                "date": r,
                "format": t.proxy(function(t) {
                    var e = t || this.o.format;
                    return h.formatDate(i, e, this.o.language)
                },
                this)
            })
        },
        "show": function(t) {
            this.isInline || this.picker.appendTo("body"),
            this.picker.show(),
            this.height = this.component ? this.component.outerHeight() : this.element.outerHeight(),
            this.place(),
            this._attachSecondaryEvents(),
            t && t.preventDefault(),
            this._trigger("show")
        },
        "hide": function() {
            this.isInline || this.picker.is(":visible") && (this.picker.hide().detach(), this._detachSecondaryEvents(), this.viewMode = this.o.startView, this.showMode(), this.o.forceParse && (this.isInput && this.element.val() || this.hasInput && this.element.find("input").val()) && this.setValue(), this._trigger("hide"))
        },
        "remove": function() {
            this.hide(),
            this._detachEvents(),
            this._detachSecondaryEvents(),
            this.picker.remove(),
            delete this.element.data().datepicker,
            this.isInput || delete this.element.data().date
        },
        "getDate": function() {
            var t = this.getUTCDate();
            return new Date(t.getTime() + 6e4 * t.getTimezoneOffset())
        },
        "getUTCDate": function() {
            return this.date
        },
        "setDate": function(t) {
            this.setUTCDate(new Date(t.getTime() - 6e4 * t.getTimezoneOffset()))
        },
        "setUTCDate": function(t) {
            this.date = t,
            this.setValue()
        },
        "setValue": function() {
            var t = this.getFormattedDate();
            this.isInput ? this.element.val(t) : this.component && this.element.find("input").val(t)
        },
        "getFormattedDate": function(t) {
            return void 0 === t && (t = this.o.format),
            h.formatDate(this.date, t, this.o.language)
        },
        "setStartDate": function(t) {
            this._process_options({
                "startDate": t
            }),
            this.update(),
            this.updateNavArrows()
        },
        "setEndDate": function(t) {
            this._process_options({
                "endDate": t
            }),
            this.update(),
            this.updateNavArrows()
        },
        "setDaysOfWeekDisabled": function(t) {
            this._process_options({
                "daysOfWeekDisabled": t
            }),
            this.update(),
            this.updateNavArrows()
        },
        "place": function() {
            if (!this.isInline) {
                var e = parseInt(this.element.parents().filter(function() {
                    return "auto" != t(this).css("z-index")
                }).first().css("z-index")) + 10,
                n = this.component ? this.component.parent().offset() : this.element.offset(),
                i = this.component ? this.component.outerHeight(!0) : this.element.outerHeight(!0);
                this.picker.css({
                    "top": n.top + i,
                    "left": n.left,
                    "zIndex": e
                })
            }
        },
        "_allow_update": !0,
        "update": function() {
            if (this._allow_update) {
                var t, e = !1;
                arguments && arguments.length && ("string" == typeof arguments[0] || arguments[0] instanceof Date) ? (t = arguments[0], e = !0) : (t = this.isInput ? this.element.val() : this.element.data("date") || this.element.find("input").val(), delete this.element.data().date),
                this.date = h.parseDate(t, this.o.format, this.o.language),
                e && this.setValue(),
                this.viewDate = new Date(this.date < this.o.startDate ? this.o.startDate: this.date > this.o.endDate ? this.o.endDate: this.date),
                this.fill()
            }
        },
        "fillDow": function() {
            var t = this.o.weekStart,
            e = "<tr>";
            if (this.o.calendarWeeks) {
                var n = '<th class="cw">&nbsp;</th>';
                e += n,
                this.picker.find(".datepicker-days thead tr:first-child").prepend(n)
            }
            for (; t < this.o.weekStart + 7;) e += '<th class="dow">' + c[this.o.language].daysMin[t++%7] + "</th>";
            e += "</tr>",
            this.picker.find(".datepicker-days thead").append(e)
        },
        "fillMonths": function() {
            for (var t = "",
            e = 0; 12 > e;) t += '<span class="month">' + c[this.o.language].monthsShort[e++] + "</span>";
            this.picker.find(".datepicker-months td").html(t)
        },
        "setRange": function(e) {
            e && e.length ? this.range = t.map(e,
            function(t) {
                return t.valueOf()
            }) : delete this.range,
            this.fill()
        },
        "getClassNames": function(e) {
            var n = [],
            i = this.viewDate.getUTCFullYear(),
            r = this.viewDate.getUTCMonth(),
            s = this.date.valueOf(),
            a = new Date;
            return e.getUTCFullYear() < i || e.getUTCFullYear() == i && e.getUTCMonth() < r ? n.push("old") : (e.getUTCFullYear() > i || e.getUTCFullYear() == i && e.getUTCMonth() > r) && n.push("new"),
            this.o.todayHighlight && e.getUTCFullYear() == a.getFullYear() && e.getUTCMonth() == a.getMonth() && e.getUTCDate() == a.getDate() && n.push("today"),
            s && e.valueOf() == s && n.push("active"),
            (e.valueOf() < this.o.startDate || e.valueOf() > this.o.endDate || -1 !== t.inArray(e.getUTCDay(), this.o.daysOfWeekDisabled)) && n.push("disabled"),
            this.range && (e > this.range[0] && e < this.range[this.range.length - 1] && n.push("range"), -1 != t.inArray(e.valueOf(), this.range) && n.push("selected")),
            n
        },
        "fill": function() {
            {
                var n, i = new Date(this.viewDate),
                r = i.getUTCFullYear(),
                s = i.getUTCMonth(),
                a = this.o.startDate !== -1 / 0 ? this.o.startDate.getUTCFullYear() : -1 / 0,
                o = this.o.startDate !== -1 / 0 ? this.o.startDate.getUTCMonth() : -1 / 0,
                l = 1 / 0 !== this.o.endDate ? this.o.endDate.getUTCFullYear() : 1 / 0,
                u = 1 / 0 !== this.o.endDate ? this.o.endDate.getUTCMonth() : 1 / 0;
                this.date && this.date.valueOf()
            }
            this.picker.find(".datepicker-days thead th.datepicker-switch").text(c[this.o.language].months[s] + " " + r),
            this.picker.find("tfoot th.today").text(c[this.o.language].today).toggle(this.o.todayBtn !== !1),
            this.picker.find("tfoot th.clear").text(c[this.o.language].clear).toggle(this.o.clearBtn !== !1),
            this.updateNavArrows(),
            this.fillMonths();
            var d = e(r, s - 1, 28, 0, 0, 0, 0),
            f = h.getDaysInMonth(d.getUTCFullYear(), d.getUTCMonth());
            d.setUTCDate(f),
            d.setUTCDate(f - (d.getUTCDay() - this.o.weekStart + 7) % 7);
            var p = new Date(d);
            p.setUTCDate(p.getUTCDate() + 42),
            p = p.valueOf();
            for (var m, g = []; d.valueOf() < p;) {
                if (d.getUTCDay() == this.o.weekStart && (g.push("<tr>"), this.o.calendarWeeks)) {
                    var v = new Date( + d + (this.o.weekStart - d.getUTCDay() - 7) % 7 * 864e5),
                    y = new Date( + v + (11 - v.getUTCDay()) % 7 * 864e5),
                    b = new Date( + (b = e(y.getUTCFullYear(), 0, 1)) + (11 - b.getUTCDay()) % 7 * 864e5),
                    w = (y - b) / 864e5 / 7 + 1;
                    g.push('<td class="cw">' + w + "</td>")
                }
                m = this.getClassNames(d),
                m.push("day");
                var $ = this.o.beforeShowDay(d);
                void 0 === $ ? $ = {}: "boolean" == typeof $ ? $ = {
                    "enabled": $
                }: "string" == typeof $ && ($ = {
                    "classes": $
                }),
                $.enabled === !1 && m.push("disabled"),
                $.classes && (m = m.concat($.classes.split(/\s+/))),
                $.tooltip && (n = $.tooltip),
                m = t.unique(m),
                g.push('<td class="' + m.join(" ") + '"' + (n ? ' title="' + n + '"': "") + ">" + d.getUTCDate() + "</td>"),
                d.getUTCDay() == this.o.weekEnd && g.push("</tr>"),
                d.setUTCDate(d.getUTCDate() + 1)
            }
            this.picker.find(".datepicker-days tbody").empty().append(g.join(""));
            var x = this.date && this.date.getUTCFullYear(),
            C = this.picker.find(".datepicker-months").find("th:eq(1)").text(r).end().find("span").removeClass("active");
            x && x == r && C.eq(this.date.getUTCMonth()).addClass("active"),
            (a > r || r > l) && C.addClass("disabled"),
            r == a && C.slice(0, o).addClass("disabled"),
            r == l && C.slice(u + 1).addClass("disabled"),
            g = "",
            r = 10 * parseInt(r / 10, 10);
            var k = this.picker.find(".datepicker-years").find("th:eq(1)").text(r + "-" + (r + 9)).end().find("td");
            r -= 1;
            for (var T = -1; 11 > T; T++) g += '<span class="year' + ( - 1 == T ? " old": 10 == T ? " new": "") + (x == r ? " active": "") + (a > r || r > l ? " disabled": "") + '">' + r + "</span>",
            r += 1;
            k.html(g)
        },
        "updateNavArrows": function() {
            if (this._allow_update) {
                var t = new Date(this.viewDate),
                e = t.getUTCFullYear(),
                n = t.getUTCMonth();
                switch (this.viewMode) {
                case 0:
                    this.picker.find(".prev").css(this.o.startDate !== -1 / 0 && e <= this.o.startDate.getUTCFullYear() && n <= this.o.startDate.getUTCMonth() ? {
                        "visibility": "hidden"
                    }: {
                        "visibility": "visible"
                    }),
                    this.picker.find(".next").css(1 / 0 !== this.o.endDate && e >= this.o.endDate.getUTCFullYear() && n >= this.o.endDate.getUTCMonth() ? {
                        "visibility": "hidden"
                    }: {
                        "visibility": "visible"
                    });
                    break;
                case 1:
                case 2:
                    this.picker.find(".prev").css(this.o.startDate !== -1 / 0 && e <= this.o.startDate.getUTCFullYear() ? {
                        "visibility": "hidden"
                    }: {
                        "visibility": "visible"
                    }),
                    this.picker.find(".next").css(1 / 0 !== this.o.endDate && e >= this.o.endDate.getUTCFullYear() ? {
                        "visibility": "hidden"
                    }: {
                        "visibility": "visible"
                    })
                }
            }
        },
        "click": function(n) {
            n.preventDefault();
            var i = t(n.target).closest("span, td, th");
            if (1 == i.length) switch (i[0].nodeName.toLowerCase()) {
            case "th":
                switch (i[0].className) {
                case "datepicker-switch":
                    this.showMode(1);
                    break;
                case "prev":
                case "next":
                    var r = h.modes[this.viewMode].navStep * ("prev" == i[0].className ? -1 : 1);
                    switch (this.viewMode) {
                    case 0:
                        this.viewDate = this.moveMonth(this.viewDate, r);
                        break;
                    case 1:
                    case 2:
                        this.viewDate = this.moveYear(this.viewDate, r)
                    }
                    this.fill();
                    break;
                case "today":
                    var s = new Date;
                    s = e(s.getFullYear(), s.getMonth(), s.getDate(), 0, 0, 0),
                    this.showMode( - 2);
                    var a = "linked" == this.o.todayBtn ? null: "view";
                    this._setDate(s, a);
                    break;
                case "clear":
                    var o;
                    this.isInput ? o = this.element: this.component && (o = this.element.find("input")),
                    o && o.val("").change(),
                    this._trigger("changeDate"),
                    this.update(),
                    this.o.autoclose && this.hide()
                }
                break;
            case "span":
                if (!i.is(".disabled")) {
                    if (this.viewDate.setUTCDate(1), i.is(".month")) {
                        var l = 1,
                        u = i.parent().find("span").index(i),
                        c = this.viewDate.getUTCFullYear();
                        this.viewDate.setUTCMonth(u),
                        this._trigger("changeMonth", this.viewDate),
                        1 === this.o.minViewMode && this._setDate(e(c, u, l, 0, 0, 0, 0))
                    } else {
                        var c = parseInt(i.text(), 10) || 0,
                        l = 1,
                        u = 0;
                        this.viewDate.setUTCFullYear(c),
                        this._trigger("changeYear", this.viewDate),
                        2 === this.o.minViewMode && this._setDate(e(c, u, l, 0, 0, 0, 0))
                    }
                    this.showMode( - 1),
                    this.fill()
                }
                break;
            case "td":
                if (i.is(".day") && !i.is(".disabled")) {
                    var l = parseInt(i.text(), 10) || 1,
                    c = this.viewDate.getUTCFullYear(),
                    u = this.viewDate.getUTCMonth();
                    i.is(".old") ? 0 === u ? (u = 11, c -= 1) : u -= 1 : i.is(".new") && (11 == u ? (u = 0, c += 1) : u += 1),
                    this._setDate(e(c, u, l, 0, 0, 0, 0))
                }
            }
        },
        "_setDate": function(t, e) {
            e && "date" != e || (this.date = new Date(t)),
            e && "view" != e || (this.viewDate = new Date(t)),
            this.fill(),
            this.setValue(),
            this._trigger("changeDate");
            var n;
            this.isInput ? n = this.element: this.component && (n = this.element.find("input")),
            n && (n.change(), !this.o.autoclose || e && "date" != e || this.hide())
        },
        "moveMonth": function(t, e) {
            if (!e) return t;
            var n, i, r = new Date(t.valueOf()),
            s = r.getUTCDate(),
            a = r.getUTCMonth(),
            o = Math.abs(e);
            if (e = e > 0 ? 1 : -1, 1 == o) i = -1 == e ?
            function() {
                return r.getUTCMonth() == a
            }: function() {
                return r.getUTCMonth() != n
            },
            n = a + e,
            r.setUTCMonth(n),
            (0 > n || n > 11) && (n = (n + 12) % 12);
            else {
                for (var l = 0; o > l; l++) r = this.moveMonth(r, e);
                n = r.getUTCMonth(),
                r.setUTCDate(s),
                i = function() {
                    return n != r.getUTCMonth()
                }
            }
            for (; i();) r.setUTCDate(--s),
            r.setUTCMonth(n);
            return r
        },
        "moveYear": function(t, e) {
            return this.moveMonth(t, 12 * e)
        },
        "dateWithinRange": function(t) {
            return t >= this.o.startDate && t <= this.o.endDate
        },
        "keydown": function(t) {
            if (this.picker.is(":not(:visible)")) return void(27 == t.keyCode && this.show());
            var e, n, i, r = !1;
            switch (t.keyCode) {
            case 27:
                this.hide(),
                t.preventDefault();
                break;
            case 37:
            case 39:
                if (!this.o.keyboardNavigation) break;
                e = 37 == t.keyCode ? -1 : 1,
                t.ctrlKey ? (n = this.moveYear(this.date, e), i = this.moveYear(this.viewDate, e)) : t.shiftKey ? (n = this.moveMonth(this.date, e), i = this.moveMonth(this.viewDate, e)) : (n = new Date(this.date), n.setUTCDate(this.date.getUTCDate() + e), i = new Date(this.viewDate), i.setUTCDate(this.viewDate.getUTCDate() + e)),
                this.dateWithinRange(n) && (this.date = n, this.viewDate = i, this.setValue(), this.update(), t.preventDefault(), r = !0);
                break;
            case 38:
            case 40:
                if (!this.o.keyboardNavigation) break;
                e = 38 == t.keyCode ? -1 : 1,
                t.ctrlKey ? (n = this.moveYear(this.date, e), i = this.moveYear(this.viewDate, e)) : t.shiftKey ? (n = this.moveMonth(this.date, e), i = this.moveMonth(this.viewDate, e)) : (n = new Date(this.date), n.setUTCDate(this.date.getUTCDate() + 7 * e), i = new Date(this.viewDate), i.setUTCDate(this.viewDate.getUTCDate() + 7 * e)),
                this.dateWithinRange(n) && (this.date = n, this.viewDate = i, this.setValue(), this.update(), t.preventDefault(), r = !0);
                break;
            case 13:
                this.hide(),
                t.preventDefault();
                break;
            case 9:
                this.hide()
            }
            if (r) {
                this._trigger("changeDate");
                var s;
                this.isInput ? s = this.element: this.component && (s = this.element.find("input")),
                s && s.change()
            }
        },
        "showMode": function(t) {
            t && (this.viewMode = Math.max(this.o.minViewMode, Math.min(2, this.viewMode + t))),
            this.picker.find(">div").hide().filter(".datepicker-" + h.modes[this.viewMode].clsName).css("display", "block"),
            this.updateNavArrows()
        }
    };
    var s = function(e, n) {
        this.element = t(e),
        this.inputs = t.map(n.inputs,
        function(t) {
            return t.jquery ? t[0] : t
        }),
        delete n.inputs,
        t(this.inputs).datepicker(n).bind("changeDate", t.proxy(this.dateUpdated, this)),
        this.pickers = t.map(this.inputs,
        function(e) {
            return t(e).data("datepicker")
        }),
        this.updateDates()
    };
    s.prototype = {
        "updateDates": function() {
            this.dates = t.map(this.pickers,
            function(t) {
                return t.date
            }),
            this.updateRanges()
        },
        "updateRanges": function() {
            var e = t.map(this.dates,
            function(t) {
                return t.valueOf()
            });
            t.each(this.pickers,
            function(t, n) {
                n.setRange(e)
            })
        },
        "dateUpdated": function(e) {
            var n = t(e.target).data("datepicker"),
            i = n.getUTCDate(),
            r = t.inArray(e.target, this.inputs),
            s = this.inputs.length;
            if ( - 1 != r) {
                if (i < this.dates[r]) for (; r >= 0 && i < this.dates[r];) this.pickers[r--].setUTCDate(i);
                else if (i > this.dates[r]) for (; s > r && i > this.dates[r];) this.pickers[r++].setUTCDate(i);
                this.updateDates()
            }
        },
        "remove": function() {
            t.map(this.pickers,
            function(t) {
                t.remove()
            }),
            delete this.element.data().datepicker
        }
    };
    var a = t.fn.datepicker,
    o = t.fn.datepicker = function(e) {
        var a = Array.apply(null, arguments);
        a.shift();
        var o;
        return this.each(function() {
            var u = t(this),
            c = u.data("datepicker"),
            h = "object" == typeof e && e;
            if (!c) {
                var d = n(this, "date"),
                f = t.extend({},
                l, d, h),
                p = i(f.language),
                m = t.extend({},
                l, p, d, h);
                if (u.is(".input-daterange") || m.inputs) {
                    var g = {
                        "inputs": m.inputs || u.find("input").toArray()
                    };
                    u.data("datepicker", c = new s(this, t.extend(m, g)))
                } else u.data("datepicker", c = new r(this, m))
            }
            return "string" == typeof e && "function" == typeof c[e] && (o = c[e].apply(c, a), void 0 !== o) ? !1 : void 0
        }),
        void 0 !== o ? o: this
    },
    l = t.fn.datepicker.defaults = {
        "autoclose": !1,
        "beforeShowDay": t.noop,
        "calendarWeeks": !1,
        "clearBtn": !1,
        "daysOfWeekDisabled": [],
        "endDate": 1 / 0,
        "forceParse": !0,
        "format": "mm/dd/yyyy",
        "keyboardNavigation": !0,
        "language": "en",
        "minViewMode": 0,
        "rtl": !1,
        "startDate": -1 / 0,
        "startView": 0,
        "todayBtn": !1,
        "todayHighlight": !1,
        "weekStart": 0
    },
    u = t.fn.datepicker.locale_opts = ["format", "rtl", "weekStart"];
    t.fn.datepicker.Constructor = r;
    var c = t.fn.datepicker.dates = {
        "en": {
            "days": ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
            "daysShort": ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            "daysMin": ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
            "months": ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            "monthsShort": ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            "today": "Today",
            "clear": "Clear"
        }
    },
    h = {
        "modes": [{
            "clsName": "days",
            "navFnc": "Month",
            "navStep": 1
        },
        {
            "clsName": "months",
            "navFnc": "FullYear",
            "navStep": 1
        },
        {
            "clsName": "years",
            "navFnc": "FullYear",
            "navStep": 10
        }],
        "isLeapYear": function(t) {
            return t % 4 === 0 && t % 100 !== 0 || t % 400 === 0
        },
        "getDaysInMonth": function(t, e) {
            return [31, h.isLeapYear(t) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][e]
        },
        "validParts": /dd?|DD?|mm?|MM?|yy(?:yy)?/g,
        "nonpunctuation": /[^ -\/:-@\[\u3400-\u9fff-`{-~\t\n\r]+/g,
        "parseFormat": function(t) {
            var e = t.replace(this.validParts, "\x00").split("\x00"),
            n = t.match(this.validParts);
            if (!e || !e.length || !n || 0 === n.length) throw new Error("Invalid date format.");
            return {
                "separators": e,
                "parts": n
            }
        },
        "parseDate": function(n, i, s) {
            if (n instanceof Date) return n;
            if ("string" == typeof i && (i = h.parseFormat(i)), /^[\-+]\d+[dmwy]([\s,]+[\-+]\d+[dmwy])*$/.test(n)) {
                var a, o, l = /([\-+]\d+)([dmwy])/,
                u = n.match(/([\-+]\d+)([dmwy])/g);
                n = new Date;
                for (var d = 0; d < u.length; d++) switch (a = l.exec(u[d]), o = parseInt(a[1]), a[2]) {
                case "d":
                    n.setUTCDate(n.getUTCDate() + o);
                    break;
                case "m":
                    n = r.prototype.moveMonth.call(r.prototype, n, o);
                    break;
                case "w":
                    n.setUTCDate(n.getUTCDate() + 7 * o);
                    break;
                case "y":
                    n = r.prototype.moveYear.call(r.prototype, n, o)
                }
                return e(n.getUTCFullYear(), n.getUTCMonth(), n.getUTCDate(), 0, 0, 0)
            }
            var f, p, a, u = n && n.match(this.nonpunctuation) || [],
            n = new Date,
            m = {},
            g = ["yyyy", "yy", "M", "MM", "m", "mm", "d", "dd"],
            v = {
                "yyyy": function(t, e) {
                    return t.setUTCFullYear(e)
                },
                "yy": function(t, e) {
                    return t.setUTCFullYear(2e3 + e)
                },
                "m": function(t, e) {
                    for (e -= 1; 0 > e;) e += 12;
                    for (e %= 12, t.setUTCMonth(e); t.getUTCMonth() != e;) t.setUTCDate(t.getUTCDate() - 1);
                    return t
                },
                "d": function(t, e) {
                    return t.setUTCDate(e)
                }
            };
            v.M = v.MM = v.mm = v.m,
            v.dd = v.d,
            n = e(n.getFullYear(), n.getMonth(), n.getDate(), 0, 0, 0);
            var y = i.parts.slice();
            if (u.length != y.length && (y = t(y).filter(function(e, n) {
                return - 1 !== t.inArray(n, g)
            }).toArray()), u.length == y.length) {
                for (var d = 0,
                b = y.length; b > d; d++) {
                    if (f = parseInt(u[d], 10), a = y[d], isNaN(f)) switch (a) {
                    case "MM":
                        p = t(c[s].months).filter(function() {
                            var t = this.slice(0, u[d].length),
                            e = u[d].slice(0, t.length);
                            return t == e
                        }),
                        f = t.inArray(p[0], c[s].months) + 1;
                        break;
                    case "M":
                        p = t(c[s].monthsShort).filter(function() {
                            var t = this.slice(0, u[d].length),
                            e = u[d].slice(0, t.length);
                            return t == e
                        }),
                        f = t.inArray(p[0], c[s].monthsShort) + 1
                    }
                    m[a] = f
                }
                for (var w, d = 0; d < g.length; d++) w = g[d],
                w in m && !isNaN(m[w]) && v[w](n, m[w])
            }
            return n
        },
        "formatDate": function(e, n, i) {
            "string" == typeof n && (n = h.parseFormat(n));
            var r = {
                "d": e.getUTCDate(),
                "D": c[i].daysShort[e.getUTCDay()],
                "DD": c[i].days[e.getUTCDay()],
                "m": e.getUTCMonth() + 1,
                "M": c[i].monthsShort[e.getUTCMonth()],
                "MM": c[i].months[e.getUTCMonth()],
                "yy": e.getUTCFullYear().toString().substring(2),
                "yyyy": e.getUTCFullYear()
            };
            r.dd = (r.d < 10 ? "0": "") + r.d,
            r.mm = (r.m < 10 ? "0": "") + r.m;
            for (var e = [], s = t.extend([], n.separators), a = 0, o = n.parts.length; o >= a; a++) s.length && e.push(s.shift()),
            e.push(r[n.parts[a]]);
            return e.join("")
        },
        "headTemplate": '<thead><tr><th class="prev"><i class="icon-arrow-left"/></th><th colspan="5" class="datepicker-switch"></th><th class="next"><i class="icon-arrow-right"/></th></tr></thead>',
        "contTemplate": '<tbody><tr><td colspan="7"></td></tr></tbody>',
        "footTemplate": '<tfoot><tr><th colspan="7" class="today"></th></tr><tr><th colspan="7" class="clear"></th></tr></tfoot>'
    };
    h.template = '<div class="datepicker"><div class="datepicker-days"><table class=" table-condensed">' + h.headTemplate + "<tbody></tbody>" + h.footTemplate + '</table></div><div class="datepicker-months"><table class="table-condensed">' + h.headTemplate + h.contTemplate + h.footTemplate + '</table></div><div class="datepicker-years"><table class="table-condensed">' + h.headTemplate + h.contTemplate + h.footTemplate + "</table></div></div>",
    t.fn.datepicker.DPGlobal = h,
    t.fn.datepicker.noConflict = function() {
        return t.fn.datepicker = a,
        this
    },
    t(document).on("focus.datepicker.data-api click.datepicker.data-api", '[data-provide="datepicker"]',
    function(e) {
        var n = t(this);
        n.data("datepicker") || (e.preventDefault(), o.call(n, "show"))
    }),
    t(function() {
        o.call(t('[data-provide="datepicker-inline"]'))
    })
} (window.jQuery),
function(t) {
    "use strict";
    t.fn.bdatepicker = t.fn.datepicker.noConflict(),
    t.fn.datepicker || (t.fn.datepicker = t.fn.bdatepicker);
    var e = function(t) {
        this.init("date", t, e.defaults),
        this.initPicker(t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.abstractinput),
    t.extend(e.prototype, {
        "initPicker": function(e, n) {
            this.options.viewformat || (this.options.viewformat = this.options.format),
            e.datepicker = t.fn.editableutils.tryParseJson(e.datepicker, !0),
            this.options.datepicker = t.extend({},
            n.datepicker, e.datepicker, {
                "format": this.options.viewformat
            }),
            this.options.datepicker.language = this.options.datepicker.language || "en",
            this.dpg = t.fn.bdatepicker.DPGlobal,
            this.parsedFormat = this.dpg.parseFormat(this.options.format),
            this.parsedViewFormat = this.dpg.parseFormat(this.options.viewformat)
        },
        "render": function() {
            this.$input.bdatepicker(this.options.datepicker),
            this.options.clear && (this.$clear = t('<a href="#"></a>').html(this.options.clear).click(t.proxy(function(t) {
                t.preventDefault(),
                t.stopPropagation(),
                this.clear()
            },
            this)), this.$tpl.parent().append(t('<div class="editable-clear">').append(this.$clear)))
        },
        "value2html": function(t, n) {
            var i = t ? this.dpg.formatDate(t, this.parsedViewFormat, this.options.datepicker.language) : "";
            e.superclass.value2html.call(this, i, n)
        },
        "html2value": function(t) {
            return this.parseDate(t, this.parsedViewFormat)
        },
        "value2str": function(t) {
            return t ? this.dpg.formatDate(t, this.parsedFormat, this.options.datepicker.language) : ""
        },
        "str2value": function(t) {
            return this.parseDate(t, this.parsedFormat)
        },
        "value2submit": function(t) {
            return this.value2str(t)
        },
        "value2input": function(t) {
            this.$input.bdatepicker("update", t)
        },
        "input2value": function() {
            return this.$input.data("datepicker").date
        },
        "activate": function() {},
        "clear": function() {
            this.$input.data("datepicker").date = null,
            this.$input.find(".active").removeClass("active"),
            this.options.showbuttons || this.$input.closest("form").submit()
        },
        "autosubmit": function() {
            this.$input.on("mouseup", ".day",
            function(e) {
                if (!t(e.currentTarget).is(".old") && !t(e.currentTarget).is(".new")) {
                    var n = t(this).closest("form");
                    setTimeout(function() {
                        n.submit()
                    },
                    200)
                }
            })
        },
        "parseDate": function(t, e) {
            var n, i = null;
            return t && (i = this.dpg.parseDate(t, e, this.options.datepicker.language), "string" == typeof t && (n = this.dpg.formatDate(i, e, this.options.datepicker.language), t !== n && (i = null))),
            i
        }
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.abstractinput.defaults, {
        "tpl": '<div class="editable-date well"></div>',
        "inputclass": null,
        "format": "yyyy-mm-dd",
        "viewformat": null,
        "datepicker": {
            "weekStart": 0,
            "startView": 0,
            "minViewMode": 0,
            "autoclose": !1
        },
        "clear": "&times; clear"
    }),
    t.fn.editabletypes.date = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t) {
        this.init("datefield", t, e.defaults),
        this.initPicker(t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.date),
    t.extend(e.prototype, {
        "render": function() {
            this.$input = this.$tpl.find("input"),
            this.setClass(),
            this.setAttr("placeholder"),
            this.$tpl.bdatepicker(this.options.datepicker),
            this.$input.off("focus keydown"),
            this.$input.keyup(t.proxy(function() {
                this.$tpl.removeData("date"),
                this.$tpl.bdatepicker("update")
            },
            this))
        },
        "value2input": function(t) {
            this.$input.val(t ? this.dpg.formatDate(t, this.parsedViewFormat, this.options.datepicker.language) : ""),
            this.$tpl.bdatepicker("update")
        },
        "input2value": function() {
            return this.html2value(this.$input.val())
        },
        "activate": function() {
            t.fn.editabletypes.text.prototype.activate.call(this)
        },
        "autosubmit": function() {}
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.date.defaults, {
        "tpl": '<div class="input-append date"><input type="text"/><span class="add-on"><i class="icon-th"></i></span></div>',
        "inputclass": "input-small",
        "datepicker": {
            "weekStart": 0,
            "startView": 0,
            "minViewMode": 0,
            "autoclose": !0
        }
    }),
    t.fn.editabletypes.datefield = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t) {
        this.init("datetime", t, e.defaults),
        this.initPicker(t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.abstractinput),
    t.extend(e.prototype, {
        "initPicker": function(e, n) {
            this.options.viewformat || (this.options.viewformat = this.options.format),
            e.datetimepicker = t.fn.editableutils.tryParseJson(e.datetimepicker, !0),
            this.options.datetimepicker = t.extend({},
            n.datetimepicker, e.datetimepicker, {
                "format": this.options.viewformat
            }),
            this.options.datetimepicker.language = this.options.datetimepicker.language || "en",
            this.dpg = t.fn.datetimepicker.DPGlobal,
            this.parsedFormat = this.dpg.parseFormat(this.options.format, this.options.formatType),
            this.parsedViewFormat = this.dpg.parseFormat(this.options.viewformat, this.options.formatType)
        },
        "render": function() {
            this.$input.datetimepicker(this.options.datetimepicker),
            this.$input.on("changeMode",
            function() {
                var e = t(this).closest("form").parent();
                setTimeout(function() {
                    e.triggerHandler("resize")
                },
                0)
            }),
            this.options.clear && (this.$clear = t('<a href="#"></a>').html(this.options.clear).click(t.proxy(function(t) {
                t.preventDefault(),
                t.stopPropagation(),
                this.clear()
            },
            this)), this.$tpl.parent().append(t('<div class="editable-clear">').append(this.$clear)))
        },
        "value2html": function(t, n) {
            var i = t ? this.dpg.formatDate(this.toUTC(t), this.parsedViewFormat, this.options.datetimepicker.language, this.options.formatType) : "";
            return n ? void e.superclass.value2html.call(this, i, n) : i
        },
        "html2value": function(t) {
            var e = this.parseDate(t, this.parsedViewFormat);
            return e ? this.fromUTC(e) : null
        },
        "value2str": function(t) {
            return t ? this.dpg.formatDate(this.toUTC(t), this.parsedFormat, this.options.datetimepicker.language, this.options.formatType) : ""
        },
        "str2value": function(t) {
            var e = this.parseDate(t, this.parsedFormat);
            return e ? this.fromUTC(e) : null
        },
        "value2submit": function(t) {
            return this.value2str(t)
        },
        "value2input": function(t) {
            t && this.$input.data("datetimepicker").setDate(t)
        },
        "input2value": function() {
            var t = this.$input.data("datetimepicker");
            return t.date ? t.getDate() : null
        },
        "activate": function() {},
        "clear": function() {
            this.$input.data("datetimepicker").date = null,
            this.$input.find(".active").removeClass("active"),
            this.options.showbuttons || this.$input.closest("form").submit()
        },
        "autosubmit": function() {
            this.$input.on("mouseup", ".minute",
            function() {
                var e = t(this).closest("form");
                setTimeout(function() {
                    e.submit()
                },
                200)
            })
        },
        "toUTC": function(t) {
            return t ? new Date(t.valueOf() - 6e4 * t.getTimezoneOffset()) : t
        },
        "fromUTC": function(t) {
            return t ? new Date(t.valueOf() + 6e4 * t.getTimezoneOffset()) : t
        },
        "parseDate": function(t, e) {
            var n, i = null;
            return t && (i = this.dpg.parseDate(t, e, this.options.datetimepicker.language, this.options.formatType), "string" == typeof t && (n = this.dpg.formatDate(i, e, this.options.datetimepicker.language, this.options.formatType), t !== n && (i = null))),
            i
        }
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.abstractinput.defaults, {
        "tpl": '<div class="editable-date well"></div>',
        "inputclass": null,
        "format": "yyyy-mm-dd hh:ii",
        "formatType": "standard",
        "viewformat": null,
        "datetimepicker": {
            "todayHighlight": !1,
            "autoclose": !1
        },
        "clear": "&times; clear"
    }),
    t.fn.editabletypes.datetime = e
} (window.jQuery),
function(t) {
    "use strict";
    var e = function(t) {
        this.init("datetimefield", t, e.defaults),
        this.initPicker(t, e.defaults)
    };
    t.fn.editableutils.inherit(e, t.fn.editabletypes.datetime),
    t.extend(e.prototype, {
        "render": function() {
            this.$input = this.$tpl.find("input"),
            this.setClass(),
            this.setAttr("placeholder"),
            this.$tpl.datetimepicker(this.options.datetimepicker),
            this.$input.off("focus keydown"),
            this.$input.keyup(t.proxy(function() {
                this.$tpl.removeData("date"),
                this.$tpl.datetimepicker("update")
            },
            this))
        },
        "value2input": function(t) {
            this.$input.val(this.value2html(t)),
            this.$tpl.datetimepicker("update")
        },
        "input2value": function() {
            return this.html2value(this.$input.val())
        },
        "activate": function() {
            t.fn.editabletypes.text.prototype.activate.call(this)
        },
        "autosubmit": function() {}
    }),
    e.defaults = t.extend({},
    t.fn.editabletypes.datetime.defaults, {
        "tpl": '<div class="input-append date"><input type="text"/><span class="add-on"><i class="icon-th"></i></span></div>',
        "inputclass": "input-medium",
        "datetimepicker": {
            "todayHighlight": !1,
            "autoclose": !0
        }
    }),
    t.fn.editabletypes.datetimefield = e
} (window.jQuery),
function(t) {
    "undefined" == typeof t.fn.each2 && t.extend(t.fn, {
        "each2": function(e) {
            for (var n = t([0]), i = -1, r = this.length; ++i < r && (n.context = n[0] = this[i]) && e.call(n[0], i, n) !== !1;);
            return this
        }
    })
} (jQuery),
function(t, e) {
    "use strict";
    function n(e) {
        var n = t(document.createTextNode(""));
        e.before(n),
        n.before(e),
        n.remove()
    }
    function i(t) {
        function e(t) {
            return R[t] || t
        }
        return t.replace(/[^\u0000-\u007E]/g, e)
    }
    function r(t, e) {
        for (var n = 0,
        i = e.length; i > n; n += 1) if (a(t, e[n])) return n;
        return - 1
    }
    function s() {
        var e = t(H);
        e.appendTo("body");
        var n = {
            "width": e.width() - e[0].clientWidth,
            "height": e.height() - e[0].clientHeight
        };
        return e.remove(),
        n
    }
    function a(t, n) {
        return t === n ? !0 : t === e || n === e ? !1 : null === t || null === n ? !1 : t.constructor === String ? t + "" == n + "": n.constructor === String ? n + "" == t + "": !1
    }
    function o(e, n) {
        var i, r, s;
        if (null === e || e.length < 1) return [];
        for (i = e.split(n), r = 0, s = i.length; s > r; r += 1) i[r] = t.trim(i[r]);
        return i
    }
    function l(t) {
        return t.outerWidth(!1) - t.width()
    }
    function u(n) {
        var i = "keyup-change-value";
        n.on("keydown",
        function() {
            t.data(n, i) === e && t.data(n, i, n.val())
        }),
        n.on("keyup",
        function() {
            var r = t.data(n, i);
            r !== e && n.val() !== r && (t.removeData(n, i), n.trigger("keyup-change"))
        })
    }
    function c(n) {
        n.on("mousemove",
        function(n) {
            var i = L; (i === e || i.x !== n.pageX || i.y !== n.pageY) && t(n.target).trigger("mousemove-filtered", n)
        })
    }
    function h(t, n, i) {
        i = i || e;
        var r;
        return function() {
            var e = arguments;
            window.clearTimeout(r),
            r = window.setTimeout(function() {
                n.apply(i, e)
            },
            t)
        }
    }
    function d(t, e) {
        var n = h(t,
        function(t) {
            e.trigger("scroll-debounced", t)
        });
        e.on("scroll",
        function(t) {
            r(t.target, e.get()) >= 0 && n(t)
        })
    }
    function f(t) {
        t[0] !== document.activeElement && window.setTimeout(function() {
            var e, n = t[0],
            i = t.val().length;
            t.focus();
            var r = n.offsetWidth > 0 || n.offsetHeight > 0;
            r && n === document.activeElement && (n.setSelectionRange ? n.setSelectionRange(i, i) : n.createTextRange && (e = n.createTextRange(), e.collapse(!1), e.select()))
        },
        0)
    }
    function p(e) {
        e = t(e)[0];
        var n = 0,
        i = 0;
        if ("selectionStart" in e) n = e.selectionStart,
        i = e.selectionEnd - n;
        else if ("selection" in document) {
            e.focus();
            var r = document.selection.createRange();
            i = document.selection.createRange().text.length,
            r.moveStart("character", -e.value.length),
            n = r.text.length - i
        }
        return {
            "offset": n,
            "length": i
        }
    }
    function m(t) {
        t.preventDefault(),
        t.stopPropagation()
    }
    function g(t) {
        t.preventDefault(),
        t.stopImmediatePropagation()
    }
    function v(e) {
        if (!j) {
            var n = e[0].currentStyle || window.getComputedStyle(e[0], null);
            j = t(document.createElement("div")).css({
                "position": "absolute",
                "left": "-10000px",
                "top": "-10000px",
                "display": "none",
                "fontSize": n.fontSize,
                "fontFamily": n.fontFamily,
                "fontStyle": n.fontStyle,
                "fontWeight": n.fontWeight,
                "letterSpacing": n.letterSpacing,
                "textTransform": n.textTransform,
                "whiteSpace": "nowrap"
            }),
            j.attr("class", "select2-sizer"),
            t("body").append(j)
        }
        return j.text(e.val()),
        j.width()
    }
    function y(e, n, i) {
        var r, s, a = [];
        r = e.attr("class"),
        r && (r = "" + r, t(r.split(" ")).each2(function() {
            0 === this.indexOf("select2-") && a.push(this)
        })),
        r = n.attr("class"),
        r && (r = "" + r, t(r.split(" ")).each2(function() {
            0 !== this.indexOf("select2-") && (s = i(this), s && a.push(s))
        })),
        e.attr("class", a.join(" "))
    }
    function b(t, e, n, r) {
        var s = i(t.toUpperCase()).indexOf(i(e.toUpperCase())),
        a = e.length;
        return 0 > s ? void n.push(r(t)) : (n.push(r(t.substring(0, s))), n.push("<span class='select2-match'>"), n.push(r(t.substring(s, s + a))), n.push("</span>"), void n.push(r(t.substring(s + a, t.length))))
    }
    function w(t) {
        var e = {
            "\\": "&#92;",
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#39;",
            "/": "&#47;"
        };
        return String(t).replace(/[&<>"'\/\\]/g,
        function(t) {
            return e[t]
        })
    }
    function $(n) {
        var i, r = null,
        s = n.quietMillis || 100,
        a = n.url,
        o = this;
        return function(l) {
            window.clearTimeout(i),
            i = window.setTimeout(function() {
                var i = n.data,
                s = a,
                u = n.transport || t.fn.select2.ajaxDefaults.transport,
                c = {
                    "type": n.type || "GET",
                    "cache": n.cache || !1,
                    "jsonpCallback": n.jsonpCallback || e,
                    "dataType": n.dataType || "json"
                },
                h = t.extend({},
                t.fn.select2.ajaxDefaults.params, c);
                i = i ? i.call(o, l.term, l.page, l.context) : null,
                s = "function" == typeof s ? s.call(o, l.term, l.page, l.context) : s,
                r && "function" == typeof r.abort && r.abort(),
                n.params && (t.isFunction(n.params) ? t.extend(h, n.params.call(o)) : t.extend(h, n.params)),
                t.extend(h, {
                    "url": s,
                    "dataType": n.dataType,
                    "data": i,
                    "success": function(t) {
                        var e = n.results(t, l.page);
                        l.callback(e)
                    }
                }),
                r = u.call(o, h)
            },
            s)
        }
    }
    function x(e) {
        var n, i, r = e,
        s = function(t) {
            return "" + t.text
        };
        t.isArray(r) && (i = r, r = {
            "results": i
        }),
        t.isFunction(r) === !1 && (i = r, r = function() {
            return i
        });
        var a = r();
        return a.text && (s = a.text, t.isFunction(s) || (n = a.text, s = function(t) {
            return t[n]
        })),
        function(e) {
            var n, i = e.term,
            a = {
                "results": []
            };
            return "" === i ? void e.callback(r()) : (n = function(r, a) {
                var o, l;
                if (r = r[0], r.children) {
                    o = {};
                    for (l in r) r.hasOwnProperty(l) && (o[l] = r[l]);
                    o.children = [],
                    t(r.children).each2(function(t, e) {
                        n(e, o.children)
                    }),
                    (o.children.length || e.matcher(i, s(o), r)) && a.push(o)
                } else e.matcher(i, s(r), r) && a.push(r)
            },
            t(r().results).each2(function(t, e) {
                n(e, a.results)
            }), void e.callback(a))
        }
    }
    function C(n) {
        var i = t.isFunction(n);
        return function(r) {
            var s = r.term,
            a = {
                "results": []
            },
            o = i ? n(r) : n;
            t.isArray(o) && (t(o).each(function() {
                var t = this.text !== e,
                n = t ? this.text: this; ("" === s || r.matcher(s, n)) && a.results.push(t ? this: {
                    "id": this,
                    "text": this
                })
            }), r.callback(a))
        }
    }
    function k(e, n) {
        if (t.isFunction(e)) return ! 0;
        if (!e) return ! 1;
        if ("string" == typeof e) return ! 0;
        throw new Error(n + " must be a string, function, or falsy value")
    }
    function T(e) {
        if (t.isFunction(e)) {
            var n = Array.prototype.slice.call(arguments, 1);
            return e.apply(null, n)
        }
        return e
    }
    function S(e) {
        var n = 0;
        return t.each(e,
        function(t, e) {
            e.children ? n += S(e.children) : n++
        }),
        n
    }
    function D(t, n, i, r) {
        var s, o, l, u, c, h = t,
        d = !1;
        if (!r.createSearchChoice || !r.tokenSeparators || r.tokenSeparators.length < 1) return e;
        for (;;) {
            for (o = -1, l = 0, u = r.tokenSeparators.length; u > l && (c = r.tokenSeparators[l], o = t.indexOf(c), !(o >= 0)); l++);
            if (0 > o) break;
            if (s = t.substring(0, o), t = t.substring(o + c.length), s.length > 0 && (s = r.createSearchChoice.call(this, s, n), s !== e && null !== s && r.id(s) !== e && null !== r.id(s))) {
                for (d = !1, l = 0, u = n.length; u > l; l++) if (a(r.id(s), r.id(n[l]))) {
                    d = !0;
                    break
                }
                d || i(s)
            }
        }
        return h !== t ? t: void 0
    }
    function E() {
        var t = this;
        Array.prototype.forEach.call(arguments,
        function(e) {
            t[e].remove(),
            t[e] = null
        })
    }
    function _(e, n) {
        var i = function() {};
        return i.prototype = new e,
        i.prototype.constructor = i,
        i.prototype.parent = e.prototype,
        i.prototype = t.extend(i.prototype, n),
        i
    }
    if (window.Select2 === e) {
        var M, A, O, F, N, j, P, I, L = {
            "x": 0,
            "y": 0
        },
        M = {
            "TAB": 9,
            "ENTER": 13,
            "ESC": 27,
            "SPACE": 32,
            "LEFT": 37,
            "UP": 38,
            "RIGHT": 39,
            "DOWN": 40,
            "SHIFT": 16,
            "CTRL": 17,
            "ALT": 18,
            "PAGE_UP": 33,
            "PAGE_DOWN": 34,
            "HOME": 36,
            "END": 35,
            "BACKSPACE": 8,
            "DELETE": 46,
            "isArrow": function(t) {
                switch (t = t.which ? t.which: t) {
                case M.LEFT:
                case M.RIGHT:
                case M.UP:
                case M.DOWN:
                    return ! 0
                }
                return ! 1
            },
            "isControl": function(t) {
                var e = t.which;
                switch (e) {
                case M.SHIFT:
                case M.CTRL:
                case M.ALT:
                    return ! 0
                }
                return t.metaKey ? !0 : !1
            },
            "isFunctionKey": function(t) {
                return t = t.which ? t.which: t,
                t >= 112 && 123 >= t
            }
        },
        H = "<div class='select2-measure-scrollbar'></div>",
        R = {
            "\u24b6": "A",
            "\uff21": "A",
            "\xc0": "A",
            "\xc1": "A",
            "\xc2": "A",
            "\u1ea6": "A",
            "\u1ea4": "A",
            "\u1eaa": "A",
            "\u1ea8": "A",
            "\xc3": "A",
            "\u0100": "A",
            "\u0102": "A",
            "\u1eb0": "A",
            "\u1eae": "A",
            "\u1eb4": "A",
            "\u1eb2": "A",
            "\u0226": "A",
            "\u01e0": "A",
            "\xc4": "A",
            "\u01de": "A",
            "\u1ea2": "A",
            "\xc5": "A",
            "\u01fa": "A",
            "\u01cd": "A",
            "\u0200": "A",
            "\u0202": "A",
            "\u1ea0": "A",
            "\u1eac": "A",
            "\u1eb6": "A",
            "\u1e00": "A",
            "\u0104": "A",
            "\u023a": "A",
            "\u2c6f": "A",
            "\ua732": "AA",
            "\xc6": "AE",
            "\u01fc": "AE",
            "\u01e2": "AE",
            "\ua734": "AO",
            "\ua736": "AU",
            "\ua738": "AV",
            "\ua73a": "AV",
            "\ua73c": "AY",
            "\u24b7": "B",
            "\uff22": "B",
            "\u1e02": "B",
            "\u1e04": "B",
            "\u1e06": "B",
            "\u0243": "B",
            "\u0182": "B",
            "\u0181": "B",
            "\u24b8": "C",
            "\uff23": "C",
            "\u0106": "C",
            "\u0108": "C",
            "\u010a": "C",
            "\u010c": "C",
            "\xc7": "C",
            "\u1e08": "C",
            "\u0187": "C",
            "\u023b": "C",
            "\ua73e": "C",
            "\u24b9": "D",
            "\uff24": "D",
            "\u1e0a": "D",
            "\u010e": "D",
            "\u1e0c": "D",
            "\u1e10": "D",
            "\u1e12": "D",
            "\u1e0e": "D",
            "\u0110": "D",
            "\u018b": "D",
            "\u018a": "D",
            "\u0189": "D",
            "\ua779": "D",
            "\u01f1": "DZ",
            "\u01c4": "DZ",
            "\u01f2": "Dz",
            "\u01c5": "Dz",
            "\u24ba": "E",
            "\uff25": "E",
            "\xc8": "E",
            "\xc9": "E",
            "\xca": "E",
            "\u1ec0": "E",
            "\u1ebe": "E",
            "\u1ec4": "E",
            "\u1ec2": "E",
            "\u1ebc": "E",
            "\u0112": "E",
            "\u1e14": "E",
            "\u1e16": "E",
            "\u0114": "E",
            "\u0116": "E",
            "\xcb": "E",
            "\u1eba": "E",
            "\u011a": "E",
            "\u0204": "E",
            "\u0206": "E",
            "\u1eb8": "E",
            "\u1ec6": "E",
            "\u0228": "E",
            "\u1e1c": "E",
            "\u0118": "E",
            "\u1e18": "E",
            "\u1e1a": "E",
            "\u0190": "E",
            "\u018e": "E",
            "\u24bb": "F",
            "\uff26": "F",
            "\u1e1e": "F",
            "\u0191": "F",
            "\ua77b": "F",
            "\u24bc": "G",
            "\uff27": "G",
            "\u01f4": "G",
            "\u011c": "G",
            "\u1e20": "G",
            "\u011e": "G",
            "\u0120": "G",
            "\u01e6": "G",
            "\u0122": "G",
            "\u01e4": "G",
            "\u0193": "G",
            "\ua7a0": "G",
            "\ua77d": "G",
            "\ua77e": "G",
            "\u24bd": "H",
            "\uff28": "H",
            "\u0124": "H",
            "\u1e22": "H",
            "\u1e26": "H",
            "\u021e": "H",
            "\u1e24": "H",
            "\u1e28": "H",
            "\u1e2a": "H",
            "\u0126": "H",
            "\u2c67": "H",
            "\u2c75": "H",
            "\ua78d": "H",
            "\u24be": "I",
            "\uff29": "I",
            "\xcc": "I",
            "\xcd": "I",
            "\xce": "I",
            "\u0128": "I",
            "\u012a": "I",
            "\u012c": "I",
            "\u0130": "I",
            "\xcf": "I",
            "\u1e2e": "I",
            "\u1ec8": "I",
            "\u01cf": "I",
            "\u0208": "I",
            "\u020a": "I",
            "\u1eca": "I",
            "\u012e": "I",
            "\u1e2c": "I",
            "\u0197": "I",
            "\u24bf": "J",
            "\uff2a": "J",
            "\u0134": "J",
            "\u0248": "J",
            "\u24c0": "K",
            "\uff2b": "K",
            "\u1e30": "K",
            "\u01e8": "K",
            "\u1e32": "K",
            "\u0136": "K",
            "\u1e34": "K",
            "\u0198": "K",
            "\u2c69": "K",
            "\ua740": "K",
            "\ua742": "K",
            "\ua744": "K",
            "\ua7a2": "K",
            "\u24c1": "L",
            "\uff2c": "L",
            "\u013f": "L",
            "\u0139": "L",
            "\u013d": "L",
            "\u1e36": "L",
            "\u1e38": "L",
            "\u013b": "L",
            "\u1e3c": "L",
            "\u1e3a": "L",
            "\u0141": "L",
            "\u023d": "L",
            "\u2c62": "L",
            "\u2c60": "L",
            "\ua748": "L",
            "\ua746": "L",
            "\ua780": "L",
            "\u01c7": "LJ",
            "\u01c8": "Lj",
            "\u24c2": "M",
            "\uff2d": "M",
            "\u1e3e": "M",
            "\u1e40": "M",
            "\u1e42": "M",
            "\u2c6e": "M",
            "\u019c": "M",
            "\u24c3": "N",
            "\uff2e": "N",
            "\u01f8": "N",
            "\u0143": "N",
            "\xd1": "N",
            "\u1e44": "N",
            "\u0147": "N",
            "\u1e46": "N",
            "\u0145": "N",
            "\u1e4a": "N",
            "\u1e48": "N",
            "\u0220": "N",
            "\u019d": "N",
            "\ua790": "N",
            "\ua7a4": "N",
            "\u01ca": "NJ",
            "\u01cb": "Nj",
            "\u24c4": "O",
            "\uff2f": "O",
            "\xd2": "O",
            "\xd3": "O",
            "\xd4": "O",
            "\u1ed2": "O",
            "\u1ed0": "O",
            "\u1ed6": "O",
            "\u1ed4": "O",
            "\xd5": "O",
            "\u1e4c": "O",
            "\u022c": "O",
            "\u1e4e": "O",
            "\u014c": "O",
            "\u1e50": "O",
            "\u1e52": "O",
            "\u014e": "O",
            "\u022e": "O",
            "\u0230": "O",
            "\xd6": "O",
            "\u022a": "O",
            "\u1ece": "O",
            "\u0150": "O",
            "\u01d1": "O",
            "\u020c": "O",
            "\u020e": "O",
            "\u01a0": "O",
            "\u1edc": "O",
            "\u1eda": "O",
            "\u1ee0": "O",
            "\u1ede": "O",
            "\u1ee2": "O",
            "\u1ecc": "O",
            "\u1ed8": "O",
            "\u01ea": "O",
            "\u01ec": "O",
            "\xd8": "O",
            "\u01fe": "O",
            "\u0186": "O",
            "\u019f": "O",
            "\ua74a": "O",
            "\ua74c": "O",
            "\u01a2": "OI",
            "\ua74e": "OO",
            "\u0222": "OU",
            "\u24c5": "P",
            "\uff30": "P",
            "\u1e54": "P",
            "\u1e56": "P",
            "\u01a4": "P",
            "\u2c63": "P",
            "\ua750": "P",
            "\ua752": "P",
            "\ua754": "P",
            "\u24c6": "Q",
            "\uff31": "Q",
            "\ua756": "Q",
            "\ua758": "Q",
            "\u024a": "Q",
            "\u24c7": "R",
            "\uff32": "R",
            "\u0154": "R",
            "\u1e58": "R",
            "\u0158": "R",
            "\u0210": "R",
            "\u0212": "R",
            "\u1e5a": "R",
            "\u1e5c": "R",
            "\u0156": "R",
            "\u1e5e": "R",
            "\u024c": "R",
            "\u2c64": "R",
            "\ua75a": "R",
            "\ua7a6": "R",
            "\ua782": "R",
            "\u24c8": "S",
            "\uff33": "S",
            "\u1e9e": "S",
            "\u015a": "S",
            "\u1e64": "S",
            "\u015c": "S",
            "\u1e60": "S",
            "\u0160": "S",
            "\u1e66": "S",
            "\u1e62": "S",
            "\u1e68": "S",
            "\u0218": "S",
            "\u015e": "S",
            "\u2c7e": "S",
            "\ua7a8": "S",
            "\ua784": "S",
            "\u24c9": "T",
            "\uff34": "T",
            "\u1e6a": "T",
            "\u0164": "T",
            "\u1e6c": "T",
            "\u021a": "T",
            "\u0162": "T",
            "\u1e70": "T",
            "\u1e6e": "T",
            "\u0166": "T",
            "\u01ac": "T",
            "\u01ae": "T",
            "\u023e": "T",
            "\ua786": "T",
            "\ua728": "TZ",
            "\u24ca": "U",
            "\uff35": "U",
            "\xd9": "U",
            "\xda": "U",
            "\xdb": "U",
            "\u0168": "U",
            "\u1e78": "U",
            "\u016a": "U",
            "\u1e7a": "U",
            "\u016c": "U",
            "\xdc": "U",
            "\u01db": "U",
            "\u01d7": "U",
            "\u01d5": "U",
            "\u01d9": "U",
            "\u1ee6": "U",
            "\u016e": "U",
            "\u0170": "U",
            "\u01d3": "U",
            "\u0214": "U",
            "\u0216": "U",
            "\u01af": "U",
            "\u1eea": "U",
            "\u1ee8": "U",
            "\u1eee": "U",
            "\u1eec": "U",
            "\u1ef0": "U",
            "\u1ee4": "U",
            "\u1e72": "U",
            "\u0172": "U",
            "\u1e76": "U",
            "\u1e74": "U",
            "\u0244": "U",
            "\u24cb": "V",
            "\uff36": "V",
            "\u1e7c": "V",
            "\u1e7e": "V",
            "\u01b2": "V",
            "\ua75e": "V",
            "\u0245": "V",
            "\ua760": "VY",
            "\u24cc": "W",
            "\uff37": "W",
            "\u1e80": "W",
            "\u1e82": "W",
            "\u0174": "W",
            "\u1e86": "W",
            "\u1e84": "W",
            "\u1e88": "W",
            "\u2c72": "W",
            "\u24cd": "X",
            "\uff38": "X",
            "\u1e8a": "X",
            "\u1e8c": "X",
            "\u24ce": "Y",
            "\uff39": "Y",
            "\u1ef2": "Y",
            "\xdd": "Y",
            "\u0176": "Y",
            "\u1ef8": "Y",
            "\u0232": "Y",
            "\u1e8e": "Y",
            "\u0178": "Y",
            "\u1ef6": "Y",
            "\u1ef4": "Y",
            "\u01b3": "Y",
            "\u024e": "Y",
            "\u1efe": "Y",
            "\u24cf": "Z",
            "\uff3a": "Z",
            "\u0179": "Z",
            "\u1e90": "Z",
            "\u017b": "Z",
            "\u017d": "Z",
            "\u1e92": "Z",
            "\u1e94": "Z",
            "\u01b5": "Z",
            "\u0224": "Z",
            "\u2c7f": "Z",
            "\u2c6b": "Z",
            "\ua762": "Z",
            "\u24d0": "a",
            "\uff41": "a",
            "\u1e9a": "a",
            "\xe0": "a",
            "\xe1": "a",
            "\xe2": "a",
            "\u1ea7": "a",
            "\u1ea5": "a",
            "\u1eab": "a",
            "\u1ea9": "a",
            "\xe3": "a",
            "\u0101": "a",
            "\u0103": "a",
            "\u1eb1": "a",
            "\u1eaf": "a",
            "\u1eb5": "a",
            "\u1eb3": "a",
            "\u0227": "a",
            "\u01e1": "a",
            "\xe4": "a",
            "\u01df": "a",
            "\u1ea3": "a",
            "\xe5": "a",
            "\u01fb": "a",
            "\u01ce": "a",
            "\u0201": "a",
            "\u0203": "a",
            "\u1ea1": "a",
            "\u1ead": "a",
            "\u1eb7": "a",
            "\u1e01": "a",
            "\u0105": "a",
            "\u2c65": "a",
            "\u0250": "a",
            "\ua733": "aa",
            "\xe6": "ae",
            "\u01fd": "ae",
            "\u01e3": "ae",
            "\ua735": "ao",
            "\ua737": "au",
            "\ua739": "av",
            "\ua73b": "av",
            "\ua73d": "ay",
            "\u24d1": "b",
            "\uff42": "b",
            "\u1e03": "b",
            "\u1e05": "b",
            "\u1e07": "b",
            "\u0180": "b",
            "\u0183": "b",
            "\u0253": "b",
            "\u24d2": "c",
            "\uff43": "c",
            "\u0107": "c",
            "\u0109": "c",
            "\u010b": "c",
            "\u010d": "c",
            "\xe7": "c",
            "\u1e09": "c",
            "\u0188": "c",
            "\u023c": "c",
            "\ua73f": "c",
            "\u2184": "c",
            "\u24d3": "d",
            "\uff44": "d",
            "\u1e0b": "d",
            "\u010f": "d",
            "\u1e0d": "d",
            "\u1e11": "d",
            "\u1e13": "d",
            "\u1e0f": "d",
            "\u0111": "d",
            "\u018c": "d",
            "\u0256": "d",
            "\u0257": "d",
            "\ua77a": "d",
            "\u01f3": "dz",
            "\u01c6": "dz",
            "\u24d4": "e",
            "\uff45": "e",
            "\xe8": "e",
            "\xe9": "e",
            "\xea": "e",
            "\u1ec1": "e",
            "\u1ebf": "e",
            "\u1ec5": "e",
            "\u1ec3": "e",
            "\u1ebd": "e",
            "\u0113": "e",
            "\u1e15": "e",
            "\u1e17": "e",
            "\u0115": "e",
            "\u0117": "e",
            "\xeb": "e",
            "\u1ebb": "e",
            "\u011b": "e",
            "\u0205": "e",
            "\u0207": "e",
            "\u1eb9": "e",
            "\u1ec7": "e",
            "\u0229": "e",
            "\u1e1d": "e",
            "\u0119": "e",
            "\u1e19": "e",
            "\u1e1b": "e",
            "\u0247": "e",
            "\u025b": "e",
            "\u01dd": "e",
            "\u24d5": "f",
            "\uff46": "f",
            "\u1e1f": "f",
            "\u0192": "f",
            "\ua77c": "f",
            "\u24d6": "g",
            "\uff47": "g",
            "\u01f5": "g",
            "\u011d": "g",
            "\u1e21": "g",
            "\u011f": "g",
            "\u0121": "g",
            "\u01e7": "g",
            "\u0123": "g",
            "\u01e5": "g",
            "\u0260": "g",
            "\ua7a1": "g",
            "\u1d79": "g",
            "\ua77f": "g",
            "\u24d7": "h",
            "\uff48": "h",
            "\u0125": "h",
            "\u1e23": "h",
            "\u1e27": "h",
            "\u021f": "h",
            "\u1e25": "h",
            "\u1e29": "h",
            "\u1e2b": "h",
            "\u1e96": "h",
            "\u0127": "h",
            "\u2c68": "h",
            "\u2c76": "h",
            "\u0265": "h",
            "\u0195": "hv",
            "\u24d8": "i",
            "\uff49": "i",
            "\xec": "i",
            "\xed": "i",
            "\xee": "i",
            "\u0129": "i",
            "\u012b": "i",
            "\u012d": "i",
            "\xef": "i",
            "\u1e2f": "i",
            "\u1ec9": "i",
            "\u01d0": "i",
            "\u0209": "i",
            "\u020b": "i",
            "\u1ecb": "i",
            "\u012f": "i",
            "\u1e2d": "i",
            "\u0268": "i",
            "\u0131": "i",
            "\u24d9": "j",
            "\uff4a": "j",
            "\u0135": "j",
            "\u01f0": "j",
            "\u0249": "j",
            "\u24da": "k",
            "\uff4b": "k",
            "\u1e31": "k",
            "\u01e9": "k",
            "\u1e33": "k",
            "\u0137": "k",
            "\u1e35": "k",
            "\u0199": "k",
            "\u2c6a": "k",
            "\ua741": "k",
            "\ua743": "k",
            "\ua745": "k",
            "\ua7a3": "k",
            "\u24db": "l",
            "\uff4c": "l",
            "\u0140": "l",
            "\u013a": "l",
            "\u013e": "l",
            "\u1e37": "l",
            "\u1e39": "l",
            "\u013c": "l",
            "\u1e3d": "l",
            "\u1e3b": "l",
            "\u017f": "l",
            "\u0142": "l",
            "\u019a": "l",
            "\u026b": "l",
            "\u2c61": "l",
            "\ua749": "l",
            "\ua781": "l",
            "\ua747": "l",
            "\u01c9": "lj",
            "\u24dc": "m",
            "\uff4d": "m",
            "\u1e3f": "m",
            "\u1e41": "m",
            "\u1e43": "m",
            "\u0271": "m",
            "\u026f": "m",
            "\u24dd": "n",
            "\uff4e": "n",
            "\u01f9": "n",
            "\u0144": "n",
            "\xf1": "n",
            "\u1e45": "n",
            "\u0148": "n",
            "\u1e47": "n",
            "\u0146": "n",
            "\u1e4b": "n",
            "\u1e49": "n",
            "\u019e": "n",
            "\u0272": "n",
            "\u0149": "n",
            "\ua791": "n",
            "\ua7a5": "n",
            "\u01cc": "nj",
            "\u24de": "o",
            "\uff4f": "o",
            "\xf2": "o",
            "\xf3": "o",
            "\xf4": "o",
            "\u1ed3": "o",
            "\u1ed1": "o",
            "\u1ed7": "o",
            "\u1ed5": "o",
            "\xf5": "o",
            "\u1e4d": "o",
            "\u022d": "o",
            "\u1e4f": "o",
            "\u014d": "o",
            "\u1e51": "o",
            "\u1e53": "o",
            "\u014f": "o",
            "\u022f": "o",
            "\u0231": "o",
            "\xf6": "o",
            "\u022b": "o",
            "\u1ecf": "o",
            "\u0151": "o",
            "\u01d2": "o",
            "\u020d": "o",
            "\u020f": "o",
            "\u01a1": "o",
            "\u1edd": "o",
            "\u1edb": "o",
            "\u1ee1": "o",
            "\u1edf": "o",
            "\u1ee3": "o",
            "\u1ecd": "o",
            "\u1ed9": "o",
            "\u01eb": "o",
            "\u01ed": "o",
            "\xf8": "o",
            "\u01ff": "o",
            "\u0254": "o",
            "\ua74b": "o",
            "\ua74d": "o",
            "\u0275": "o",
            "\u01a3": "oi",
            "\u0223": "ou",
            "\ua74f": "oo",
            "\u24df": "p",
            "\uff50": "p",
            "\u1e55": "p",
            "\u1e57": "p",
            "\u01a5": "p",
            "\u1d7d": "p",
            "\ua751": "p",
            "\ua753": "p",
            "\ua755": "p",
            "\u24e0": "q",
            "\uff51": "q",
            "\u024b": "q",
            "\ua757": "q",
            "\ua759": "q",
            "\u24e1": "r",
            "\uff52": "r",
            "\u0155": "r",
            "\u1e59": "r",
            "\u0159": "r",
            "\u0211": "r",
            "\u0213": "r",
            "\u1e5b": "r",
            "\u1e5d": "r",
            "\u0157": "r",
            "\u1e5f": "r",
            "\u024d": "r",
            "\u027d": "r",
            "\ua75b": "r",
            "\ua7a7": "r",
            "\ua783": "r",
            "\u24e2": "s",
            "\uff53": "s",
            "\xdf": "s",
            "\u015b": "s",
            "\u1e65": "s",
            "\u015d": "s",
            "\u1e61": "s",
            "\u0161": "s",
            "\u1e67": "s",
            "\u1e63": "s",
            "\u1e69": "s",
            "\u0219": "s",
            "\u015f": "s",
            "\u023f": "s",
            "\ua7a9": "s",
            "\ua785": "s",
            "\u1e9b": "s",
            "\u24e3": "t",
            "\uff54": "t",
            "\u1e6b": "t",
            "\u1e97": "t",
            "\u0165": "t",
            "\u1e6d": "t",
            "\u021b": "t",
            "\u0163": "t",
            "\u1e71": "t",
            "\u1e6f": "t",
            "\u0167": "t",
            "\u01ad": "t",
            "\u0288": "t",
            "\u2c66": "t",
            "\ua787": "t",
            "\ua729": "tz",
            "\u24e4": "u",
            "\uff55": "u",
            "\xf9": "u",
            "\xfa": "u",
            "\xfb": "u",
            "\u0169": "u",
            "\u1e79": "u",
            "\u016b": "u",
            "\u1e7b": "u",
            "\u016d": "u",
            "\xfc": "u",
            "\u01dc": "u",
            "\u01d8": "u",
            "\u01d6": "u",
            "\u01da": "u",
            "\u1ee7": "u",
            "\u016f": "u",
            "\u0171": "u",
            "\u01d4": "u",
            "\u0215": "u",
            "\u0217": "u",
            "\u01b0": "u",
            "\u1eeb": "u",
            "\u1ee9": "u",
            "\u1eef": "u",
            "\u1eed": "u",
            "\u1ef1": "u",
            "\u1ee5": "u",
            "\u1e73": "u",
            "\u0173": "u",
            "\u1e77": "u",
            "\u1e75": "u",
            "\u0289": "u",
            "\u24e5": "v",
            "\uff56": "v",
            "\u1e7d": "v",
            "\u1e7f": "v",
            "\u028b": "v",
            "\ua75f": "v",
            "\u028c": "v",
            "\ua761": "vy",
            "\u24e6": "w",
            "\uff57": "w",
            "\u1e81": "w",
            "\u1e83": "w",
            "\u0175": "w",
            "\u1e87": "w",
            "\u1e85": "w",
            "\u1e98": "w",
            "\u1e89": "w",
            "\u2c73": "w",
            "\u24e7": "x",
            "\uff58": "x",
            "\u1e8b": "x",
            "\u1e8d": "x",
            "\u24e8": "y",
            "\uff59": "y",
            "\u1ef3": "y",
            "\xfd": "y",
            "\u0177": "y",
            "\u1ef9": "y",
            "\u0233": "y",
            "\u1e8f": "y",
            "\xff": "y",
            "\u1ef7": "y",
            "\u1e99": "y",
            "\u1ef5": "y",
            "\u01b4": "y",
            "\u024f": "y",
            "\u1eff": "y",
            "\u24e9": "z",
            "\uff5a": "z",
            "\u017a": "z",
            "\u1e91": "z",
            "\u017c": "z",
            "\u017e": "z",
            "\u1e93": "z",
            "\u1e95": "z",
            "\u01b6": "z",
            "\u0225": "z",
            "\u0240": "z",
            "\u2c6c": "z",
            "\ua763": "z"
        };
        P = t(document),
        N = function() {
            var t = 1;
            return function() {
                return t++
            }
        } (),
        P.on("mousemove",
        function(t) {
            L.x = t.pageX,
            L.y = t.pageY
        }),
        A = _(Object, {
            "bind": function(t) {
                var e = this;
                return function() {
                    t.apply(e, arguments)
                }
            },
            "init": function(n) {
                var i, r, a = ".select2-results";
                this.opts = n = this.prepareOpts(n),
                this.id = n.id,
                n.element.data("select2") !== e && null !== n.element.data("select2") && n.element.data("select2").destroy(),
                this.container = this.createContainer(),
                this.liveRegion = t("<span>", {
                    "role": "status",
                    "aria-live": "polite"
                }).addClass("select2-hidden-accessible").appendTo(document.body),
                this.containerId = "s2id_" + (n.element.attr("id") || "autogen" + N()),
                this.containerEventName = this.containerId.replace(/([.])/g, "_").replace(/([;&,\-\.\+\*\~':"\!\^#$%@\[\]\(\)=>\|])/g, "\\$1"),
                this.container.attr("id", this.containerId),
                this.container.attr("title", n.element.attr("title")),
                this.body = t("body"),
                y(this.container, this.opts.element, this.opts.adaptContainerCssClass),
                this.container.attr("style", n.element.attr("style")),
                this.container.css(T(n.containerCss)),
                this.container.addClass(T(n.containerCssClass)),
                this.elementTabIndex = this.opts.element.attr("tabindex"),
                this.opts.element.data("select2", this).attr("tabindex", "-1").before(this.container).on("click.select2", m),
                this.container.data("select2", this),
                this.dropdown = this.container.find(".select2-drop"),
                y(this.dropdown, this.opts.element, this.opts.adaptDropdownCssClass),
                this.dropdown.addClass(T(n.dropdownCssClass)),
                this.dropdown.data("select2", this),
                this.dropdown.on("click", m),
                this.results = i = this.container.find(a),
                this.search = r = this.container.find("input.select2-input"),
                this.queryCount = 0,
                this.resultsPage = 0,
                this.context = null,
                this.initContainer(),
                this.container.on("click", m),
                c(this.results),
                this.dropdown.on("mousemove-filtered", a, this.bind(this.highlightUnderEvent)),
                this.dropdown.on("touchstart touchmove touchend", a, this.bind(function(t) {
                    this._touchEvent = !0,
                    this.highlightUnderEvent(t)
                })),
                this.dropdown.on("touchmove", a, this.bind(this.touchMoved)),
                this.dropdown.on("touchstart touchend", a, this.bind(this.clearTouchMoved)),
                this.dropdown.on("click", this.bind(function() {
                    this._touchEvent && (this._touchEvent = !1, this.selectHighlighted())
                })),
                d(80, this.results),
                this.dropdown.on("scroll-debounced", a, this.bind(this.loadMoreIfNeeded)),
                t(this.container).on("change", ".select2-input",
                function(t) {
                    t.stopPropagation()
                }),
                t(this.dropdown).on("change", ".select2-input",
                function(t) {
                    t.stopPropagation()
                }),
                t.fn.mousewheel && i.mousewheel(function(t, e, n, r) {
                    var s = i.scrollTop();
                    r > 0 && 0 >= s - r ? (i.scrollTop(0), m(t)) : 0 > r && i.get(0).scrollHeight - i.scrollTop() + r <= i.height() && (i.scrollTop(i.get(0).scrollHeight - i.height()), m(t))
                }),
                u(r),
                r.on("keyup-change input paste", this.bind(this.updateResults)),
                r.on("focus",
                function() {
                    r.addClass("select2-focused")
                }),
                r.on("blur",
                function() {
                    r.removeClass("select2-focused")
                }),
                this.dropdown.on("mouseup", a, this.bind(function(e) {
                    t(e.target).closest(".select2-result-selectable").length > 0 && (this.highlightUnderEvent(e), this.selectHighlighted(e))
                })),
                this.dropdown.on("click mouseup mousedown touchstart touchend focusin",
                function(t) {
                    t.stopPropagation()
                }),
                this.nextSearchTerm = e,
                t.isFunction(this.opts.initSelection) && (this.initSelection(), this.monitorSource()),
                null !== n.maximumInputLength && this.search.attr("maxlength", n.maximumInputLength);
                var o = n.element.prop("disabled");
                o === e && (o = !1),
                this.enable(!o);
                var l = n.element.prop("readonly");
                l === e && (l = !1),
                this.readonly(l),
                I = I || s(),
                this.autofocus = n.element.prop("autofocus"),
                n.element.prop("autofocus", !1),
                this.autofocus && this.focus(),
                this.search.attr("placeholder", n.searchInputPlaceholder)
            },
            "destroy": function() {
                var t = this.opts.element,
                n = t.data("select2");
                this.close(),
                this.propertyObserver && (this.propertyObserver.disconnect(), this.propertyObserver = null),
                n !== e && (n.container.remove(), n.liveRegion.remove(), n.dropdown.remove(), t.removeClass("select2-offscreen").removeData("select2").off(".select2").prop("autofocus", this.autofocus || !1), this.elementTabIndex ? t.attr({
                    "tabindex": this.elementTabIndex
                }) : t.removeAttr("tabindex"), t.show()),
                E.call(this, "container", "liveRegion", "dropdown", "results", "search")
            },
            "optionToData": function(t) {
                return t.is("option") ? {
                    "id": t.prop("value"),
                    "text": t.text(),
                    "element": t.get(),
                    "css": t.attr("class"),
                    "disabled": t.prop("disabled"),
                    "locked": a(t.attr("locked"), "locked") || a(t.data("locked"), !0)
                }: t.is("optgroup") ? {
                    "text": t.attr("label"),
                    "children": [],
                    "element": t.get(),
                    "css": t.attr("class")
                }: void 0
            },
            "prepareOpts": function(n) {
                var i, r, s, l, u = this;
                if (i = n.element, "select" === i.get(0).tagName.toLowerCase() && (this.select = r = n.element), r && t.each(["id", "multiple", "ajax", "query", "createSearchChoice", "initSelection", "data", "tags"],
                function() {
                    if (this in n) throw new Error("Option '" + this + "' is not allowed for Select2 when attached to a <select> element.")
                }), n = t.extend({},
                {
                    "populateResults": function(i, r, s) {
                        var a, o = this.opts.id,
                        l = this.liveRegion; (a = function(i, r, c) {
                            var h, d, f, p, m, g, v, y, b, w;
                            for (i = n.sortResults(i, r, s), h = 0, d = i.length; d > h; h += 1) f = i[h],
                            m = f.disabled === !0,
                            p = !m && o(f) !== e,
                            g = f.children && f.children.length > 0,
                            v = t("<li></li>"),
                            v.addClass("select2-results-dept-" + c),
                            v.addClass("select2-result"),
                            v.addClass(p ? "select2-result-selectable": "select2-result-unselectable"),
                            m && v.addClass("select2-disabled"),
                            g && v.addClass("select2-result-with-children"),
                            v.addClass(u.opts.formatResultCssClass(f)),
                            v.attr("role", "presentation"),
                            y = t(document.createElement("div")),
                            y.addClass("select2-result-label"),
                            y.attr("id", "select2-result-label-" + N()),
                            y.attr("role", "option"),
                            w = n.formatResult(f, y, s, u.opts.escapeMarkup),
                            w !== e && (y.html(w), v.append(y)),
                            g && (b = t("<ul></ul>"), b.addClass("select2-result-sub"), a(f.children, b, c + 1), v.append(b)),
                            v.data("select2-data", f),
                            r.append(v);
                            l.text(n.formatMatches(i.length))
                        })(r, i, 0)
                    }
                },
                t.fn.select2.defaults, n), "function" != typeof n.id && (s = n.id, n.id = function(t) {
                    return t[s]
                }), t.isArray(n.element.data("select2Tags"))) {
                    if ("tags" in n) throw "tags specified as both an attribute 'data-select2-tags' and in options of Select2 " + n.element.attr("id");
                    n.tags = n.element.data("select2Tags")
                }
                if (r ? (n.query = this.bind(function(t) {
                    var n, r, s, a = {
                        "results": [],
                        "more": !1
                    },
                    o = t.term;
                    s = function(e, n) {
                        var i;
                        e.is("option") ? t.matcher(o, e.text(), e) && n.push(u.optionToData(e)) : e.is("optgroup") && (i = u.optionToData(e), e.children().each2(function(t, e) {
                            s(e, i.children)
                        }), i.children.length > 0 && n.push(i))
                    },
                    n = i.children(),
                    this.getPlaceholder() !== e && n.length > 0 && (r = this.getPlaceholderOption(), r && (n = n.not(r))),
                    n.each2(function(t, e) {
                        s(e, a.results)
                    }),
                    t.callback(a)
                }), n.id = function(t) {
                    return t.id
                }) : "query" in n || ("ajax" in n ? (l = n.element.data("ajax-url"), l && l.length > 0 && (n.ajax.url = l), n.query = $.call(n.element, n.ajax)) : "data" in n ? n.query = x(n.data) : "tags" in n && (n.query = C(n.tags), n.createSearchChoice === e && (n.createSearchChoice = function(e) {
                    return {
                        "id": t.trim(e),
                        "text": t.trim(e)
                    }
                }), n.initSelection === e && (n.initSelection = function(e, i) {
                    var r = [];
                    t(o(e.val(), n.separator)).each(function() {
                        var e = {
                            "id": this,
                            "text": this
                        },
                        i = n.tags;
                        t.isFunction(i) && (i = i()),
                        t(i).each(function() {
                            return a(this.id, e.id) ? (e = this, !1) : void 0
                        }),
                        r.push(e)
                    }),
                    i(r)
                }))), "function" != typeof n.query) throw "query function not defined for Select2 " + n.element.attr("id");
                if ("top" === n.createSearchChoicePosition) n.createSearchChoicePosition = function(t, e) {
                    t.unshift(e)
                };
                else if ("bottom" === n.createSearchChoicePosition) n.createSearchChoicePosition = function(t, e) {
                    t.push(e)
                };
                else if ("function" != typeof n.createSearchChoicePosition) throw "invalid createSearchChoicePosition option must be 'top', 'bottom' or a custom function";
                return n
            },
            "monitorSource": function() {
                var t, n, i = this.opts.element;
                i.on("change.select2", this.bind(function() {
                    this.opts.element.data("select2-change-triggered") !== !0 && this.initSelection()
                })),
                t = this.bind(function() {
                    var t = i.prop("disabled");
                    t === e && (t = !1),
                    this.enable(!t);
                    var n = i.prop("readonly");
                    n === e && (n = !1),
                    this.readonly(n),
                    y(this.container, this.opts.element, this.opts.adaptContainerCssClass),
                    this.container.addClass(T(this.opts.containerCssClass)),
                    y(this.dropdown, this.opts.element, this.opts.adaptDropdownCssClass),
                    this.dropdown.addClass(T(this.opts.dropdownCssClass))
                }),
                i.length && i[0].attachEvent && i.each(function() {
                    this.attachEvent("onpropertychange", t)
                }),
                n = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver,
                n !== e && (this.propertyObserver && (delete this.propertyObserver, this.propertyObserver = null), this.propertyObserver = new n(function(e) {
                    e.forEach(t)
                }), this.propertyObserver.observe(i.get(0), {
                    "attributes": !0,
                    "subtree": !1
                }))
            },
            "triggerSelect": function(e) {
                var n = t.Event("select2-selecting", {
                    "val": this.id(e),
                    "object": e
                });
                return this.opts.element.trigger(n),
                !n.isDefaultPrevented()
            },
            "triggerChange": function(e) {
                e = e || {},
                e = t.extend({},
                e, {
                    "type": "change",
                    "val": this.val()
                }),
                this.opts.element.data("select2-change-triggered", !0),
                this.opts.element.trigger(e),
                this.opts.element.data("select2-change-triggered", !1),
                this.opts.element.click(),
                this.opts.blurOnChange && this.opts.element.blur()
            },
            "isInterfaceEnabled": function() {
                return this.enabledInterface === !0
            },
            "enableInterface": function() {
                var t = this._enabled && !this._readonly,
                e = !t;
                return t === this.enabledInterface ? !1 : (this.container.toggleClass("select2-container-disabled", e), this.close(), this.enabledInterface = t, !0)
            },
            "enable": function(t) {
                t === e && (t = !0),
                this._enabled !== t && (this._enabled = t, this.opts.element.prop("disabled", !t), this.enableInterface())
            },
            "disable": function() {
                this.enable(!1)
            },
            "readonly": function(t) {
                t === e && (t = !1),
                this._readonly !== t && (this._readonly = t, this.opts.element.prop("readonly", t), this.enableInterface())
            },
            "opened": function() {
                return this.container.hasClass("select2-dropdown-open")
            },
            "positionDropdown": function() {
                var e, n, i, r, s, a = this.dropdown,
                o = this.container.offset(),
                l = this.container.outerHeight(!1),
                u = this.container.outerWidth(!1),
                c = a.outerHeight(!1),
                h = t(window),
                d = h.width(),
                f = h.height(),
                p = h.scrollLeft() + d,
                m = h.scrollTop() + f,
                g = o.top + l,
                v = o.left,
                y = m >= g + c,
                b = o.top - c >= h.scrollTop(),
                w = a.outerWidth(!1),
                $ = p >= v + w,
                x = a.hasClass("select2-drop-above");
                x ? (n = !0, !b && y && (i = !0, n = !1)) : (n = !1, !y && b && (i = !0, n = !0)),
                i && (a.hide(), o = this.container.offset(), l = this.container.outerHeight(!1), u = this.container.outerWidth(!1), c = a.outerHeight(!1), p = h.scrollLeft() + d, m = h.scrollTop() + f, g = o.top + l, v = o.left, w = a.outerWidth(!1), $ = p >= v + w, a.show(), this.focusSearch()),
                this.opts.dropdownAutoWidth ? (s = t(".select2-results", a)[0], a.addClass("select2-drop-auto-width"), a.css("width", ""), w = a.outerWidth(!1) + (s.scrollHeight === s.clientHeight ? 0 : I.width), w > u ? u = w: w = u, c = a.outerHeight(!1), $ = p >= v + w) : this.container.removeClass("select2-drop-auto-width"),
                "static" !== this.body.css("position") && (e = this.body.offset(), g -= e.top, v -= e.left),
                $ || (v = o.left + this.container.outerWidth(!1) - w),
                r = {
                    "left": v,
                    "width": u
                },
                n ? (r.top = o.top - c, r.bottom = "auto", this.container.addClass("select2-drop-above"), a.addClass("select2-drop-above")) : (r.top = g, r.bottom = "auto", this.container.removeClass("select2-drop-above"), a.removeClass("select2-drop-above")),
                r = t.extend(r, T(this.opts.dropdownCss)),
                a.css(r)
            },
            "shouldOpen": function() {
                var e;
                return this.opened() ? !1 : this._enabled === !1 || this._readonly === !0 ? !1 : (e = t.Event("select2-opening"), this.opts.element.trigger(e), !e.isDefaultPrevented())
            },
            "clearDropdownAlignmentPreference": function() {
                this.container.removeClass("select2-drop-above"),
                this.dropdown.removeClass("select2-drop-above")
            },
            "open": function() {
                return this.shouldOpen() ? (this.opening(), !0) : !1
            },
            "opening": function() {
                var e, i = this.containerEventName,
                r = "scroll." + i,
                s = "resize." + i,
                a = "orientationchange." + i;
                this.container.addClass("select2-dropdown-open").addClass("select2-container-active"),
                this.clearDropdownAlignmentPreference(),
                this.dropdown[0] !== this.body.children().last()[0] && this.dropdown.detach().appendTo(this.body),
                e = t("#select2-drop-mask"),
                0 == e.length && (e = t(document.createElement("div")), e.attr("id", "select2-drop-mask").attr("class", "select2-drop-mask"), e.hide(), e.appendTo(this.body), e.on("mousedown touchstart click",
                function(i) {
                    n(e);
                    var r, s = t("#select2-drop");
                    s.length > 0 && (r = s.data("select2"), r.opts.selectOnBlur && r.selectHighlighted({
                        "noFocus": !0
                    }), r.close(), i.preventDefault(), i.stopPropagation())
                })),
                this.dropdown.prev()[0] !== e[0] && this.dropdown.before(e),
                t("#select2-drop").removeAttr("id"),
                this.dropdown.attr("id", "select2-drop"),
                e.show(),
                this.positionDropdown(),
                this.dropdown.show(),
                this.positionDropdown(),
                this.dropdown.addClass("select2-drop-active");
                var o = this;
                this.container.parents().add(window).each(function() {
                    t(this).on(s + " " + r + " " + a,
                    function() {
                        o.opened() && o.positionDropdown()
                    })
                })
            },
            "close": function() {
                if (this.opened()) {
                    var e = this.containerEventName,
                    n = "scroll." + e,
                    i = "resize." + e,
                    r = "orientationchange." + e;
                    this.container.parents().add(window).each(function() {
                        t(this).off(n).off(i).off(r)
                    }),
                    this.clearDropdownAlignmentPreference(),
                    t("#select2-drop-mask").hide(),
                    this.dropdown.removeAttr("id"),
                    this.dropdown.hide(),
                    this.container.removeClass("select2-dropdown-open").removeClass("select2-container-active"),
                    this.results.empty(),
                    this.clearSearch(),
                    this.search.removeClass("select2-active"),
                    this.opts.element.trigger(t.Event("select2-close"))
                }
            },
            "externalSearch": function(t) {
                this.open(),
                this.search.val(t),
                this.updateResults(!1)
            },
            "clearSearch": function() {},
            "getMaximumSelectionSize": function() {
                return T(this.opts.maximumSelectionSize)
            },
            "ensureHighlightVisible": function() {
                var e, n, i, r, s, a, o, l = this.results;
                if (n = this.highlight(), !(0 > n)) {
                    if (0 == n) return void l.scrollTop(0);
                    e = this.findHighlightableChoices().find(".select2-result-label"),
                    i = t(e[n]),
                    r = i.offset().top + i.outerHeight(!0),
                    n === e.length - 1 && (o = l.find("li.select2-more-results"), o.length > 0 && (r = o.offset().top + o.outerHeight(!0))),
                    s = l.offset().top + l.outerHeight(!0),
                    r > s && l.scrollTop(l.scrollTop() + (r - s)),
                    a = i.offset().top - l.offset().top,
                    0 > a && "none" != i.css("display") && l.scrollTop(l.scrollTop() + a)
                }
            },
            "findHighlightableChoices": function() {
                return this.results.find(".select2-result-selectable:not(.select2-disabled):not(.select2-selected)")
            },
            "moveHighlight": function(e) {
                for (var n = this.findHighlightableChoices(), i = this.highlight(); i > -1 && i < n.length;) {
                    i += e;
                    var r = t(n[i]);
                    if (r.hasClass("select2-result-selectable") && !r.hasClass("select2-disabled") && !r.hasClass("select2-selected")) {
                        this.highlight(i);
                        break
                    }
                }
            },
            "highlight": function(e) {
                var n, i, s = this.findHighlightableChoices();
                return 0 === arguments.length ? r(s.filter(".select2-highlighted")[0], s.get()) : (e >= s.length && (e = s.length - 1), 0 > e && (e = 0), this.removeHighlight(), n = t(s[e]), n.addClass("select2-highlighted"), this.search.attr("aria-activedescendant", n.find(".select2-result-label").attr("id")), this.ensureHighlightVisible(), this.liveRegion.text(n.text()), i = n.data("select2-data"), void(i && this.opts.element.trigger({
                    "type": "select2-highlight",
                    "val": this.id(i),
                    "choice": i
                })))
            },
            "removeHighlight": function() {
                this.results.find(".select2-highlighted").removeClass("select2-highlighted")
            },
            "touchMoved": function() {
                this._touchMoved = !0
            },
            "clearTouchMoved": function() {
                this._touchMoved = !1
            },
            "countSelectableResults": function() {
                return this.findHighlightableChoices().length
            },
            "highlightUnderEvent": function(e) {
                var n = t(e.target).closest(".select2-result-selectable");
                if (n.length > 0 && !n.is(".select2-highlighted")) {
                    var i = this.findHighlightableChoices();
                    this.highlight(i.index(n))
                } else 0 == n.length && this.removeHighlight()
            },
            "loadMoreIfNeeded": function() {
                var t, e = this.results,
                n = e.find("li.select2-more-results"),
                i = this.resultsPage + 1,
                r = this,
                s = this.search.val(),
                a = this.context;
                0 !== n.length && (t = n.offset().top - e.offset().top - e.height(), t <= this.opts.loadMorePadding && (n.addClass("select2-active"), this.opts.query({
                    "element": this.opts.element,
                    "term": s,
                    "page": i,
                    "context": a,
                    "matcher": this.opts.matcher,
                    "callback": this.bind(function(t) {
                        r.opened() && (r.opts.populateResults.call(this, e, t.results, {
                            "term": s,
                            "page": i,
                            "context": a
                        }), r.postprocessResults(t, !1, !1), t.more === !0 ? (n.detach().appendTo(e).text(T(r.opts.formatLoadMore, i + 1)), window.setTimeout(function() {
                            r.loadMoreIfNeeded()
                        },
                        10)) : n.remove(), r.positionDropdown(), r.resultsPage = i, r.context = t.context, this.opts.element.trigger({
                            "type": "select2-loaded",
                            "items": t
                        }))
                    })
                })))
            },
            "tokenize": function() {},
            "updateResults": function(n) {
                function i() {
                    u.removeClass("select2-active"),
                    d.positionDropdown(),
                    d.liveRegion.text(c.find(".select2-no-results,.select2-selection-limit,.select2-searching").length ? c.text() : d.opts.formatMatches(c.find(".select2-result-selectable").length))
                }
                function r(t) {
                    c.html(t),
                    i()
                }
                var s, o, l, u = this.search,
                c = this.results,
                h = this.opts,
                d = this,
                f = u.val(),
                p = t.data(this.container, "select2-last-term");
                if ((n === !0 || !p || !a(f, p)) && (t.data(this.container, "select2-last-term", f), n === !0 || this.showSearchInput !== !1 && this.opened())) {
                    l = ++this.queryCount;
                    var m = this.getMaximumSelectionSize();
                    if (m >= 1 && (s = this.data(), t.isArray(s) && s.length >= m && k(h.formatSelectionTooBig, "formatSelectionTooBig"))) return void r("<li class='select2-selection-limit'>" + T(h.formatSelectionTooBig, m) + "</li>");
                    if (u.val().length < h.minimumInputLength) return r(k(h.formatInputTooShort, "formatInputTooShort") ? "<li class='select2-no-results'>" + T(h.formatInputTooShort, u.val(), h.minimumInputLength) + "</li>": ""),
                    void(n && this.showSearch && this.showSearch(!0));
                    if (h.maximumInputLength && u.val().length > h.maximumInputLength) return void r(k(h.formatInputTooLong, "formatInputTooLong") ? "<li class='select2-no-results'>" + T(h.formatInputTooLong, u.val(), h.maximumInputLength) + "</li>": "");
                    h.formatSearching && 0 === this.findHighlightableChoices().length && r("<li class='select2-searching'>" + T(h.formatSearching) + "</li>"),
                    u.addClass("select2-active"),
                    this.removeHighlight(),
                    o = this.tokenize(),
                    o != e && null != o && u.val(o),
                    this.resultsPage = 1,
                    h.query({
                        "element": h.element,
                        "term": u.val(),
                        "page": this.resultsPage,
                        "context": null,
                        "matcher": h.matcher,
                        "callback": this.bind(function(s) {
                            var o;
                            if (l == this.queryCount) {
                                if (!this.opened()) return void this.search.removeClass("select2-active");
                                if (this.context = s.context === e ? null: s.context, this.opts.createSearchChoice && "" !== u.val() && (o = this.opts.createSearchChoice.call(d, u.val(), s.results), o !== e && null !== o && d.id(o) !== e && null !== d.id(o) && 0 === t(s.results).filter(function() {
                                    return a(d.id(this), d.id(o))
                                }).length && this.opts.createSearchChoicePosition(s.results, o)), 0 === s.results.length && k(h.formatNoMatches, "formatNoMatches")) return void r("<li class='select2-no-results'>" + T(h.formatNoMatches, u.val()) + "</li>");
                                c.empty(),
                                d.opts.populateResults.call(this, c, s.results, {
                                    "term": u.val(),
                                    "page": this.resultsPage,
                                    "context": null
                                }),
                                s.more === !0 && k(h.formatLoadMore, "formatLoadMore") && (c.append("<li class='select2-more-results'>" + d.opts.escapeMarkup(T(h.formatLoadMore, this.resultsPage)) + "</li>"), window.setTimeout(function() {
                                    d.loadMoreIfNeeded()
                                },
                                10)),
                                this.postprocessResults(s, n),
                                i(),
                                this.opts.element.trigger({
                                    "type": "select2-loaded",
                                    "items": s
                                })
                            }
                        })
                    })
                }
            },
            "cancel": function() {
                this.close()
            },
            "blur": function() {
                this.opts.selectOnBlur && this.selectHighlighted({
                    "noFocus": !0
                }),
                this.close(),
                this.container.removeClass("select2-container-active"),
                this.search[0] === document.activeElement && this.search.blur(),
                this.clearSearch(),
                this.selection.find(".select2-search-choice-focus").removeClass("select2-search-choice-focus")
            },
            "focusSearch": function() {
                f(this.search)
            },
            "selectHighlighted": function(t) {
                if (this._touchMoved) return void this.clearTouchMoved();
                var e = this.highlight(),
                n = this.results.find(".select2-highlighted"),
                i = n.closest(".select2-result").data("select2-data");
                i ? (this.highlight(e), this.onSelect(i, t)) : t && t.noFocus && this.close()
            },
            "getPlaceholder": function() {
                var t;
                return this.opts.element.attr("placeholder") || this.opts.element.attr("data-placeholder") || this.opts.element.data("placeholder") || this.opts.placeholder || ((t = this.getPlaceholderOption()) !== e ? t.text() : e)
            },
            "getPlaceholderOption": function() {
                if (this.select) {
                    var n = this.select.children("option").first();
                    if (this.opts.placeholderOption !== e) return "first" === this.opts.placeholderOption && n || "function" == typeof this.opts.placeholderOption && this.opts.placeholderOption(this.select);
                    if ("" === t.trim(n.text()) && "" === n.val()) return n
                }
            },
            "initContainerWidth": function() {
                function n() {
                    var n, i, r, s, a, o;
                    if ("off" === this.opts.width) return null;
                    if ("element" === this.opts.width) return 0 === this.opts.element.outerWidth(!1) ? "auto": this.opts.element.outerWidth(!1) + "px";
                    if ("copy" === this.opts.width || "resolve" === this.opts.width) {
                        if (n = this.opts.element.attr("style"), n !== e) for (i = n.split(";"), s = 0, a = i.length; a > s; s += 1) if (o = i[s].replace(/\s/g, ""), r = o.match(/^width:(([-+]?([0-9]*\.)?[0-9]+)(px|em|ex|%|in|cm|mm|pt|pc))/i), null !== r && r.length >= 1) return r[1];
                        return "resolve" === this.opts.width ? (n = this.opts.element.css("width"), n.indexOf("%") > 0 ? n: 0 === this.opts.element.outerWidth(!1) ? "auto": this.opts.element.outerWidth(!1) + "px") : null
                    }
                    return t.isFunction(this.opts.width) ? this.opts.width() : this.opts.width
                }
                var i = n.call(this);
                null !== i && this.container.css("width", i)
            }
        }),
        O = _(A, {
            "createContainer": function() {
                var e = t(document.createElement("div")).attr({
                    "class": "select2-container"
                }).html(["<a href='javascript:void(0)' class='select2-choice' tabindex='-1'>", "   <span class='select2-chosen'>&#160;</span><abbr class='select2-search-choice-close'></abbr>", "   <span class='select2-arrow' role='presentation'><b role='presentation'></b></span>", "</a>", "<label for='' class='select2-offscreen'></label>", "<input class='select2-focusser select2-offscreen' type='text' aria-haspopup='true' role='button' />", "<div class='select2-drop select2-display-none'>", "   <div class='select2-search'>", "       <label for='' class='select2-offscreen'></label>", "       <input type='text' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' class='select2-input' role='combobox' aria-expanded='true'", "       aria-autocomplete='list' />", "   </div>", "   <ul class='select2-results' role='listbox'>", "   </ul>", "</div>"].join(""));
                return e
            },
            "enableInterface": function() {
                this.parent.enableInterface.apply(this, arguments) && this.focusser.prop("disabled", !this.isInterfaceEnabled())
            },
            "opening": function() {
                var n, i, r;
                this.opts.minimumResultsForSearch >= 0 && this.showSearch(!0),
                this.parent.opening.apply(this, arguments),
                this.showSearchInput !== !1 && this.search.val(this.focusser.val()),
                this.opts.shouldFocusInput(this) && (this.search.focus(), n = this.search.get(0), n.createTextRange ? (i = n.createTextRange(), i.collapse(!1), i.select()) : n.setSelectionRange && (r = this.search.val().length, n.setSelectionRange(r, r))),
                "" === this.search.val() && this.nextSearchTerm != e && (this.search.val(this.nextSearchTerm), this.search.select()),
                this.focusser.prop("disabled", !0).val(""),
                this.updateResults(!0),
                this.opts.element.trigger(t.Event("select2-open"))
            },
            "close": function() {
                this.opened() && (this.parent.close.apply(this, arguments), this.focusser.prop("disabled", !1), this.opts.shouldFocusInput(this) && this.focusser.focus())
            },
            "focus": function() {
                this.opened() ? this.close() : (this.focusser.prop("disabled", !1), this.opts.shouldFocusInput(this) && this.focusser.focus())
            },
            "isFocused": function() {
                return this.container.hasClass("select2-container-active")
            },
            "cancel": function() {
                this.parent.cancel.apply(this, arguments),
                this.focusser.prop("disabled", !1),
                this.opts.shouldFocusInput(this) && this.focusser.focus()
            },
            "destroy": function() {
                t("label[for='" + this.focusser.attr("id") + "']").attr("for", this.opts.element.attr("id")),
                this.parent.destroy.apply(this, arguments),
                E.call(this, "selection", "focusser")
            },
            "initContainer": function() {
                var e, i, r = this.container,
                s = this.dropdown,
                a = N();
                this.showSearch(this.opts.minimumResultsForSearch < 0 ? !1 : !0),
                this.selection = e = r.find(".select2-choice"),
                this.focusser = r.find(".select2-focusser"),
                e.find(".select2-chosen").attr("id", "select2-chosen-" + a),
                this.focusser.attr("aria-labelledby", "select2-chosen-" + a),
                this.results.attr("id", "select2-results-" + a),
                this.search.attr("aria-owns", "select2-results-" + a),
                this.focusser.attr("id", "s2id_autogen" + a),
                i = t("label[for='" + this.opts.element.attr("id") + "']"),
                this.focusser.prev().text(i.text()).attr("for", this.focusser.attr("id"));
                var o = this.opts.element.attr("title");
                this.opts.element.attr("title", o || i.text()),
                this.focusser.attr("tabindex", this.elementTabIndex),
                this.search.attr("id", this.focusser.attr("id") + "_search"),
                this.search.prev().text(t("label[for='" + this.focusser.attr("id") + "']").text()).attr("for", this.search.attr("id")),
                this.search.on("keydown", this.bind(function(t) {
                    if (this.isInterfaceEnabled()) {
                        if (t.which === M.PAGE_UP || t.which === M.PAGE_DOWN) return void m(t);
                        switch (t.which) {
                        case M.UP:
                        case M.DOWN:
                            return this.moveHighlight(t.which === M.UP ? -1 : 1),
                            void m(t);
                        case M.ENTER:
                            return this.selectHighlighted(),
                            void m(t);
                        case M.TAB:
                            return void this.selectHighlighted({
                                "noFocus":
                                !0
                            });
                        case M.ESC:
                            return this.cancel(t),
                            void m(t)
                        }
                    }
                })),
                this.search.on("blur", this.bind(function() {
                    document.activeElement === this.body.get(0) && window.setTimeout(this.bind(function() {
                        this.opened() && this.search.focus()
                    }), 0)
                })),
                this.focusser.on("keydown", this.bind(function(t) {
                    if (this.isInterfaceEnabled() && t.which !== M.TAB && !M.isControl(t) && !M.isFunctionKey(t) && t.which !== M.ESC) {
                        if (this.opts.openOnEnter === !1 && t.which === M.ENTER) return void m(t);
                        if (t.which == M.DOWN || t.which == M.UP || t.which == M.ENTER && this.opts.openOnEnter) {
                            if (t.altKey || t.ctrlKey || t.shiftKey || t.metaKey) return;
                            return this.open(),
                            void m(t)
                        }
                        return t.which == M.DELETE || t.which == M.BACKSPACE ? (this.opts.allowClear && this.clear(), void m(t)) : void 0
                    }
                })),
                u(this.focusser),
                this.focusser.on("keyup-change input", this.bind(function(t) {
                    if (this.opts.minimumResultsForSearch >= 0) {
                        if (t.stopPropagation(), this.opened()) return;
                        this.open()
                    }
                })),
                e.on("mousedown touchstart", "abbr", this.bind(function(t) {
                    this.isInterfaceEnabled() && (this.clear(), g(t), this.close(), this.selection.focus())
                })),
                e.on("mousedown touchstart", this.bind(function(i) {
                    n(e),
                    this.container.hasClass("select2-container-active") || this.opts.element.trigger(t.Event("select2-focus")),
                    this.opened() ? this.close() : this.isInterfaceEnabled() && this.open(),
                    m(i)
                })),
                s.on("mousedown touchstart", this.bind(function() {
                    this.opts.shouldFocusInput(this) && this.search.focus()
                })),
                e.on("focus", this.bind(function(t) {
                    m(t)
                })),
                this.focusser.on("focus", this.bind(function() {
                    this.container.hasClass("select2-container-active") || this.opts.element.trigger(t.Event("select2-focus")),
                    this.container.addClass("select2-container-active")
                })).on("blur", this.bind(function() {
                    this.opened() || (this.container.removeClass("select2-container-active"), this.opts.element.trigger(t.Event("select2-blur")))
                })),
                this.search.on("focus", this.bind(function() {
                    this.container.hasClass("select2-container-active") || this.opts.element.trigger(t.Event("select2-focus")),
                    this.container.addClass("select2-container-active")
                })),
                this.initContainerWidth(),
                this.opts.element.addClass("select2-offscreen"),
                this.setPlaceholder()
            },
            "clear": function(e) {
                var n = this.selection.data("select2-data");
                if (n) {
                    var i = t.Event("select2-clearing");
                    if (this.opts.element.trigger(i), i.isDefaultPrevented()) return;
                    var r = this.getPlaceholderOption();
                    this.opts.element.val(r ? r.val() : ""),
                    this.selection.find(".select2-chosen").empty(),
                    this.selection.removeData("select2-data"),
                    this.setPlaceholder(),
                    e !== !1 && (this.opts.element.trigger({
                        "type": "select2-removed",
                        "val": this.id(n),
                        "choice": n
                    }), this.triggerChange({
                        "removed": n
                    }))
                }
            },
            "initSelection": function() {
                if (this.isPlaceholderOptionSelected()) this.updateSelection(null),
                this.close(),
                this.setPlaceholder();
                else {
                    var t = this;
                    this.opts.initSelection.call(null, this.opts.element,
                    function(n) {
                        n !== e && null !== n && (t.updateSelection(n), t.close(), t.setPlaceholder(), t.nextSearchTerm = t.opts.nextSearchTerm(n, t.search.val()))
                    })
                }
            },
            "isPlaceholderOptionSelected": function() {
                var t;
                return this.getPlaceholder() === e ? !1 : (t = this.getPlaceholderOption()) !== e && t.prop("selected") || "" === this.opts.element.val() || this.opts.element.val() === e || null === this.opts.element.val()
            },
            "prepareOpts": function() {
                var e = this.parent.prepareOpts.apply(this, arguments),
                n = this;
                return "select" === e.element.get(0).tagName.toLowerCase() ? e.initSelection = function(t, e) {
                    var i = t.find("option").filter(function() {
                        return this.selected && !this.disabled
                    });
                    e(n.optionToData(i))
                }: "data" in e && (e.initSelection = e.initSelection ||
                function(n, i) {
                    var r = n.val(),
                    s = null;
                    e.query({
                        "matcher": function(t, n, i) {
                            var o = a(r, e.id(i));
                            return o && (s = i),
                            o
                        },
                        "callback": t.isFunction(i) ?
                        function() {
                            i(s)
                        }: t.noop
                    })
                }),
                e
            },
            "getPlaceholder": function() {
                return this.select && this.getPlaceholderOption() === e ? e: this.parent.getPlaceholder.apply(this, arguments)
            },
            "setPlaceholder": function() {
                var t = this.getPlaceholder();
                if (this.isPlaceholderOptionSelected() && t !== e) {
                    if (this.select && this.getPlaceholderOption() === e) return;
                    this.selection.find(".select2-chosen").html(this.opts.escapeMarkup(t)),
                    this.selection.addClass("select2-default"),
                    this.container.removeClass("select2-allowclear")
                }
            },
            "postprocessResults": function(t, e, n) {
                var i = 0,
                r = this;
                if (this.findHighlightableChoices().each2(function(t, e) {
                    return a(r.id(e.data("select2-data")), r.opts.element.val()) ? (i = t, !1) : void 0
                }), n !== !1 && this.highlight(e === !0 && i >= 0 ? i: 0), e === !0) {
                    var s = this.opts.minimumResultsForSearch;
                    s >= 0 && this.showSearch(S(t.results) >= s)
                }
            },
            "showSearch": function(e) {
                this.showSearchInput !== e && (this.showSearchInput = e, this.dropdown.find(".select2-search").toggleClass("select2-search-hidden", !e), this.dropdown.find(".select2-search").toggleClass("select2-offscreen", !e), t(this.dropdown, this.container).toggleClass("select2-with-searchbox", e))
            },
            "onSelect": function(t, e) {
                if (this.triggerSelect(t)) {
                    var n = this.opts.element.val(),
                    i = this.data();
                    this.opts.element.val(this.id(t)),
                    this.updateSelection(t),
                    this.opts.element.trigger({
                        "type": "select2-selected",
                        "val": this.id(t),
                        "choice": t
                    }),
                    this.nextSearchTerm = this.opts.nextSearchTerm(t, this.search.val()),
                    this.close(),
                    e && e.noFocus || !this.opts.shouldFocusInput(this) || this.focusser.focus(),
                    a(n, this.id(t)) || this.triggerChange({
                        "added": t,
                        "removed": i
                    })
                }
            },
            "updateSelection": function(t) {
                var n, i, r = this.selection.find(".select2-chosen");
                this.selection.data("select2-data", t),
                r.empty(),
                null !== t && (n = this.opts.formatSelection(t, r, this.opts.escapeMarkup)),
                n !== e && r.append(n),
                i = this.opts.formatSelectionCssClass(t, r),
                i !== e && r.addClass(i),
                this.selection.removeClass("select2-default"),
                this.opts.allowClear && this.getPlaceholder() !== e && this.container.addClass("select2-allowclear")
            },
            "val": function() {
                var t, n = !1,
                i = null,
                r = this,
                s = this.data();
                if (0 === arguments.length) return this.opts.element.val();
                if (t = arguments[0], arguments.length > 1 && (n = arguments[1]), this.select) this.select.val(t).find("option").filter(function() {
                    return this.selected
                }).each2(function(t, e) {
                    return i = r.optionToData(e),
                    !1
                }),
                this.updateSelection(i),
                this.setPlaceholder(),
                n && this.triggerChange({
                    "added": i,
                    "removed": s
                });
                else {
                    if (!t && 0 !== t) return void this.clear(n);
                    if (this.opts.initSelection === e) throw new Error("cannot call val() if initSelection() is not defined");
                    this.opts.element.val(t),
                    this.opts.initSelection(this.opts.element,
                    function(t) {
                        r.opts.element.val(t ? r.id(t) : ""),
                        r.updateSelection(t),
                        r.setPlaceholder(),
                        n && r.triggerChange({
                            "added": t,
                            "removed": s
                        })
                    })
                }
            },
            "clearSearch": function() {
                this.search.val(""),
                this.focusser.val("")
            },
            "data": function(t) {
                var n, i = !1;
                return 0 === arguments.length ? (n = this.selection.data("select2-data"), n == e && (n = null), n) : (arguments.length > 1 && (i = arguments[1]), void(t ? (n = this.data(), this.opts.element.val(t ? this.id(t) : ""), this.updateSelection(t), i && this.triggerChange({
                    "added": t,
                    "removed": n
                })) : this.clear(i)))
            }
        }),
        F = _(A, {
            "createContainer": function() {
                var e = t(document.createElement("div")).attr({
                    "class": "select2-container select2-container-multi"
                }).html(["<ul class='select2-choices'>", "  <li class='select2-search-field'>", "    <label for='' class='select2-offscreen'></label>", "    <input type='text' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' class='select2-input'>", "  </li>", "</ul>", "<div class='select2-drop select2-drop-multi select2-display-none'>", "   <ul class='select2-results'>", "   </ul>", "</div>"].join(""));
                return e
            },
            "prepareOpts": function() {
                var e = this.parent.prepareOpts.apply(this, arguments),
                n = this;
                return "select" === e.element.get(0).tagName.toLowerCase() ? e.initSelection = function(t, e) {
                    var i = [];
                    t.find("option").filter(function() {
                        return this.selected && !this.disabled
                    }).each2(function(t, e) {
                        i.push(n.optionToData(e))
                    }),
                    e(i)
                }: "data" in e && (e.initSelection = e.initSelection ||
                function(n, i) {
                    var r = o(n.val(), e.separator),
                    s = [];
                    e.query({
                        "matcher": function(n, i, o) {
                            var l = t.grep(r,
                            function(t) {
                                return a(t, e.id(o))
                            }).length;
                            return l && s.push(o),
                            l
                        },
                        "callback": t.isFunction(i) ?
                        function() {
                            for (var t = [], n = 0; n < r.length; n++) for (var o = r[n], l = 0; l < s.length; l++) {
                                var u = s[l];
                                if (a(o, e.id(u))) {
                                    t.push(u),
                                    s.splice(l, 1);
                                    break
                                }
                            }
                            i(t)
                        }: t.noop
                    })
                }),
                e
            },
            "selectChoice": function(t) {
                var e = this.container.find(".select2-search-choice-focus");
                e.length && t && t[0] == e[0] || (e.length && this.opts.element.trigger("choice-deselected", e), e.removeClass("select2-search-choice-focus"), t && t.length && (this.close(), t.addClass("select2-search-choice-focus"), this.opts.element.trigger("choice-selected", t)))
            },
            "destroy": function() {
                t("label[for='" + this.search.attr("id") + "']").attr("for", this.opts.element.attr("id")),
                this.parent.destroy.apply(this, arguments),
                E.call(this, "searchContainer", "selection")
            },
            "initContainer": function() {
                var e, n = ".select2-choices";
                this.searchContainer = this.container.find(".select2-search-field"),
                this.selection = e = this.container.find(n);
                var i = this;
                this.selection.on("click", ".select2-search-choice:not(.select2-locked)",
                function() {
                    i.search[0].focus(),
                    i.selectChoice(t(this))
                }),
                this.search.attr("id", "s2id_autogen" + N()),
                this.search.prev().text(t("label[for='" + this.opts.element.attr("id") + "']").text()).attr("for", this.search.attr("id")),
                this.search.on("input paste", this.bind(function() {
                    this.isInterfaceEnabled() && (this.opened() || this.open())
                })),
                this.search.attr("tabindex", this.elementTabIndex),
                this.keydowns = 0,
                this.search.on("keydown", this.bind(function(t) {
                    if (this.isInterfaceEnabled()) {++this.keydowns;
                        var n = e.find(".select2-search-choice-focus"),
                        i = n.prev(".select2-search-choice:not(.select2-locked)"),
                        r = n.next(".select2-search-choice:not(.select2-locked)"),
                        s = p(this.search);
                        if (n.length && (t.which == M.LEFT || t.which == M.RIGHT || t.which == M.BACKSPACE || t.which == M.DELETE || t.which == M.ENTER)) {
                            var a = n;
                            return t.which == M.LEFT && i.length ? a = i: t.which == M.RIGHT ? a = r.length ? r: null: t.which === M.BACKSPACE ? this.unselect(n.first()) && (this.search.width(10), a = i.length ? i: r) : t.which == M.DELETE ? this.unselect(n.first()) && (this.search.width(10), a = r.length ? r: null) : t.which == M.ENTER && (a = null),
                            this.selectChoice(a),
                            m(t),
                            void(a && a.length || this.open())
                        }
                        if ((t.which === M.BACKSPACE && 1 == this.keydowns || t.which == M.LEFT) && 0 == s.offset && !s.length) return this.selectChoice(e.find(".select2-search-choice:not(.select2-locked)").last()),
                        void m(t);
                        if (this.selectChoice(null), this.opened()) switch (t.which) {
                        case M.UP:
                        case M.DOWN:
                            return this.moveHighlight(t.which === M.UP ? -1 : 1),
                            void m(t);
                        case M.ENTER:
                            return this.selectHighlighted(),
                            void m(t);
                        case M.TAB:
                            return this.selectHighlighted({
                                "noFocus":
                                !0
                            }),
                            void this.close();
                        case M.ESC:
                            return this.cancel(t),
                            void m(t)
                        }
                        if (t.which !== M.TAB && !M.isControl(t) && !M.isFunctionKey(t) && t.which !== M.BACKSPACE && t.which !== M.ESC) {
                            if (t.which === M.ENTER) {
                                if (this.opts.openOnEnter === !1) return;
                                if (t.altKey || t.ctrlKey || t.shiftKey || t.metaKey) return
                            }
                            this.open(),
                            (t.which === M.PAGE_UP || t.which === M.PAGE_DOWN) && m(t),
                            t.which === M.ENTER && m(t)
                        }
                    }
                })),
                this.search.on("keyup", this.bind(function() {
                    this.keydowns = 0,
                    this.resizeSearch()
                })),
                this.search.on("blur", this.bind(function(e) {
                    this.container.removeClass("select2-container-active"),
                    this.search.removeClass("select2-focused"),
                    this.selectChoice(null),
                    this.opened() || this.clearSearch(),
                    e.stopImmediatePropagation(),
                    this.opts.element.trigger(t.Event("select2-blur"))
                })),
                this.container.on("click", n, this.bind(function(e) {
                    this.isInterfaceEnabled() && (t(e.target).closest(".select2-search-choice").length > 0 || (this.selectChoice(null), this.clearPlaceholder(), this.container.hasClass("select2-container-active") || this.opts.element.trigger(t.Event("select2-focus")), this.open(), this.focusSearch(), e.preventDefault()))
                })),
                this.container.on("focus", n, this.bind(function() {
                    this.isInterfaceEnabled() && (this.container.hasClass("select2-container-active") || this.opts.element.trigger(t.Event("select2-focus")), this.container.addClass("select2-container-active"), this.dropdown.addClass("select2-drop-active"), this.clearPlaceholder())
                })),
                this.initContainerWidth(),
                this.opts.element.addClass("select2-offscreen"),
                this.clearSearch()
            },
            "enableInterface": function() {
                this.parent.enableInterface.apply(this, arguments) && this.search.prop("disabled", !this.isInterfaceEnabled())
            },
            "initSelection": function() {
                if ("" === this.opts.element.val() && "" === this.opts.element.text() && (this.updateSelection([]), this.close(), this.clearSearch()), this.select || "" !== this.opts.element.val()) {
                    var t = this;
                    this.opts.initSelection.call(null, this.opts.element,
                    function(n) {
                        n !== e && null !== n && (t.updateSelection(n), t.close(), t.clearSearch())
                    })
                }
            },
            "clearSearch": function() {
                var t = this.getPlaceholder(),
                n = this.getMaxSearchWidth();
                t !== e && 0 === this.getVal().length && this.search.hasClass("select2-focused") === !1 ? (this.search.val(t).addClass("select2-default"), this.search.width(n > 0 ? n: this.container.css("width"))) : this.search.val("").width(10)
            },
            "clearPlaceholder": function() {
                this.search.hasClass("select2-default") && this.search.val("").removeClass("select2-default")
            },
            "opening": function() {
                this.clearPlaceholder(),
                this.resizeSearch(),
                this.parent.opening.apply(this, arguments),
                this.focusSearch(),
                "" === this.search.val() && this.nextSearchTerm != e && (this.search.val(this.nextSearchTerm), this.search.select()),
                this.updateResults(!0),
                this.opts.shouldFocusInput(this) && this.search.focus(),
                this.opts.element.trigger(t.Event("select2-open"))
            },
            "close": function() {
                this.opened() && this.parent.close.apply(this, arguments)
            },
            "focus": function() {
                this.close(),
                this.search.focus()
            },
            "isFocused": function() {
                return this.search.hasClass("select2-focused")
            },
            "updateSelection": function(e) {
                var n = [],
                i = [],
                s = this;
                t(e).each(function() {
                    r(s.id(this), n) < 0 && (n.push(s.id(this)), i.push(this))
                }),
                e = i,
                this.selection.find(".select2-search-choice").remove(),
                t(e).each(function() {
                    s.addSelectedChoice(this)
                }),
                s.postprocessResults()
            },
            "tokenize": function() {
                var t = this.search.val();
                t = this.opts.tokenizer.call(this, t, this.data(), this.bind(this.onSelect), this.opts),
                null != t && t != e && (this.search.val(t), t.length > 0 && this.open())
            },
            "onSelect": function(t, n) {
                this.triggerSelect(t) && (this.addSelectedChoice(t), this.opts.element.trigger({
                    "type": "selected",
                    "val": this.id(t),
                    "choice": t
                }), this.nextSearchTerm = this.opts.nextSearchTerm(t, this.search.val()), this.clearSearch(), this.updateResults(), (this.select || !this.opts.closeOnSelect) && this.postprocessResults(t, !1, this.opts.closeOnSelect === !0), this.opts.closeOnSelect ? (this.close(), this.search.width(10)) : this.countSelectableResults() > 0 ? (this.search.width(10), this.resizeSearch(), this.getMaximumSelectionSize() > 0 && this.val().length >= this.getMaximumSelectionSize() ? this.updateResults(!0) : this.nextSearchTerm != e && (this.search.val(this.nextSearchTerm), this.updateResults(), this.search.select()), this.positionDropdown()) : (this.close(), this.search.width(10)), this.triggerChange({
                    "added": t
                }), n && n.noFocus || this.focusSearch())
            },
            "cancel": function() {
                this.close(),
                this.focusSearch()
            },
            "addSelectedChoice": function(n) {
                var i, r, s = !n.locked,
                a = t("<li class='select2-search-choice'>    <div></div>    <a href='#' class='select2-search-choice-close' tabindex='-1'></a></li>"),
                o = t("<li class='select2-search-choice select2-locked'><div></div></li>"),
                l = s ? a: o,
                u = this.id(n),
                c = this.getVal();
                i = this.opts.formatSelection(n, l.find("div"), this.opts.escapeMarkup),
                i != e && l.find("div").replaceWith("<div>" + i + "</div>"),
                r = this.opts.formatSelectionCssClass(n, l.find("div")),
                r != e && l.addClass(r),
                s && l.find(".select2-search-choice-close").on("mousedown", m).on("click dblclick", this.bind(function(e) {
                    this.isInterfaceEnabled() && (this.unselect(t(e.target)), this.selection.find(".select2-search-choice-focus").removeClass("select2-search-choice-focus"), m(e), this.close(), this.focusSearch())
                })).on("focus", this.bind(function() {
                    this.isInterfaceEnabled() && (this.container.addClass("select2-container-active"), this.dropdown.addClass("select2-drop-active"))
                })),
                l.data("select2-data", n),
                l.insertBefore(this.searchContainer),
                c.push(u),
                this.setVal(c)
            },
            "unselect": function(e) {
                var n, i, s = this.getVal();
                if (e = e.closest(".select2-search-choice"), 0 === e.length) throw "Invalid argument: " + e + ". Must be .select2-search-choice";
                if (n = e.data("select2-data")) {
                    var a = t.Event("select2-removing");
                    if (a.val = this.id(n), a.choice = n, this.opts.element.trigger(a), a.isDefaultPrevented()) return ! 1;
                    for (; (i = r(this.id(n), s)) >= 0;) s.splice(i, 1),
                    this.setVal(s),
                    this.select && this.postprocessResults();
                    return e.remove(),
                    this.opts.element.trigger({
                        "type": "select2-removed",
                        "val": this.id(n),
                        "choice": n
                    }),
                    this.triggerChange({
                        "removed": n
                    }),
                    !0
                }
            },
            "postprocessResults": function(t, e, n) {
                var i = this.getVal(),
                s = this.results.find(".select2-result"),
                a = this.results.find(".select2-result-with-children"),
                o = this;
                s.each2(function(t, e) {
                    var n = o.id(e.data("select2-data"));
                    r(n, i) >= 0 && (e.addClass("select2-selected"), e.find(".select2-result-selectable").addClass("select2-selected"))
                }),
                a.each2(function(t, e) {
                    e.is(".select2-result-selectable") || 0 !== e.find(".select2-result-selectable:not(.select2-selected)").length || e.addClass("select2-selected")
                }),
                -1 == this.highlight() && n !== !1 && o.highlight(0),
                !this.opts.createSearchChoice && !s.filter(".select2-result:not(.select2-selected)").length > 0 && (!t || t && !t.more && 0 === this.results.find(".select2-no-results").length) && k(o.opts.formatNoMatches, "formatNoMatches") && this.results.append("<li class='select2-no-results'>" + T(o.opts.formatNoMatches, o.search.val()) + "</li>")
            },
            "getMaxSearchWidth": function() {
                return this.selection.width() - l(this.search)
            },
            "resizeSearch": function() {
                var t, e, n, i, r, s = l(this.search);
                t = v(this.search) + 10,
                e = this.search.offset().left,
                n = this.selection.width(),
                i = this.selection.offset().left,
                r = n - (e - i) - s,
                t > r && (r = n - s),
                40 > r && (r = n - s),
                0 >= r && (r = t),
                this.search.width(Math.floor(r))
            },
            "getVal": function() {
                var t;
                return this.select ? (t = this.select.val(), null === t ? [] : t) : (t = this.opts.element.val(), o(t, this.opts.separator))
            },
            "setVal": function(e) {
                var n;
                this.select ? this.select.val(e) : (n = [], t(e).each(function() {
                    r(this, n) < 0 && n.push(this)
                }), this.opts.element.val(0 === n.length ? "": n.join(this.opts.separator)))
            },
            "buildChangeDetails": function(t, e) {
                for (var e = e.slice(0), t = t.slice(0), n = 0; n < e.length; n++) for (var i = 0; i < t.length; i++) a(this.opts.id(e[n]), this.opts.id(t[i])) && (e.splice(n, 1), n > 0 && n--, t.splice(i, 1), i--);
                return {
                    "added": e,
                    "removed": t
                }
            },
            "val": function(n, i) {
                var r, s = this;
                if (0 === arguments.length) return this.getVal();
                if (r = this.data(), r.length || (r = []), !n && 0 !== n) return this.opts.element.val(""),
                this.updateSelection([]),
                this.clearSearch(),
                void(i && this.triggerChange({
                    "added": this.data(),
                    "removed": r
                }));
                if (this.setVal(n), this.select) this.opts.initSelection(this.select, this.bind(this.updateSelection)),
                i && this.triggerChange(this.buildChangeDetails(r, this.data()));
                else {
                    if (this.opts.initSelection === e) throw new Error("val() cannot be called if initSelection() is not defined");
                    this.opts.initSelection(this.opts.element,
                    function(e) {
                        var n = t.map(e, s.id);
                        s.setVal(n),
                        s.updateSelection(e),
                        s.clearSearch(),
                        i && s.triggerChange(s.buildChangeDetails(r, s.data()))
                    })
                }
                this.clearSearch()
            },
            "onSortStart": function() {
                if (this.select) throw new Error("Sorting of elements is not supported when attached to <select>. Attach to <input type='hidden'/> instead.");
                this.search.width(0),
                this.searchContainer.hide()
            },
            "onSortEnd": function() {
                var e = [],
                n = this;
                this.searchContainer.show(),
                this.searchContainer.appendTo(this.searchContainer.parent()),
                this.resizeSearch(),
                this.selection.find(".select2-search-choice").each(function() {
                    e.push(n.opts.id(t(this).data("select2-data")))
                }),
                this.setVal(e),
                this.triggerChange()
            },
            "data": function(e, n) {
                var i, r, s = this;
                return 0 === arguments.length ? this.selection.children(".select2-search-choice").map(function() {
                    return t(this).data("select2-data")
                }).get() : (r = this.data(), e || (e = []), i = t.map(e,
                function(t) {
                    return s.opts.id(t)
                }), this.setVal(i), this.updateSelection(e), this.clearSearch(), n && this.triggerChange(this.buildChangeDetails(r, this.data())), void 0)
            }
        }),
        t.fn.select2 = function() {
            var n, i, s, a, o, l = Array.prototype.slice.call(arguments, 0),
            u = ["val", "destroy", "opened", "open", "close", "focus", "isFocused", "container", "dropdown", "onSortStart", "onSortEnd", "enable", "disable", "readonly", "positionDropdown", "data", "search"],
            c = ["opened", "isFocused", "container", "dropdown"],
            h = ["val", "data"],
            d = {
                "search": "externalSearch"
            };
            return this.each(function() {
                if (0 === l.length || "object" == typeof l[0]) n = 0 === l.length ? {}: t.extend({},
                l[0]),
                n.element = t(this),
                "select" === n.element.get(0).tagName.toLowerCase() ? o = n.element.prop("multiple") : (o = n.multiple || !1, "tags" in n && (n.multiple = o = !0)),
                i = o ? new window.Select2["class"].multi: new window.Select2["class"].single,
                i.init(n);
                else {
                    if ("string" != typeof l[0]) throw "Invalid arguments to select2 plugin: " + l;
                    if (r(l[0], u) < 0) throw "Unknown method: " + l[0];
                    if (a = e, i = t(this).data("select2"), i === e) return;
                    if (s = l[0], "container" === s ? a = i.container: "dropdown" === s ? a = i.dropdown: (d[s] && (s = d[s]), a = i[s].apply(i, l.slice(1))), r(l[0], c) >= 0 || r(l[0], h) >= 0 && 1 == l.length) return ! 1
                }
            }),
            a === e ? this: a
        },
        t.fn.select2.defaults = {
            "width": "copy",
            "loadMorePadding": 0,
            "closeOnSelect": !0,
            "openOnEnter": !0,
            "containerCss": {},
            "dropdownCss": {},
            "containerCssClass": "",
            "dropdownCssClass": "",
            "formatResult": function(t, e, n, i) {
                var r = [];
                return b(t.text, n.term, r, i),
                r.join("")
            },
            "formatSelection": function(t, n, i) {
                return t ? i(t.text) : e
            },
            "sortResults": function(t) {
                return t
            },
            "formatResultCssClass": function(t) {
                return t.css
            },
            "formatSelectionCssClass": function() {
                return e
            },
            "formatMatches": function(t) {
                return t + " results are available, use up and down arrow keys to navigate."
            },
            "formatNoMatches": function() {
                return "No matches found"
            },
            "formatInputTooShort": function(t, e) {
                var n = e - t.length;
                return "Please enter " + n + " or more character" + (1 == n ? "": "s")
            },
            "formatInputTooLong": function(t, e) {
                var n = t.length - e;
                return "Please delete " + n + " character" + (1 == n ? "": "s")
            },
            "formatSelectionTooBig": function(t) {
                return "You can only select " + t + " item" + (1 == t ? "": "s")
            },
            "formatLoadMore": function() {
                return "Loading more results\u2026"
            },
            "formatSearching": function() {
                return "Searching\u2026"
            },
            "minimumResultsForSearch": 0,
            "minimumInputLength": 0,
            "maximumInputLength": null,
            "maximumSelectionSize": 0,
            "id": function(t) {
                return t == e ? null: t.id
            },
            "matcher": function(t, e) {
                return i("" + e).toUpperCase().indexOf(i("" + t).toUpperCase()) >= 0
            },
            "separator": ",",
            "tokenSeparators": [],
            "tokenizer": D,
            "escapeMarkup": w,
            "blurOnChange": !1,
            "selectOnBlur": !1,
            "adaptContainerCssClass": function(t) {
                return t
            },
            "adaptDropdownCssClass": function() {
                return null
            },
            "nextSearchTerm": function() {
                return e
            },
            "searchInputPlaceholder": "",
            "createSearchChoicePosition": "top",
            "shouldFocusInput": function(t) {
                var e = "ontouchstart" in window || navigator.msMaxTouchPoints > 0;
                return e && t.opts.minimumResultsForSearch < 0 ? !1 : !0
            }
        },
        t.fn.select2.ajaxDefaults = {
            "transport": t.ajax,
            "params": {
                "type": "GET",
                "cache": !1,
                "dataType": "json"
            }
        },
        window.Select2 = {
            "query": {
                "ajax": $,
                "local": x,
                "tags": C
            },
            "util": {
                "debounce": h,
                "markMatch": b,
                "escapeMarkup": w,
                "stripDiacritics": i
            },
            "class": {
                "abstract": A,
                "single": O,
                "multi": F
            }
        }
    }
} (jQuery),
function(t, e, n) {
    "use strict";
    function i(t) {
        return function() {
            var e, n = arguments[0],
            n = "[" + (t ? t + ":": "") + n + "] http://errors.angularjs.org/1.2.28/" + (t ? t + "/": "") + n;
            for (e = 1; e < arguments.length; e++) n = n + (1 == e ? "?": "&") + "p" + (e - 1) + "=" + encodeURIComponent("function" == typeof arguments[e] ? arguments[e].toString().replace(/ \{[\s\S]*$/, "") : "undefined" == typeof arguments[e] ? "undefined": "string" != typeof arguments[e] ? JSON.stringify(arguments[e]) : arguments[e]);
            return Error(n)
        }
    }
    function r(t) {
        if (null == t || T(t)) return ! 1;
        var e = t.length;
        return 1 === t.nodeType && e ? !0 : w(t) || ai(t) || 0 === e || "number" == typeof e && e > 0 && e - 1 in t
    }
    function s(t, e, n) {
        var i;
        if (t) if (C(t)) for (i in t)"prototype" == i || "length" == i || "name" == i || t.hasOwnProperty && !t.hasOwnProperty(i) || e.call(n, t[i], i);
        else if (ai(t) || r(t)) for (i = 0; i < t.length; i++) e.call(n, t[i], i);
        else if (t.forEach && t.forEach !== s) t.forEach(e, n);
        else for (i in t) t.hasOwnProperty(i) && e.call(n, t[i], i);
        return t
    }
    function a(t) {
        var e, n = [];
        for (e in t) t.hasOwnProperty(e) && n.push(e);
        return n.sort()
    }
    function o(t, e, n) {
        for (var i = a(t), r = 0; r < i.length; r++) e.call(n, t[i[r]], i[r]);
        return i
    }
    function l(t) {
        return function(e, n) {
            t(n, e)
        }
    }
    function u() {
        for (var t, e = si.length; e;) {
            if (e--, t = si[e].charCodeAt(0), 57 == t) return si[e] = "A",
            si.join("");
            if (90 != t) return si[e] = String.fromCharCode(t + 1),
            si.join("");
            si[e] = "0"
        }
        return si.unshift("0"),
        si.join("")
    }
    function c(t, e) {
        e ? t.$$hashKey = e: delete t.$$hashKey
    }
    function h(t) {
        var e = t.$$hashKey;
        return s(arguments,
        function(e) {
            e !== t && s(e,
            function(e, n) {
                t[n] = e
            })
        }),
        c(t, e),
        t
    }
    function d(t) {
        return parseInt(t, 10)
    }
    function f(t, e) {
        return h(new(h(function() {},
        {
            "prototype": t
        })), e)
    }
    function p() {}
    function m(t) {
        return t
    }
    function g(t) {
        return function() {
            return t
        }
    }
    function v(t) {
        return "undefined" == typeof t
    }
    function y(t) {
        return "undefined" != typeof t
    }
    function b(t) {
        return null != t && "object" == typeof t
    }
    function w(t) {
        return "string" == typeof t
    }
    function $(t) {
        return "number" == typeof t
    }
    function x(t) {
        return "[object Date]" === ni.call(t)
    }
    function C(t) {
        return "function" == typeof t
    }
    function k(t) {
        return "[object RegExp]" === ni.call(t)
    }
    function T(t) {
        return t && t.document && t.location && t.alert && t.setInterval
    }
    function S(t) {
        return ! (!t || !(t.nodeName || t.prop && t.attr && t.find))
    }
    function D(t, e, n) {
        var i = [];
        return s(t,
        function(t, r, s) {
            i.push(e.call(n, t, r, s))
        }),
        i
    }
    function E(t, e) {
        if (t.indexOf) return t.indexOf(e);
        for (var n = 0; n < t.length; n++) if (e === t[n]) return n;
        return - 1
    }
    function _(t, e) {
        var n = E(t, e);
        return n >= 0 && t.splice(n, 1),
        e
    }
    function M(t, e, n, i) {
        if (T(t) || t && t.$evalAsync && t.$watch) throw ii("cpws");
        if (e) {
            if (t === e) throw ii("cpi");
            if (n = n || [], i = i || [], b(t)) {
                var r = E(n, t);
                if ( - 1 !== r) return i[r];
                n.push(t),
                i.push(e)
            }
            if (ai(t)) for (var a = e.length = 0; a < t.length; a++) r = M(t[a], null, n, i),
            b(t[a]) && (n.push(t[a]), i.push(r)),
            e.push(r);
            else {
                var o = e.$$hashKey;
                ai(e) ? e.length = 0 : s(e,
                function(t, n) {
                    delete e[n]
                });
                for (a in t) r = M(t[a], null, n, i),
                b(t[a]) && (n.push(t[a]), i.push(r)),
                e[a] = r;
                c(e, o)
            }
        } else(e = t) && (ai(t) ? e = M(t, [], n, i) : x(t) ? e = new Date(t.getTime()) : k(t) ? (e = RegExp(t.source, t.toString().match(/[^\/]*$/)[0]), e.lastIndex = t.lastIndex) : b(t) && (e = M(t, {},
        n, i)));
        return e
    }
    function A(t, e) {
        if (ai(t)) {
            e = e || [];
            for (var n = 0; n < t.length; n++) e[n] = t[n]
        } else if (b(t)) for (n in e = e || {},
        t) ! Xn.call(t, n) || "$" === n.charAt(0) && "$" === n.charAt(1) || (e[n] = t[n]);
        return e || t
    }
    function O(t, e) {
        if (t === e) return ! 0;
        if (null === t || null === e) return ! 1;
        if (t !== t && e !== e) return ! 0;
        var i, r = typeof t;
        if (r == typeof e && "object" == r) {
            if (!ai(t)) {
                if (x(t)) return x(e) ? isNaN(t.getTime()) && isNaN(e.getTime()) || t.getTime() === e.getTime() : !1;
                if (k(t) && k(e)) return t.toString() == e.toString();
                if (t && t.$evalAsync && t.$watch || e && e.$evalAsync && e.$watch || T(t) || T(e) || ai(e)) return ! 1;
                r = {};
                for (i in t) if ("$" !== i.charAt(0) && !C(t[i])) {
                    if (!O(t[i], e[i])) return ! 1;
                    r[i] = !0
                }
                for (i in e) if (!r.hasOwnProperty(i) && "$" !== i.charAt(0) && e[i] !== n && !C(e[i])) return ! 1;
                return ! 0
            }
            if (!ai(e)) return ! 1;
            if ((r = t.length) == e.length) {
                for (i = 0; r > i; i++) if (!O(t[i], e[i])) return ! 1;
                return ! 0
            }
        }
        return ! 1
    }
    function F(t, e) {
        var n = 2 < arguments.length ? ti.call(arguments, 2) : [];
        return ! C(e) || e instanceof RegExp ? e: n.length ?
        function() {
            return arguments.length ? e.apply(t, n.concat(ti.call(arguments, 0))) : e.apply(t, n)
        }: function() {
            return arguments.length ? e.apply(t, arguments) : e.call(t)
        }
    }
    function N(t, i) {
        var r = i;
        return "string" == typeof t && "$" === t.charAt(0) ? r = n: T(i) ? r = "$WINDOW": i && e === i ? r = "$DOCUMENT": i && i.$evalAsync && i.$watch && (r = "$SCOPE"),
        r
    }
    function j(t, e) {
        return "undefined" == typeof t ? n: JSON.stringify(t, N, e ? "  ": null)
    }
    function P(t) {
        return w(t) ? JSON.parse(t) : t
    }
    function I(t) {
        return "function" == typeof t ? t = !0 : t && 0 !== t.length ? (t = Jn("" + t), t = !("f" == t || "0" == t || "false" == t || "no" == t || "n" == t || "[]" == t)) : t = !1,
        t
    }
    function L(t) {
        t = zn(t).clone();
        try {
            t.empty()
        } catch(e) {}
        var n = zn("<div>").append(t).html();
        try {
            return 3 === t[0].nodeType ? Jn(n) : n.match(/^(<[^>]+>)/)[1].replace(/^<([\w\-]+)/,
            function(t, e) {
                return "<" + Jn(e)
            })
        } catch(i) {
            return Jn(n)
        }
    }
    function H(t) {
        try {
            return decodeURIComponent(t)
        } catch(e) {}
    }
    function R(t) {
        var e, n, i = {};
        return s((t || "").split("&"),
        function(t) {
            t && (e = t.replace(/\+/g, "%20").split("="), n = H(e[0]), y(n) && (t = y(e[1]) ? H(e[1]) : !0, Xn.call(i, n) ? ai(i[n]) ? i[n].push(t) : i[n] = [i[n], t] : i[n] = t))
        }),
        i
    }
    function U(t) {
        var e = [];
        return s(t,
        function(t, n) {
            ai(t) ? s(t,
            function(t) {
                e.push(Y(n, !0) + (!0 === t ? "": "=" + Y(t, !0)))
            }) : e.push(Y(n, !0) + (!0 === t ? "": "=" + Y(t, !0)))
        }),
        e.length ? e.join("&") : ""
    }
    function q(t) {
        return Y(t, !0).replace(/%26/gi, "&").replace(/%3D/gi, "=").replace(/%2B/gi, "+")
    }
    function Y(t, e) {
        return encodeURIComponent(t).replace(/%40/gi, "@").replace(/%3A/gi, ":").replace(/%24/g, "$").replace(/%2C/gi, ",").replace(/%20/g, e ? "%20": "+")
    }
    function V(t, n) {
        function i(t) {
            t && o.push(t)
        }
        var r, a, o = [t],
        l = ["ng:app", "ng-app", "x-ng-app", "data-ng-app"],
        u = /\sng[:\-]app(:\s*([\w\d_]+);?)?\s/;
        s(l,
        function(n) {
            l[n] = !0,
            i(e.getElementById(n)),
            n = n.replace(":", "\\:"),
            t.querySelectorAll && (s(t.querySelectorAll("." + n), i), s(t.querySelectorAll("." + n + "\\:"), i), s(t.querySelectorAll("[" + n + "]"), i))
        }),
        s(o,
        function(t) {
            if (!r) {
                var e = u.exec(" " + t.className + " ");
                e ? (r = t, a = (e[2] || "").replace(/\s+/g, ",")) : s(t.attributes,
                function(e) { ! r && l[e.name] && (r = t, a = e.value)
                })
            }
        }),
        r && n(r, a ? [a] : [])
    }
    function W(n, i) {
        var r = function() {
            if (n = zn(n), n.injector()) {
                var t = n[0] === e ? "document": L(n);
                throw ii("btstrpd", t.replace(/</, "&lt;").replace(/>/, "&gt;"))
            }
            return i = i || [],
            i.unshift(["$provide",
            function(t) {
                t.value("$rootElement", n)
            }]),
            i.unshift("ng"),
            t = $e(i),
            t.invoke(["$rootScope", "$rootElement", "$compile", "$injector", "$animate",
            function(t, e, n, i) {
                t.$apply(function() {
                    e.data("$injector", i),
                    n(e)(t)
                })
            }]),
            t
        },
        a = /^NG_DEFER_BOOTSTRAP!/;
        return t && !a.test(t.name) ? r() : (t.name = t.name.replace(a, ""), void(ri.resumeBootstrap = function(t) {
            s(t,
            function(t) {
                i.push(t)
            }),
            r()
        }))
    }
    function z(t, e) {
        return e = e || "_",
        t.replace(ui,
        function(t, n) {
            return (n ? e: "") + t.toLowerCase()
        })
    }
    function B(t, e, n) {
        if (!t) throw ii("areq", e || "?", n || "required");
        return t
    }
    function G(t, e, n) {
        return n && ai(t) && (t = t[t.length - 1]),
        B(C(t), e, "not a function, got " + (t && "object" == typeof t ? t.constructor.name || "Object": typeof t)),
        t
    }
    function Q(t, e) {
        if ("hasOwnProperty" === t) throw ii("badname", e)
    }
    function Z(t, e, n) {
        if (!e) return t;
        e = e.split(".");
        for (var i, r = t,
        s = e.length,
        a = 0; s > a; a++) i = e[a],
        t && (t = (r = t)[i]);
        return ! n && C(t) ? F(r, t) : t
    }
    function J(t) {
        var e = t[0];
        if (t = t[t.length - 1], e === t) return zn(e);
        var n = [e];
        do {
            if (e = e.nextSibling, !e) break;
            n.push(e)
        } while ( e !== t );
        return zn(n)
    }
    function X(t) {
        var e = i("$injector"),
        n = i("ng");
        return t = t.angular || (t.angular = {}),
        t.$$minErr = t.$$minErr || i,
        t.module || (t.module = function() {
            var t = {};
            return function(i, r, s) {
                if ("hasOwnProperty" === i) throw n("badname", "module");
                return r && t.hasOwnProperty(i) && (t[i] = null),
                t[i] || (t[i] = function() {
                    function t(t, e, i) {
                        return function() {
                            return n[i || "push"]([t, e, arguments]),
                            l
                        }
                    }
                    if (!r) throw e("nomod", i);
                    var n = [],
                    a = [],
                    o = t("$injector", "invoke"),
                    l = {
                        "_invokeQueue": n,
                        "_runBlocks": a,
                        "requires": r,
                        "name": i,
                        "provider": t("$provide", "provider"),
                        "factory": t("$provide", "factory"),
                        "service": t("$provide", "service"),
                        "value": t("$provide", "value"),
                        "constant": t("$provide", "constant", "unshift"),
                        "animation": t("$animateProvider", "register"),
                        "filter": t("$filterProvider", "register"),
                        "controller": t("$controllerProvider", "register"),
                        "directive": t("$compileProvider", "directive"),
                        "config": o,
                        "run": function(t) {
                            return a.push(t),
                            this
                        }
                    };
                    return s && o(s),
                    l
                } ())
            }
        } ())
    }
    function K(e) {
        h(e, {
            "bootstrap": W,
            "copy": M,
            "extend": h,
            "equals": O,
            "element": zn,
            "forEach": s,
            "injector": $e,
            "noop": p,
            "bind": F,
            "toJson": j,
            "fromJson": P,
            "identity": m,
            "isUndefined": v,
            "isDefined": y,
            "isString": w,
            "isFunction": C,
            "isObject": b,
            "isNumber": $,
            "isElement": S,
            "isArray": ai,
            "version": ci,
            "isDate": x,
            "lowercase": Jn,
            "uppercase": Kn,
            "callbacks": {
                "counter": 0
            },
            "$$minErr": i,
            "$$csp": li
        }),
        Gn = X(t);
        try {
            Gn("ngLocale")
        } catch(n) {
            Gn("ngLocale", []).provider("$locale", Ye)
        }
        Gn("ng", ["ngLocale"], ["$provide",
        function(t) {
            t.provider({
                "$$sanitizeUri": gn
            }),
            t.provider("$compile", Ee).directive({
                "a": or,
                "input": yr,
                "textarea": yr,
                "form": hr,
                "script": ts,
                "select": is,
                "style": ss,
                "option": rs,
                "ngBind": Mr,
                "ngBindHtml": Or,
                "ngBindTemplate": Ar,
                "ngClass": Fr,
                "ngClassEven": jr,
                "ngClassOdd": Nr,
                "ngCloak": Pr,
                "ngController": Ir,
                "ngForm": dr,
                "ngHide": Gr,
                "ngIf": Rr,
                "ngInclude": Ur,
                "ngInit": Yr,
                "ngNonBindable": Vr,
                "ngPluralize": Wr,
                "ngRepeat": zr,
                "ngShow": Br,
                "ngStyle": Qr,
                "ngSwitch": Zr,
                "ngSwitchWhen": Jr,
                "ngSwitchDefault": Xr,
                "ngOptions": ns,
                "ngTransclude": Kr,
                "ngModel": kr,
                "ngList": Dr,
                "ngChange": Tr,
                "required": Sr,
                "ngRequired": Sr,
                "ngValue": _r
            }).directive({
                "ngInclude": qr
            }).directive(lr).directive(Lr),
            t.provider({
                "$anchorScroll": xe,
                "$animate": Oi,
                "$browser": Te,
                "$cacheFactory": Se,
                "$controller": Ae,
                "$document": Oe,
                "$exceptionHandler": Fe,
                "$filter": Sn,
                "$interpolate": Ue,
                "$interval": qe,
                "$http": Ie,
                "$httpBackend": He,
                "$location": en,
                "$log": nn,
                "$parse": hn,
                "$rootScope": mn,
                "$q": dn,
                "$sce": wn,
                "$sceDelegate": bn,
                "$sniffer": $n,
                "$templateCache": De,
                "$timeout": xn,
                "$window": Tn,
                "$$rAF": pn,
                "$$asyncCallback": Ce
            })
        }])
    }
    function te(t) {
        return t.replace(mi,
        function(t, e, n, i) {
            return i ? n.toUpperCase() : n
        }).replace(gi, "Moz$1")
    }
    function ee(t, e, n, i) {
        function r(t) {
            var r, a, o, l, u, c, h = n && t ? [this.filter(t)] : [this],
            d = e;
            if (!i || null != t) for (; h.length;) for (r = h.shift(), a = 0, o = r.length; o > a; a++) for (l = zn(r[a]), d ? l.triggerHandler("$destroy") : d = !d, u = 0, l = (c = l.children()).length; l > u; u++) h.push(Bn(c[u]));
            return s.apply(this, arguments)
        }
        var s = Bn.fn[t],
        s = s.$original || s;
        r.$original = s,
        Bn.fn[t] = r
    }
    function ne(t) {
        if (t instanceof ne) return t;
        if (w(t) && (t = oi(t)), !(this instanceof ne)) {
            if (w(t) && "<" != t.charAt(0)) throw vi("nosel");
            return new ne(t)
        }
        if (w(t)) {
            var n = t;
            t = e;
            var i;
            if (i = yi.exec(n)) t = [t.createElement(i[1])];
            else {
                var r, s = t;
                if (t = s.createDocumentFragment(), i = [], bi.test(n)) {
                    for (s = t.appendChild(s.createElement("div")), r = (wi.exec(n) || ["", ""])[1].toLowerCase(), r = xi[r] || xi._default, s.innerHTML = "<div>&#160;</div>" + r[1] + n.replace($i, "<$1></$2>") + r[2], s.removeChild(s.firstChild), n = r[0]; n--;) s = s.lastChild;
                    for (n = 0, r = s.childNodes.length; r > n; ++n) i.push(s.childNodes[n]);
                    s = t.firstChild,
                    s.textContent = ""
                } else i.push(s.createTextNode(n));
                t.textContent = "",
                t.innerHTML = "",
                t = i
            }
            de(this, t),
            zn(e.createDocumentFragment()).append(this)
        } else de(this, t)
    }
    function ie(t) {
        return t.cloneNode(!0)
    }
    function re(t) {
        ae(t);
        var e = 0;
        for (t = t.childNodes || []; e < t.length; e++) re(t[e])
    }
    function se(t, e, n, i) {
        if (y(i)) throw vi("offargs");
        var r = oe(t, "events");
        oe(t, "handle") && (v(e) ? s(r,
        function(e, n) {
            pi(t, n, e),
            delete r[n]
        }) : s(e.split(" "),
        function(e) {
            v(n) ? (pi(t, e, r[e]), delete r[e]) : _(r[e] || [], n)
        }))
    }
    function ae(t, e) {
        var i = t.ng339,
        r = hi[i];
        r && (e ? delete hi[i].data[e] : (r.handle && (r.events.$destroy && r.handle({},
        "$destroy"), se(t)), delete hi[i], t.ng339 = n))
    }
    function oe(t, e, n) {
        var i = t.ng339,
        i = hi[i || -1];
        return y(n) ? (i || (t.ng339 = i = ++di, i = hi[i] = {}), void(i[e] = n)) : i && i[e]
    }
    function le(t, e, n) {
        var i = oe(t, "data"),
        r = y(n),
        s = !r && y(e),
        a = s && !b(e);
        if (i || a || oe(t, "data", i = {}), r) i[e] = n;
        else {
            if (!s) return i;
            if (a) return i && i[e];
            h(i, e)
        }
    }
    function ue(t, e) {
        return t.getAttribute ? -1 < (" " + (t.getAttribute("class") || "") + " ").replace(/[\n\t]/g, " ").indexOf(" " + e + " ") : !1
    }
    function ce(t, e) {
        e && t.setAttribute && s(e.split(" "),
        function(e) {
            t.setAttribute("class", oi((" " + (t.getAttribute("class") || "") + " ").replace(/[\n\t]/g, " ").replace(" " + oi(e) + " ", " ")))
        })
    }
    function he(t, e) {
        if (e && t.setAttribute) {
            var n = (" " + (t.getAttribute("class") || "") + " ").replace(/[\n\t]/g, " ");
            s(e.split(" "),
            function(t) {
                t = oi(t),
                -1 === n.indexOf(" " + t + " ") && (n += t + " ")
            }),
            t.setAttribute("class", oi(n))
        }
    }
    function de(t, e) {
        if (e) {
            e = e.nodeName || !y(e.length) || T(e) ? [e] : e;
            for (var n = 0; n < e.length; n++) t.push(e[n])
        }
    }
    function fe(t, e) {
        return pe(t, "$" + (e || "ngController") + "Controller")
    }
    function pe(t, e, i) {
        for (9 == t.nodeType && (t = t.documentElement), e = ai(e) ? e: [e]; t;) {
            for (var r = 0,
            s = e.length; s > r; r++) if ((i = zn.data(t, e[r])) !== n) return i;
            t = t.parentNode || 11 === t.nodeType && t.host
        }
    }
    function me(t) {
        for (var e = 0,
        n = t.childNodes; e < n.length; e++) re(n[e]);
        for (; t.firstChild;) t.removeChild(t.firstChild)
    }
    function ge(t, e) {
        var n = ki[e.toLowerCase()];
        return n && Ti[t.nodeName] && n
    }
    function ve(t, n) {
        var i = function(i, r) {
            if (i.preventDefault || (i.preventDefault = function() {
                i.returnValue = !1
            }), i.stopPropagation || (i.stopPropagation = function() {
                i.cancelBubble = !0
            }), i.target || (i.target = i.srcElement || e), v(i.defaultPrevented)) {
                var a = i.preventDefault;
                i.preventDefault = function() {
                    i.defaultPrevented = !0,
                    a.call(i)
                },
                i.defaultPrevented = !1
            }
            i.isDefaultPrevented = function() {
                return i.defaultPrevented || !1 === i.returnValue
            };
            var o = A(n[r || i.type] || []);
            s(o,
            function(e) {
                e.call(t, i)
            }),
            8 >= Wn ? (i.preventDefault = null, i.stopPropagation = null, i.isDefaultPrevented = null) : (delete i.preventDefault, delete i.stopPropagation, delete i.isDefaultPrevented)
        };
        return i.elem = t,
        i
    }
    function ye(t, e) {
        var i, r = typeof t;
        return "function" == r || "object" == r && null !== t ? "function" == typeof(i = t.$$hashKey) ? i = t.$$hashKey() : i === n && (i = t.$$hashKey = (e || u)()) : i = t,
        r + ":" + i
    }
    function be(t, e) {
        if (e) {
            var n = 0;
            this.nextUid = function() {
                return++n
            }
        }
        s(t, this.put, this)
    }
    function we(t) {
        var e, n;
        return "function" == typeof t ? (e = t.$inject) || (e = [], t.length && (n = t.toString().replace(_i, ""), n = n.match(Si), s(n[1].split(Di),
        function(t) {
            t.replace(Ei,
            function(t, n, i) {
                e.push(i)
            })
        })), t.$inject = e) : ai(t) ? (n = t.length - 1, G(t[n], "fn"), e = t.slice(0, n)) : G(t, "fn", !0),
        e
    }
    function $e(t) {
        function e(t) {
            return function(e, n) {
                return b(e) ? void s(e, l(t)) : t(e, n)
            }
        }
        function n(t, e) {
            if (Q(t, "service"), (C(e) || ai(e)) && (e = f.instantiate(e)), !e.$get) throw Mi("pget", t);
            return d[t + u] = e
        }
        function i(t, e) {
            return n(t, {
                "$get": e
            })
        }
        function r(t) {
            var e, n, i, a, o = [];
            return s(t,
            function(t) {
                if (!h.get(t)) {
                    h.put(t, !0);
                    try {
                        if (w(t)) for (e = Gn(t), o = o.concat(r(e.requires)).concat(e._runBlocks), n = e._invokeQueue, i = 0, a = n.length; a > i; i++) {
                            var s = n[i],
                            l = f.get(s[0]);
                            l[s[1]].apply(l, s[2])
                        } else C(t) ? o.push(f.invoke(t)) : ai(t) ? o.push(f.invoke(t)) : G(t, "module")
                    } catch(u) {
                        throw ai(t) && (t = t[t.length - 1]),
                        u.message && u.stack && -1 == u.stack.indexOf(u.message) && (u = u.message + "\n" + u.stack),
                        Mi("modulerr", t, u.stack || u.message || u)
                    }
                }
            }),
            o
        }
        function a(t, e) {
            function n(n) {
                if (t.hasOwnProperty(n)) {
                    if (t[n] === o) throw Mi("cdep", n + " <- " + c.join(" <- "));
                    return t[n]
                }
                try {
                    return c.unshift(n),
                    t[n] = o,
                    t[n] = e(n)
                } catch(i) {
                    throw t[n] === o && delete t[n],
                    i
                } finally {
                    c.shift()
                }
            }
            function i(t, e, i) {
                var r, s, a, o = [],
                l = we(t);
                for (s = 0, r = l.length; r > s; s++) {
                    if (a = l[s], "string" != typeof a) throw Mi("itkn", a);
                    o.push(i && i.hasOwnProperty(a) ? i[a] : n(a))
                }
                return ai(t) && (t = t[r]),
                t.apply(e, o)
            }
            return {
                "invoke": i,
                "instantiate": function(t, e) {
                    var n, r = function() {};
                    return r.prototype = (ai(t) ? t[t.length - 1] : t).prototype,
                    r = new r,
                    n = i(t, r, e),
                    b(n) || C(n) ? n: r
                },
                "get": n,
                "annotate": we,
                "has": function(e) {
                    return d.hasOwnProperty(e + u) || t.hasOwnProperty(e)
                }
            }
        }
        var o = {},
        u = "Provider",
        c = [],
        h = new be([], !0),
        d = {
            "$provide": {
                "provider": e(n),
                "factory": e(i),
                "service": e(function(t, e) {
                    return i(t, ["$injector",
                    function(t) {
                        return t.instantiate(e)
                    }])
                }),
                "value": e(function(t, e) {
                    return i(t, g(e))
                }),
                "constant": e(function(t, e) {
                    Q(t, "constant"),
                    d[t] = e,
                    m[t] = e
                }),
                "decorator": function(t, e) {
                    var n = f.get(t + u),
                    i = n.$get;
                    n.$get = function() {
                        var t = v.invoke(i, n);
                        return v.invoke(e, null, {
                            "$delegate": t
                        })
                    }
                }
            }
        },
        f = d.$injector = a(d,
        function() {
            throw Mi("unpr", c.join(" <- "))
        }),
        m = {},
        v = m.$injector = a(m,
        function(t) {
            return t = f.get(t + u),
            v.invoke(t.$get, t)
        });
        return s(r(t),
        function(t) {
            v.invoke(t || p)
        }),
        v
    }
    function xe() {
        var t = !0;
        this.disableAutoScrolling = function() {
            t = !1
        },
        this.$get = ["$window", "$location", "$rootScope",
        function(e, n, i) {
            function r(t) {
                var e = null;
                return s(t,
                function(t) {
                    e || "a" !== Jn(t.nodeName) || (e = t)
                }),
                e
            }
            function a() {
                var t, i = n.hash();
                i ? (t = o.getElementById(i)) ? t.scrollIntoView() : (t = r(o.getElementsByName(i))) ? t.scrollIntoView() : "top" === i && e.scrollTo(0, 0) : e.scrollTo(0, 0)
            }
            var o = e.document;
            return t && i.$watch(function() {
                return n.hash()
            },
            function() {
                i.$evalAsync(a)
            }),
            a
        }]
    }
    function Ce() {
        this.$get = ["$$rAF", "$timeout",
        function(t, e) {
            return t.supported ?
            function(e) {
                return t(e)
            }: function(t) {
                return e(t, 0, !1)
            }
        }]
    }
    function ke(t, e, i, r) {
        function a(t) {
            try {
                t.apply(null, ti.call(arguments, 1))
            } finally {
                if (y--, 0 === y) for (; b.length;) try {
                    b.pop()()
                } catch(e) {
                    i.error(e)
                }
            }
        }
        function o(t, e) { !
            function n() {
                s(x,
                function(t) {
                    t()
                }),
                $ = e(n, t)
            } ()
        }
        function l() {
            C != u.url() && (C = u.url(), s(S,
            function(t) {
                t(u.url())
            }))
        }
        var u = this,
        c = e[0],
        h = t.location,
        d = t.history,
        f = t.setTimeout,
        m = t.clearTimeout,
        g = {};
        u.isMock = !1;
        var y = 0,
        b = [];
        u.$$completeOutstandingRequest = a,
        u.$$incOutstandingRequestCount = function() {
            y++
        },
        u.notifyWhenNoOutstandingRequests = function(t) {
            s(x,
            function(t) {
                t()
            }),
            0 === y ? t() : b.push(t)
        };
        var $, x = [];
        u.addPollFn = function(t) {
            return v($) && o(100, f),
            x.push(t),
            t
        };
        var C = h.href,
        k = e.find("base"),
        T = null;
        u.url = function(e, n) {
            if (h !== t.location && (h = t.location), d !== t.history && (d = t.history), !e) return T || h.href.replace(/%27/g, "'");
            if (C != e) {
                var i = C && Ge(C) === Ge(e);
                return C = e,
                !i && r.history ? n ? d.replaceState(null, "", e) : (d.pushState(null, "", e), k.attr("href", k.attr("href"))) : (i || (T = e), n ? h.replace(e) : h.href = e),
                u
            }
        };
        var S = [],
        D = !1;
        u.onUrlChange = function(e) {
            return D || (r.history && zn(t).on("popstate", l), r.hashchange ? zn(t).on("hashchange", l) : u.addPollFn(l), D = !0),
            S.push(e),
            e
        },
        u.$$checkUrlChange = l,
        u.baseHref = function() {
            var t = k.attr("href");
            return t ? t.replace(/^(https?\:)?\/\/[^\/]*/, "") : ""
        };
        var E = {},
        _ = "",
        M = u.baseHref();
        u.cookies = function(t, e) {
            var r, s, a, o;
            if (!t) {
                if (c.cookie !== _) for (_ = c.cookie, r = _.split("; "), E = {},
                a = 0; a < r.length; a++) s = r[a],
                o = s.indexOf("="),
                o > 0 && (t = unescape(s.substring(0, o)), E[t] === n && (E[t] = unescape(s.substring(o + 1))));
                return E
            }
            e === n ? c.cookie = escape(t) + "=;path=" + M + ";expires=Thu, 01 Jan 1970 00:00:00 GMT": w(e) && (r = (c.cookie = escape(t) + "=" + escape(e) + ";path=" + M).length + 1, r > 4096 && i.warn("Cookie '" + t + "' possibly not set or overflowed because it was too large (" + r + " > 4096 bytes)!"))
        },
        u.defer = function(t, e) {
            var n;
            return y++,
            n = f(function() {
                delete g[n],
                a(t)
            },
            e || 0),
            g[n] = !0,
            n
        },
        u.defer.cancel = function(t) {
            return g[t] ? (delete g[t], m(t), a(p), !0) : !1
        }
    }
    function Te() {
        this.$get = ["$window", "$log", "$sniffer", "$document",
        function(t, e, n, i) {
            return new ke(t, i, e, n)
        }]
    }
    function Se() {
        this.$get = function() {
            function t(t, n) {
                function r(t) {
                    t != d && (f ? f == t && (f = t.n) : f = t, s(t.n, t.p), s(t, d), d = t, d.n = null)
                }
                function s(t, e) {
                    t != e && (t && (t.p = e), e && (e.n = t))
                }
                if (t in e) throw i("$cacheFactory")("iid", t);
                var a = 0,
                o = h({},
                n, {
                    "id": t
                }),
                l = {},
                u = n && n.capacity || Number.MAX_VALUE,
                c = {},
                d = null,
                f = null;
                return e[t] = {
                    "put": function(t, e) {
                        if (u < Number.MAX_VALUE) {
                            var n = c[t] || (c[t] = {
                                "key": t
                            });
                            r(n)
                        }
                        return v(e) ? void 0 : (t in l || a++, l[t] = e, a > u && this.remove(f.key), e)
                    },
                    "get": function(t) {
                        if (u < Number.MAX_VALUE) {
                            var e = c[t];
                            if (!e) return;
                            r(e)
                        }
                        return l[t]
                    },
                    "remove": function(t) {
                        if (u < Number.MAX_VALUE) {
                            var e = c[t];
                            if (!e) return;
                            e == d && (d = e.p),
                            e == f && (f = e.n),
                            s(e.n, e.p),
                            delete c[t]
                        }
                        delete l[t],
                        a--
                    },
                    "removeAll": function() {
                        l = {},
                        a = 0,
                        c = {},
                        d = f = null
                    },
                    "destroy": function() {
                        c = o = l = null,
                        delete e[t]
                    },
                    "info": function() {
                        return h({},
                        o, {
                            "size": a
                        })
                    }
                }
            }
            var e = {};
            return t.info = function() {
                var t = {};
                return s(e,
                function(e, n) {
                    t[n] = e.info()
                }),
                t
            },
            t.get = function(t) {
                return e[t]
            },
            t
        }
    }
    function De() {
        this.$get = ["$cacheFactory",
        function(t) {
            return t("templates")
        }]
    }
    function Ee(t, i) {
        var r = {},
        a = "Directive",
        o = /^\s*directive\:\s*([\d\w_\-]+)\s+(.*)$/,
        u = /(([\d\w_\-]+)(?:\:([^;]+))?;?)/,
        c = /^(on[a-z]+|formaction)$/;
        this.directive = function d(e, n) {
            return Q(e, "directive"),
            w(e) ? (B(n, "directiveFactory"), r.hasOwnProperty(e) || (r[e] = [], t.factory(e + a, ["$injector", "$exceptionHandler",
            function(t, n) {
                var i = [];
                return s(r[e],
                function(r, s) {
                    try {
                        var a = t.invoke(r);
                        C(a) ? a = {
                            "compile": g(a)
                        }: !a.compile && a.link && (a.compile = g(a.link)),
                        a.priority = a.priority || 0,
                        a.index = s,
                        a.name = a.name || e,
                        a.require = a.require || a.controller && a.name,
                        a.restrict = a.restrict || "A",
                        i.push(a)
                    } catch(o) {
                        n(o)
                    }
                }),
                i
            }])), r[e].push(n)) : s(e, l(d)),
            this
        },
        this.aHrefSanitizationWhitelist = function(t) {
            return y(t) ? (i.aHrefSanitizationWhitelist(t), this) : i.aHrefSanitizationWhitelist()
        },
        this.imgSrcSanitizationWhitelist = function(t) {
            return y(t) ? (i.imgSrcSanitizationWhitelist(t), this) : i.imgSrcSanitizationWhitelist()
        },
        this.$get = ["$injector", "$interpolate", "$exceptionHandler", "$http", "$templateCache", "$parse", "$controller", "$rootScope", "$document", "$sce", "$animate", "$$sanitizeUri",
        function(t, i, l, d, p, g, v, y, $, x, k, T) {
            function S(t, e, n, i, r) {
                t instanceof zn || (t = zn(t)),
                s(t,
                function(e, n) {
                    3 == e.nodeType && e.nodeValue.match(/\S+/) && (t[n] = zn(e).wrap("<span></span>").parent()[0])
                });
                var a = E(t, e, t, n, i, r);
                return D(t, "ng-scope"),
                function(e, n, i, r) {
                    B(e, "scope");
                    var o = n ? Ci.clone.call(t) : t;
                    s(i,
                    function(t, e) {
                        o.data("$" + e + "Controller", t)
                    }),
                    i = 0;
                    for (var l = o.length; l > i; i++) {
                        var u = o[i].nodeType;
                        1 !== u && 9 !== u || o.eq(i).data("$scope", e)
                    }
                    return n && n(o, e),
                    a && a(e, o, o, r),
                    o
                }
            }
            function D(t, e) {
                try {
                    t.addClass(e)
                } catch(n) {}
            }
            function E(t, e, i, r, s, a) {
                function o(t, i, r, s) {
                    var a, o, l, u, c, h, f;
                    a = i.length;
                    var p = Array(a);
                    for (u = 0; a > u; u++) p[u] = i[u];
                    for (h = u = 0, c = d.length; c > u; h++) o = p[h],
                    i = d[u++],
                    a = d[u++],
                    i ? (i.scope ? (l = t.$new(), zn.data(o, "$scope", l)) : l = t, f = i.transcludeOnThisElement ? _(t, i.transclude, s) : !i.templateOnThisElement && s ? s: !s && e ? _(t, e) : null, i(a, l, o, r, f)) : a && a(t, o.childNodes, n, s)
                }
                for (var l, u, c, h, d = [], f = 0; f < t.length; f++) l = new Z,
                u = M(t[f], [], l, 0 === f ? r: n, s),
                (a = u.length ? j(u, t[f], l, e, i, null, [], [], a) : null) && a.scope && D(l.$$element, "ng-scope"),
                l = a && a.terminal || !(c = t[f].childNodes) || !c.length ? null: E(c, a ? (a.transcludeOnThisElement || !a.templateOnThisElement) && a.transclude: e),
                d.push(a, l),
                h = h || a || l,
                a = null;
                return h ? o: null
            }
            function _(t, e, n) {
                return function(i, r, s) {
                    var a = !1;
                    return i || (i = t.$new(), a = i.$$transcluded = !0),
                    r = e(i, r, s, n),
                    a && r.on("$destroy",
                    function() {
                        i.$destroy()
                    }),
                    r
                }
            }
            function M(t, e, n, i, r) {
                var s, a = n.$attr;
                switch (t.nodeType) {
                case 1:
                    I(e, _e(Qn(t).toLowerCase()), "E", i, r);
                    for (var l, c, h, d = t.attributes,
                    f = 0,
                    p = d && d.length; p > f; f++) {
                        var m = !1,
                        g = !1;
                        if (l = d[f], !Wn || Wn >= 8 || l.specified) {
                            s = l.name,
                            c = oi(l.value),
                            l = _e(s),
                            (h = te.test(l)) && (s = z(l.substr(6), "-"));
                            var v = l.replace(/(Start|End)$/, "");
                            l === v + "Start" && (m = s, g = s.substr(0, s.length - 5) + "end", s = s.substr(0, s.length - 6)),
                            l = _e(s.toLowerCase()),
                            a[l] = s,
                            (h || !n.hasOwnProperty(l)) && (n[l] = c, ge(t, l) && (n[l] = !0)),
                            W(t, e, c, l),
                            I(e, l, "A", i, r, m, g)
                        }
                    }
                    if (t = t.className, w(t) && "" !== t) for (; s = u.exec(t);) l = _e(s[2]),
                    I(e, l, "C", i, r) && (n[l] = oi(s[3])),
                    t = t.substr(s.index + s[0].length);
                    break;
                case 3:
                    Y(e, t.nodeValue);
                    break;
                case 8:
                    try { (s = o.exec(t.nodeValue)) && (l = _e(s[1]), I(e, l, "M", i, r) && (n[l] = oi(s[2])))
                    } catch(y) {}
                }
                return e.sort(U),
                e
            }
            function F(t, e, n) {
                var i = [],
                r = 0;
                if (e && t.hasAttribute && t.hasAttribute(e)) {
                    do {
                        if (!t) throw Fi("uterdir", e, n);
                        1 == t.nodeType && (t.hasAttribute(e) && r++, t.hasAttribute(n) && r--), i.push(t), t = t.nextSibling
                    } while ( r > 0 )
                } else i.push(t);
                return zn(i)
            }
            function N(t, e, n) {
                return function(i, r, s, a, o) {
                    return r = F(r[0], e, n),
                    t(i, r, s, a, o)
                }
            }
            function j(t, r, a, o, u, c, h, d, f) {
                function p(t, e, n, i) {
                    t && (n && (t = N(t, n, i)), t.require = x.require, t.directiveName = k, (I === x || x.$$isolateScope) && (t = Q(t, {
                        "isolateScope": !0
                    })), h.push(t)),
                    e && (n && (e = N(e, n, i)), e.require = x.require, e.directiveName = k, (I === x || x.$$isolateScope) && (e = Q(e, {
                        "isolateScope": !0
                    })), d.push(e))
                }
                function m(t, e, n, i) {
                    var r, a = "data",
                    o = !1;
                    if (w(e)) {
                        for (;
                        "^" == (r = e.charAt(0)) || "?" == r;) e = e.substr(1),
                        "^" == r && (a = "inheritedData"),
                        o = o || "?" == r;
                        if (r = null, i && "data" === a && (r = i[e]), r = r || n[a]("$" + e + "Controller"), !r && !o) throw Fi("ctreq", e, t)
                    } else ai(e) && (r = [], s(e,
                    function(e) {
                        r.push(m(t, e, n, i))
                    }));
                    return r
                }
                function y(t, e, o, u, c) {
                    function f(t, e) {
                        var i;
                        return 2 > arguments.length && (e = t, t = n),
                        z && (i = k),
                        c(t, e, i)
                    }
                    var p, y, b, w, $, x, C, k = {};
                    if (p = r === o ? a: A(a, new Z(zn(o), a.$attr)), y = p.$$element, I) {
                        var T = /^\s*([@=&])(\??)\s*(\w*)\s*$/;
                        x = e.$new(!0),
                        !U || U !== I && U !== I.$$originalDirective ? y.data("$isolateScopeNoTemplate", x) : y.data("$isolateScope", x),
                        D(y, "ng-isolate-scope"),
                        s(I.scope,
                        function(t, n) {
                            var r, s, a, o, l = t.match(T) || [],
                            u = l[3] || n,
                            c = "?" == l[2],
                            l = l[1];
                            switch (x.$$isolateBindings[n] = l + u, l) {
                            case "@":
                                p.$observe(u,
                                function(t) {
                                    x[n] = t
                                }),
                                p.$$observers[u].$$scope = e,
                                p[u] && (x[n] = i(p[u])(e));
                                break;
                            case "=":
                                if (c && !p[u]) break;
                                s = g(p[u]),
                                o = s.literal ? O: function(t, e) {
                                    return t === e || t !== t && e !== e
                                },
                                a = s.assign ||
                                function() {
                                    throw r = x[n] = s(e),
                                    Fi("nonassign", p[u], I.name)
                                },
                                r = x[n] = s(e),
                                x.$watch(function() {
                                    var t = s(e);
                                    return o(t, x[n]) || (o(t, r) ? a(e, t = x[n]) : x[n] = t),
                                    r = t
                                },
                                null, s.literal);
                                break;
                            case "&":
                                s = g(p[u]),
                                x[n] = function(t) {
                                    return s(e, t)
                                };
                                break;
                            default:
                                throw Fi("iscp", I.name, n, t)
                            }
                        })
                    }
                    for (C = c && f, j && s(j,
                    function(t) {
                        var n, i = {
                            "$scope": t === I || t.$$isolateScope ? x: e,
                            "$element": y,
                            "$attrs": p,
                            "$transclude": C
                        };
                        $ = t.controller,
                        "@" == $ && ($ = p[t.name]),
                        n = v($, i),
                        k[t.name] = n,
                        z || y.data("$" + t.name + "Controller", n),
                        t.controllerAs && (i.$scope[t.controllerAs] = n)
                    }), u = 0, b = h.length; b > u; u++) try { (w = h[u])(w.isolateScope ? x: e, y, p, w.require && m(w.directiveName, w.require, y, k), C)
                    } catch(S) {
                        l(S, L(y))
                    }
                    for (u = e, I && (I.template || null === I.templateUrl) && (u = x), t && t(u, o.childNodes, n, c), u = d.length - 1; u >= 0; u--) try { (w = d[u])(w.isolateScope ? x: e, y, p, w.require && m(w.directiveName, w.require, y, k), C)
                    } catch(E) {
                        l(E, L(y))
                    }
                }
                f = f || {};
                for (var $, x, k, T, E, _ = -Number.MAX_VALUE,
                j = f.controllerDirectives,
                I = f.newIsolateScopeDirective,
                U = f.templateDirective,
                Y = f.nonTlbTranscludeDirective,
                V = !1,
                W = !1,
                z = f.hasElementTranscludeDirective,
                B = a.$$element = zn(r), J = o, X = 0, te = t.length; te > X; X++) {
                    x = t[X];
                    var ee = x.$$start,
                    ne = x.$$end;
                    if (ee && (B = F(r, ee, ne)), T = n, _ > x.priority) break;
                    if ((T = x.scope) && ($ = $ || x, x.templateUrl || (q("new/isolated scope", I, x, B), b(T) && (I = x))), k = x.name, !x.templateUrl && x.controller && (T = x.controller, j = j || {},
                    q("'" + k + "' controller", j[k], x, B), j[k] = x), (T = x.transclude) && (V = !0, x.$$tlb || (q("transclusion", Y, x, B), Y = x), "element" == T ? (z = !0, _ = x.priority, T = B, B = a.$$element = zn(e.createComment(" " + k + ": " + a[k] + " ")), r = B[0], G(u, ti.call(T, 0), r), J = S(T, o, _, c && c.name, {
                        "nonTlbTranscludeDirective": Y
                    })) : (T = zn(ie(r)).contents(), B.empty(), J = S(T, o))), x.template) if (W = !0, q("template", U, x, B), U = x, T = C(x.template) ? x.template(B, a) : x.template, T = K(T), x.replace) {
                        if (c = x, T = bi.test(T) ? zn(oi(T)) : [], r = T[0], 1 != T.length || 1 !== r.nodeType) throw Fi("tplrt", k, "");
                        G(u, B, r),
                        te = {
                            "$attr": {}
                        },
                        T = M(r, [], te);
                        var re = t.splice(X + 1, t.length - (X + 1));
                        I && P(T),
                        t = t.concat(T).concat(re),
                        H(a, te),
                        te = t.length
                    } else B.html(T);
                    if (x.templateUrl) W = !0,
                    q("template", U, x, B),
                    U = x,
                    x.replace && (c = x),
                    y = R(t.splice(X, t.length - X), B, a, u, V && J, h, d, {
                        "controllerDirectives": j,
                        "newIsolateScopeDirective": I,
                        "templateDirective": U,
                        "nonTlbTranscludeDirective": Y
                    }),
                    te = t.length;
                    else if (x.compile) try {
                        E = x.compile(B, a, J),
                        C(E) ? p(null, E, ee, ne) : E && p(E.pre, E.post, ee, ne)
                    } catch(se) {
                        l(se, L(B))
                    }
                    x.terminal && (y.terminal = !0, _ = Math.max(_, x.priority))
                }
                return y.scope = $ && !0 === $.scope,
                y.transcludeOnThisElement = V,
                y.templateOnThisElement = W,
                y.transclude = J,
                f.hasElementTranscludeDirective = z,
                y
            }
            function P(t) {
                for (var e = 0,
                n = t.length; n > e; e++) t[e] = f(t[e], {
                    "$$isolateScope": !0
                })
            }
            function I(e, i, s, o, u, c, h) {
                if (i === u) return null;
                if (u = null, r.hasOwnProperty(i)) {
                    var d;
                    i = t.get(i + a);
                    for (var p = 0,
                    m = i.length; m > p; p++) try {
                        d = i[p],
                        (o === n || o > d.priority) && -1 != d.restrict.indexOf(s) && (c && (d = f(d, {
                            "$$start": c,
                            "$$end": h
                        })), e.push(d), u = d)
                    } catch(g) {
                        l(g)
                    }
                }
                return u
            }
            function H(t, e) {
                var n = e.$attr,
                i = t.$attr,
                r = t.$$element;
                s(t,
                function(i, r) {
                    "$" != r.charAt(0) && (e[r] && e[r] !== i && (i += ("style" === r ? ";": " ") + e[r]), t.$set(r, i, !0, n[r]))
                }),
                s(e,
                function(e, s) {
                    "class" == s ? (D(r, e), t["class"] = (t["class"] ? t["class"] + " ": "") + e) : "style" == s ? (r.attr("style", r.attr("style") + ";" + e), t.style = (t.style ? t.style + ";": "") + e) : "$" == s.charAt(0) || t.hasOwnProperty(s) || (t[s] = e, i[s] = n[s])
                })
            }
            function R(t, e, n, i, r, a, o, l) {
                var u, c, f = [],
                m = e[0],
                g = t.shift(),
                v = h({},
                g, {
                    "templateUrl": null,
                    "transclude": null,
                    "replace": null,
                    "$$originalDirective": g
                }),
                y = C(g.templateUrl) ? g.templateUrl(e, n) : g.templateUrl;
                return e.empty(),
                d.get(x.getTrustedResourceUrl(y), {
                    "cache": p
                }).success(function(h) {
                    var d, p;
                    if (h = K(h), g.replace) {
                        if (h = bi.test(h) ? zn(oi(h)) : [], d = h[0], 1 != h.length || 1 !== d.nodeType) throw Fi("tplrt", g.name, y);
                        h = {
                            "$attr": {}
                        },
                        G(i, e, d);
                        var w = M(d, [], h);
                        b(g.scope) && P(w),
                        t = w.concat(t),
                        H(n, h)
                    } else d = m,
                    e.html(h);
                    for (t.unshift(v), u = j(t, d, n, r, e, g, a, o, l), s(i,
                    function(t, n) {
                        t == d && (i[n] = e[0])
                    }), c = E(e[0].childNodes, r); f.length;) {
                        h = f.shift(),
                        p = f.shift();
                        var $ = f.shift(),
                        x = f.shift(),
                        w = e[0];
                        if (p !== m) {
                            var C = p.className;
                            l.hasElementTranscludeDirective && g.replace || (w = ie(d)),
                            G($, zn(p), w),
                            D(zn(w), C)
                        }
                        p = u.transcludeOnThisElement ? _(h, u.transclude, x) : x,
                        u(c, h, w, i, p)
                    }
                    f = null
                }).error(function(t, e, n, i) {
                    throw Fi("tpload", i.url)
                }),
                function(t, e, n, i, r) {
                    t = r,
                    f ? (f.push(e), f.push(n), f.push(i), f.push(t)) : (u.transcludeOnThisElement && (t = _(e, u.transclude, r)), u(c, e, n, i, t))
                }
            }
            function U(t, e) {
                var n = e.priority - t.priority;
                return 0 !== n ? n: t.name !== e.name ? t.name < e.name ? -1 : 1 : t.index - e.index
            }
            function q(t, e, n, i) {
                if (e) throw Fi("multidir", e.name, n.name, t, L(i))
            }
            function Y(t, e) {
                var n = i(e, !0);
                n && t.push({
                    "priority": 0,
                    "compile": function(t) {
                        var e = t.parent().length;
                        return e && D(t.parent(), "ng-binding"),
                        function(t, i) {
                            var r = i.parent(),
                            s = r.data("$binding") || [];
                            s.push(n),
                            r.data("$binding", s),
                            e || D(r, "ng-binding"),
                            t.$watch(n,
                            function(t) {
                                i[0].nodeValue = t
                            })
                        }
                    }
                })
            }
            function V(t, e) {
                if ("srcdoc" == e) return x.HTML;
                var n = Qn(t);
                return "xlinkHref" == e || "FORM" == n && "action" == e || "IMG" != n && ("src" == e || "ngSrc" == e) ? x.RESOURCE_URL: void 0
            }
            function W(t, e, n, r) {
                var s = i(n, !0);
                if (s) {
                    if ("multiple" === r && "SELECT" === Qn(t)) throw Fi("selmulti", L(t));
                    e.push({
                        "priority": 100,
                        "compile": function() {
                            return {
                                "pre": function(e, n, a) {
                                    if (n = a.$$observers || (a.$$observers = {}), c.test(r)) throw Fi("nodomevents"); (s = i(a[r], !0, V(t, r))) && (a[r] = s(e), (n[r] || (n[r] = [])).$$inter = !0, (a.$$observers && a.$$observers[r].$$scope || e).$watch(s,
                                    function(t, e) {
                                        "class" === r && t != e ? a.$updateClass(t, e) : a.$set(r, t)
                                    }))
                                }
                            }
                        }
                    })
                }
            }
            function G(t, n, i) {
                var r, s, a = n[0],
                o = n.length,
                l = a.parentNode;
                if (t) for (r = 0, s = t.length; s > r; r++) if (t[r] == a) {
                    t[r++] = i,
                    s = r + o - 1;
                    for (var u = t.length; u > r; r++, s++) u > s ? t[r] = t[s] : delete t[r];
                    t.length -= o - 1;
                    break
                }
                for (l && l.replaceChild(i, a), t = e.createDocumentFragment(), t.appendChild(a), i[zn.expando] = a[zn.expando], a = 1, o = n.length; o > a; a++) l = n[a],
                zn(l).remove(),
                t.appendChild(l),
                delete n[a];
                n[0] = i,
                n.length = 1
            }
            function Q(t, e) {
                return h(function() {
                    return t.apply(null, arguments)
                },
                t, e)
            }
            var Z = function(t, e) {
                this.$$element = t,
                this.$attr = e || {}
            };
            Z.prototype = {
                "$normalize": _e,
                "$addClass": function(t) {
                    t && 0 < t.length && k.addClass(this.$$element, t)
                },
                "$removeClass": function(t) {
                    t && 0 < t.length && k.removeClass(this.$$element, t)
                },
                "$updateClass": function(t, e) {
                    var n = Me(t, e),
                    i = Me(e, t);
                    0 === n.length ? k.removeClass(this.$$element, i) : 0 === i.length ? k.addClass(this.$$element, n) : k.setClass(this.$$element, n, i)
                },
                "$set": function(t, e, i, r) {
                    var a = ge(this.$$element[0], t);
                    a && (this.$$element.prop(t, e), r = a),
                    this[t] = e,
                    r ? this.$attr[t] = r: (r = this.$attr[t]) || (this.$attr[t] = r = z(t, "-")),
                    a = Qn(this.$$element),
                    ("A" === a && "href" === t || "IMG" === a && "src" === t) && (this[t] = e = T(e, "src" === t)),
                    !1 !== i && (null === e || e === n ? this.$$element.removeAttr(r) : this.$$element.attr(r, e)),
                    (i = this.$$observers) && s(i[t],
                    function(t) {
                        try {
                            t(e)
                        } catch(n) {
                            l(n)
                        }
                    })
                },
                "$observe": function(t, e) {
                    var n = this,
                    i = n.$$observers || (n.$$observers = {}),
                    r = i[t] || (i[t] = []);
                    return r.push(e),
                    y.$evalAsync(function() {
                        r.$$inter || e(n[t])
                    }),
                    e
                }
            };
            var J = i.startSymbol(),
            X = i.endSymbol(),
            K = "{{" == J || "}}" == X ? m: function(t) {
                return t.replace(/\{\{/g, J).replace(/}}/g, X)
            },
            te = /^ngAttr[A-Z]/;
            return S
        }]
    }
    function _e(t) {
        return te(t.replace(Ni, ""))
    }
    function Me(t, e) {
        var n = "",
        i = t.split(/\s+/),
        r = e.split(/\s+/),
        s = 0;
        t: for (; s < i.length; s++) {
            for (var a = i[s], o = 0; o < r.length; o++) if (a == r[o]) continue t;
            n += (0 < n.length ? " ": "") + a
        }
        return n
    }
    function Ae() {
        var t = {},
        e = /^(\S+)(\s+as\s+(\w+))?$/;
        this.register = function(e, n) {
            Q(e, "controller"),
            b(e) ? h(t, e) : t[e] = n
        },
        this.$get = ["$injector", "$window",
        function(n, r) {
            return function(s, a) {
                var o, l, u;
                if (w(s) && (o = s.match(e), l = o[1], u = o[3], s = t.hasOwnProperty(l) ? t[l] : Z(a.$scope, l, !0) || Z(r, l, !0), G(s, l, !0)), o = n.instantiate(s, a), u) {
                    if (!a || "object" != typeof a.$scope) throw i("$controller")("noscp", l || s.name, u);
                    a.$scope[u] = o
                }
                return o
            }
        }]
    }
    function Oe() {
        this.$get = ["$window",
        function(t) {
            return zn(t.document)
        }]
    }
    function Fe() {
        this.$get = ["$log",
        function(t) {
            return function() {
                t.error.apply(t, arguments)
            }
        }]
    }
    function Ne(t) {
        var e, n, i, r = {};
        return t ? (s(t.split("\n"),
        function(t) {
            i = t.indexOf(":"),
            e = Jn(oi(t.substr(0, i))),
            n = oi(t.substr(i + 1)),
            e && (r[e] = r[e] ? r[e] + ", " + n: n)
        }), r) : r
    }
    function je(t) {
        var e = b(t) ? t: n;
        return function(n) {
            return e || (e = Ne(t)),
            n ? e[Jn(n)] || null: e
        }
    }
    function Pe(t, e, n) {
        return C(n) ? n(t, e) : (s(n,
        function(n) {
            t = n(t, e)
        }), t)
    }
    function Ie() {
        var t = /^\s*(\[|\{[^\{])/,
        e = /[\}\]]\s*$/,
        i = /^\)\]\}',?\n/,
        r = {
            "Content-Type": "application/json;charset=utf-8"
        },
        a = this.defaults = {
            "transformResponse": [function(n) {
                return w(n) && (n = n.replace(i, ""), t.test(n) && e.test(n) && (n = P(n))),
                n
            }],
            "transformRequest": [function(t) {
                return b(t) && "[object File]" !== ni.call(t) && "[object Blob]" !== ni.call(t) ? j(t) : t
            }],
            "headers": {
                "common": {
                    "Accept": "application/json, text/plain, */*"
                },
                "post": A(r),
                "put": A(r),
                "patch": A(r)
            },
            "xsrfCookieName": "XSRF-TOKEN",
            "xsrfHeaderName": "X-XSRF-TOKEN"
        },
        l = this.interceptors = [],
        u = this.responseInterceptors = [];
        this.$get = ["$httpBackend", "$browser", "$cacheFactory", "$rootScope", "$q", "$injector",
        function(t, e, i, r, c, d) {
            function f(t) {
                function e(t) {
                    var e = h({},
                    t, {
                        "data": Pe(t.data, t.headers, i.transformResponse)
                    });
                    return 200 <= t.status && 300 > t.status ? e: c.reject(e)
                }
                var i = {
                    "method": "get",
                    "transformRequest": a.transformRequest,
                    "transformResponse": a.transformResponse
                },
                r = function(t) {
                    var e, n, i = a.headers,
                    r = h({},
                    t.headers),
                    i = h({},
                    i.common, i[Jn(t.method)]);
                    t: for (e in i) {
                        t = Jn(e);
                        for (n in r) if (Jn(n) === t) continue t;
                        r[e] = i[e]
                    }
                    return function(t) {
                        var e;
                        s(t,
                        function(n, i) {
                            C(n) && (e = n(), null != e ? t[i] = e: delete t[i])
                        })
                    } (r),
                    r
                } (t);
                h(i, t),
                i.headers = r,
                i.method = Kn(i.method);
                var o = [function(t) {
                    r = t.headers;
                    var n = Pe(t.data, je(r), t.transformRequest);
                    return v(n) && s(r,
                    function(t, e) {
                        "content-type" === Jn(e) && delete r[e]
                    }),
                    v(t.withCredentials) && !v(a.withCredentials) && (t.withCredentials = a.withCredentials),
                    p(t, n, r).then(e, e)
                },
                n],
                l = c.when(i);
                for (s($,
                function(t) { (t.request || t.requestError) && o.unshift(t.request, t.requestError),
                    (t.response || t.responseError) && o.push(t.response, t.responseError)
                }); o.length;) {
                    t = o.shift();
                    var u = o.shift(),
                    l = l.then(t, u)
                }
                return l.success = function(t) {
                    return l.then(function(e) {
                        t(e.data, e.status, e.headers, i)
                    }),
                    l
                },
                l.error = function(t) {
                    return l.then(null,
                    function(e) {
                        t(e.data, e.status, e.headers, i)
                    }),
                    l
                },
                l
            }
            function p(i, s, o) {
                function l(t, e, n, i) {
                    d && (t >= 200 && 300 > t ? d.put(x, [t, e, Ne(n), i]) : d.remove(x)),
                    u(e, t, n, i),
                    r.$$phase || r.$apply()
                }
                function u(t, e, n, r) {
                    e = Math.max(e, 0),
                    (e >= 200 && 300 > e ? w.resolve: w.reject)({
                        "data": t,
                        "status": e,
                        "headers": je(n),
                        "config": i,
                        "statusText": r
                    })
                }
                function h() {
                    var t = E(f.pendingRequests, i); - 1 !== t && f.pendingRequests.splice(t, 1)
                }
                var d, p, w = c.defer(),
                $ = w.promise,
                x = m(i.url, i.params);
                if (f.pendingRequests.push(i), $.then(h, h), !i.cache && !a.cache || !1 === i.cache || "GET" !== i.method && "JSONP" !== i.method || (d = b(i.cache) ? i.cache: b(a.cache) ? a.cache: g), d) if (p = d.get(x), y(p)) {
                    if (p && C(p.then)) return p.then(h, h),
                    p;
                    ai(p) ? u(p[1], p[0], A(p[2]), p[3]) : u(p, 200, {},
                    "OK")
                } else d.put(x, $);
                return v(p) && ((p = kn(i.url) ? e.cookies()[i.xsrfCookieName || a.xsrfCookieName] : n) && (o[i.xsrfHeaderName || a.xsrfHeaderName] = p), t(i.method, x, s, l, o, i.timeout, i.withCredentials, i.responseType)),
                $
            }
            function m(t, e) {
                if (!e) return t;
                var n = [];
                return o(e,
                function(t, e) {
                    null === t || v(t) || (ai(t) || (t = [t]), s(t,
                    function(t) {
                        b(t) && (t = x(t) ? t.toISOString() : j(t)),
                        n.push(Y(e) + "=" + Y(t))
                    }))
                }),
                0 < n.length && (t += ( - 1 == t.indexOf("?") ? "?": "&") + n.join("&")),
                t
            }
            var g = i("$http"),
            $ = [];
            return s(l,
            function(t) {
                $.unshift(w(t) ? d.get(t) : d.invoke(t))
            }),
            s(u,
            function(t, e) {
                var n = w(t) ? d.get(t) : d.invoke(t);
                $.splice(e, 0, {
                    "response": function(t) {
                        return n(c.when(t))
                    },
                    "responseError": function(t) {
                        return n(c.reject(t))
                    }
                })
            }),
            f.pendingRequests = [],
            function() {
                s(arguments,
                function(t) {
                    f[t] = function(e, n) {
                        return f(h(n || {},
                        {
                            "method": t,
                            "url": e
                        }))
                    }
                })
            } ("get", "delete", "head", "jsonp"),
            function() {
                s(arguments,
                function(t) {
                    f[t] = function(e, n, i) {
                        return f(h(i || {},
                        {
                            "method": t,
                            "url": e,
                            "data": n
                        }))
                    }
                })
            } ("post", "put", "patch"),
            f.defaults = a,
            f
        }]
    }
    function Le(e) {
        if (8 >= Wn && (!e.match(/^(get|post|head|put|delete|options)$/i) || !t.XMLHttpRequest)) return new t.ActiveXObject("Microsoft.XMLHTTP");
        if (t.XMLHttpRequest) return new t.XMLHttpRequest;
        throw i("$httpBackend")("noxhr")
    }
    function He() {
        this.$get = ["$browser", "$window", "$document",
        function(t, e, n) {
            return Re(t, Le, t.defer, e.angular.callbacks, n[0])
        }]
    }
    function Re(t, e, n, i, r) {
        function a(t, e, n) {
            var s = r.createElement("script"),
            a = null;
            return s.type = "text/javascript",
            s.src = t,
            s.async = !0,
            a = function(t) {
                pi(s, "load", a),
                pi(s, "error", a),
                r.body.removeChild(s),
                s = null;
                var o = -1,
                l = "unknown";
                t && ("load" !== t.type || i[e].called || (t = {
                    "type": "error"
                }), l = t.type, o = "error" === t.type ? 404 : 200),
                n && n(o, l)
            },
            fi(s, "load", a),
            fi(s, "error", a),
            8 >= Wn && (s.onreadystatechange = function() {
                w(s.readyState) && /loaded|complete/.test(s.readyState) && (s.onreadystatechange = null, a({
                    "type": "load"
                }))
            }),
            r.body.appendChild(s),
            a
        }
        var o = -1;
        return function(r, l, u, c, h, d, f, m) {
            function g() {
                b = o,
                $ && $(),
                x && x.abort()
            }
            function v(e, i, r, s, a) {
                T && n.cancel(T),
                $ = x = null,
                0 === i && (i = r ? 200 : "file" == Cn(l).protocol ? 404 : 0),
                e(1223 === i ? 204 : i, r, s, a || ""),
                t.$$completeOutstandingRequest(p)
            }
            var b;
            if (t.$$incOutstandingRequestCount(), l = l || t.url(), "jsonp" == Jn(r)) {
                var w = "_" + (i.counter++).toString(36);
                i[w] = function(t) {
                    i[w].data = t,
                    i[w].called = !0
                };
                var $ = a(l.replace("JSON_CALLBACK", "angular.callbacks." + w), w,
                function(t, e) {
                    v(c, t, i[w].data, "", e),
                    i[w] = p
                })
            } else {
                var x = e(r);
                if (x.open(r, l, !0), s(h,
                function(t, e) {
                    y(t) && x.setRequestHeader(e, t)
                }), x.onreadystatechange = function() {
                    if (x && 4 == x.readyState) {
                        var t = null,
                        e = null,
                        n = "";
                        b !== o && (t = x.getAllResponseHeaders(), e = "response" in x ? x.response: x.responseText),
                        b === o && 10 > Wn || (n = x.statusText),
                        v(c, b || x.status, e, t, n)
                    }
                },
                f && (x.withCredentials = !0), m) try {
                    x.responseType = m
                } catch(k) {
                    if ("json" !== m) throw k
                }
                x.send(u || null)
            }
            if (d > 0) var T = n(g, d);
            else d && C(d.then) && d.then(g)
        }
    }
    function Ue() {
        var t = "{{",
        e = "}}";
        this.startSymbol = function(e) {
            return e ? (t = e, this) : t
        },
        this.endSymbol = function(t) {
            return t ? (e = t, this) : e
        },
        this.$get = ["$parse", "$exceptionHandler", "$sce",
        function(n, i, r) {
            function s(s, l, u) {
                for (var c, h, d = 0,
                f = [], p = s.length, m = !1, g = []; p > d;) - 1 != (c = s.indexOf(t, d)) && -1 != (h = s.indexOf(e, c + a)) ? (d != c && f.push(s.substring(d, c)), f.push(d = n(m = s.substring(c + a, h))), d.exp = m, d = h + o, m = !0) : (d != p && f.push(s.substring(d)), d = p);
                if ((p = f.length) || (f.push(""), p = 1), u && 1 < f.length) throw ji("noconcat", s);
                return ! l || m ? (g.length = p, d = function(t) {
                    try {
                        for (var e, n = 0,
                        a = p; a > n; n++) {
                            if ("function" == typeof(e = f[n])) if (e = e(t), e = u ? r.getTrusted(u, e) : r.valueOf(e), null == e) e = "";
                            else switch (typeof e) {
                            case "string":
                                break;
                            case "number":
                                e = "" + e;
                                break;
                            default:
                                e = j(e)
                            }
                            g[n] = e
                        }
                        return g.join("")
                    } catch(o) {
                        t = ji("interr", s, o.toString()),
                        i(t)
                    }
                },
                d.exp = s, d.parts = f, d) : void 0
            }
            var a = t.length,
            o = e.length;
            return s.startSymbol = function() {
                return t
            },
            s.endSymbol = function() {
                return e
            },
            s
        }]
    }
    function qe() {
        this.$get = ["$rootScope", "$window", "$q",
        function(t, e, n) {
            function i(i, s, a, o) {
                var l = e.setInterval,
                u = e.clearInterval,
                c = n.defer(),
                h = c.promise,
                d = 0,
                f = y(o) && !o;
                return a = y(a) ? a: 0,
                h.then(null, null, i),
                h.$$intervalId = l(function() {
                    c.notify(d++),
                    a > 0 && d >= a && (c.resolve(d), u(h.$$intervalId), delete r[h.$$intervalId]),
                    f || t.$apply()
                },
                s),
                r[h.$$intervalId] = c,
                h
            }
            var r = {};
            return i.cancel = function(t) {
                return t && t.$$intervalId in r ? (r[t.$$intervalId].reject("canceled"), e.clearInterval(t.$$intervalId), delete r[t.$$intervalId], !0) : !1
            },
            i
        }]
    }
    function Ye() {
        this.$get = function() {
            return {
                "id": "en-us",
                "NUMBER_FORMATS": {
                    "DECIMAL_SEP": ".",
                    "GROUP_SEP": ",",
                    "PATTERNS": [{
                        "minInt": 1,
                        "minFrac": 0,
                        "maxFrac": 3,
                        "posPre": "",
                        "posSuf": "",
                        "negPre": "-",
                        "negSuf": "",
                        "gSize": 3,
                        "lgSize": 3
                    },
                    {
                        "minInt": 1,
                        "minFrac": 2,
                        "maxFrac": 2,
                        "posPre": "\xa4",
                        "posSuf": "",
                        "negPre": "(\xa4",
                        "negSuf": ")",
                        "gSize": 3,
                        "lgSize": 3
                    }],
                    "CURRENCY_SYM": "$"
                },
                "DATETIME_FORMATS": {
                    "MONTH": "January February March April May June July August September October November December".split(" "),
                    "SHORTMONTH": "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(" "),
                    "DAY": "Sunday Monday Tuesday Wednesday Thursday Friday Saturday".split(" "),
                    "SHORTDAY": "Sun Mon Tue Wed Thu Fri Sat".split(" "),
                    "AMPMS": ["AM", "PM"],
                    "medium": "MMM d, y h:mm:ss a",
                    "short": "M/d/yy h:mm a",
                    "fullDate": "EEEE, MMMM d, y",
                    "longDate": "MMMM d, y",
                    "mediumDate": "MMM d, y",
                    "shortDate": "M/d/yy",
                    "mediumTime": "h:mm:ss a",
                    "shortTime": "h:mm a"
                },
                "pluralCat": function(t) {
                    return 1 === t ? "one": "other"
                }
            }
        }
    }
    function Ve(t) {
        t = t.split("/");
        for (var e = t.length; e--;) t[e] = q(t[e]);
        return t.join("/")
    }
    function We(t, e, n) {
        t = Cn(t, n),
        e.$$protocol = t.protocol,
        e.$$host = t.hostname,
        e.$$port = d(t.port) || Ii[t.protocol] || null
    }
    function ze(t, e, n) {
        var i = "/" !== t.charAt(0);
        i && (t = "/" + t),
        t = Cn(t, n),
        e.$$path = decodeURIComponent(i && "/" === t.pathname.charAt(0) ? t.pathname.substring(1) : t.pathname),
        e.$$search = R(t.search),
        e.$$hash = decodeURIComponent(t.hash),
        e.$$path && "/" != e.$$path.charAt(0) && (e.$$path = "/" + e.$$path)
    }
    function Be(t, e) {
        return 0 === e.indexOf(t) ? e.substr(t.length) : void 0
    }
    function Ge(t) {
        var e = t.indexOf("#");
        return - 1 == e ? t: t.substr(0, e)
    }
    function Qe(t) {
        return t.substr(0, Ge(t).lastIndexOf("/") + 1)
    }
    function Ze(t, e) {
        this.$$html5 = !0,
        e = e || "";
        var i = Qe(t);
        We(t, this, t),
        this.$$parse = function(e) {
            var n = Be(i, e);
            if (!w(n)) throw Li("ipthprfx", e, i);
            ze(n, this, t),
            this.$$path || (this.$$path = "/"),
            this.$$compose()
        },
        this.$$compose = function() {
            var t = U(this.$$search),
            e = this.$$hash ? "#" + q(this.$$hash) : "";
            this.$$url = Ve(this.$$path) + (t ? "?" + t: "") + e,
            this.$$absUrl = i + this.$$url.substr(1)
        },
        this.$$parseLinkUrl = function(r) {
            var s, a;
            return (s = Be(t, r)) !== n ? (a = s, a = (s = Be(e, s)) !== n ? i + (Be("/", s) || s) : t + a) : (s = Be(i, r)) !== n ? a = i + s: i == r + "/" && (a = i),
            a && this.$$parse(a),
            !!a
        }
    }
    function Je(t, e) {
        var n = Qe(t);
        We(t, this, t),
        this.$$parse = function(i) {
            var r = Be(t, i) || Be(n, i),
            r = "#" == r.charAt(0) ? Be(e, r) : this.$$html5 ? r: "";
            if (!w(r)) throw Li("ihshprfx", i, e);
            ze(r, this, t),
            i = this.$$path;
            var s = /^\/[A-Z]:(\/.*)/;
            0 === r.indexOf(t) && (r = r.replace(t, "")),
            s.exec(r) || (i = (r = s.exec(i)) ? r[1] : i),
            this.$$path = i,
            this.$$compose()
        },
        this.$$compose = function() {
            var n = U(this.$$search),
            i = this.$$hash ? "#" + q(this.$$hash) : "";
            this.$$url = Ve(this.$$path) + (n ? "?" + n: "") + i,
            this.$$absUrl = t + (this.$$url ? e + this.$$url: "")
        },
        this.$$parseLinkUrl = function(e) {
            return Ge(t) == Ge(e) ? (this.$$parse(e), !0) : !1
        }
    }
    function Xe(t, e) {
        this.$$html5 = !0,
        Je.apply(this, arguments);
        var n = Qe(t);
        this.$$parseLinkUrl = function(i) {
            var r, s;
            return t == Ge(i) ? r = i: (s = Be(n, i)) ? r = t + e + s: n === i + "/" && (r = n),
            r && this.$$parse(r),
            !!r
        },
        this.$$compose = function() {
            var n = U(this.$$search),
            i = this.$$hash ? "#" + q(this.$$hash) : "";
            this.$$url = Ve(this.$$path) + (n ? "?" + n: "") + i,
            this.$$absUrl = t + e + this.$$url
        }
    }
    function Ke(t) {
        return function() {
            return this[t]
        }
    }
    function tn(t, e) {
        return function(n) {
            return v(n) ? this[t] : (this[t] = e(n), this.$$compose(), this)
        }
    }
    function en() {
        var e = "",
        n = !1;
        this.hashPrefix = function(t) {
            return y(t) ? (e = t, this) : e
        },
        this.html5Mode = function(t) {
            return y(t) ? (n = t, this) : n
        },
        this.$get = ["$rootScope", "$browser", "$sniffer", "$rootElement",
        function(i, r, s, a) {
            function o(t) {
                i.$broadcast("$locationChangeSuccess", l.absUrl(), t)
            }
            var l, u = r.baseHref(),
            c = r.url();
            n ? (u = c.substring(0, c.indexOf("/", c.indexOf("//") + 2)) + (u || "/"), s = s.history ? Ze: Xe) : (u = Ge(c), s = Je),
            l = new s(u, "#" + e),
            l.$$parseLinkUrl(c, c);
            var h = /^\s*(javascript|mailto):/i;
            a.on("click",
            function(e) {
                if (!e.ctrlKey && !e.metaKey && 2 != e.which) {
                    for (var n = zn(e.target);
                    "a" !== Jn(n[0].nodeName);) if (n[0] === a[0] || !(n = n.parent())[0]) return;
                    var s = n.prop("href"),
                    o = n.attr("href") || n.attr("xlink:href");
                    b(s) && "[object SVGAnimatedString]" === s.toString() && (s = Cn(s.animVal).href),
                    h.test(s) || !s || n.attr("target") || e.isDefaultPrevented() || !l.$$parseLinkUrl(s, o) || (e.preventDefault(), l.absUrl() != r.url() && (i.$apply(), t.angular["ff-684208-preventDefault"] = !0))
                }
            }),
            l.absUrl() != c && r.url(l.absUrl(), !0),
            r.onUrlChange(function(t) {
                l.absUrl() != t && (i.$evalAsync(function() {
                    var e = l.absUrl();
                    l.$$parse(t),
                    i.$broadcast("$locationChangeStart", t, e).defaultPrevented ? (l.$$parse(e), r.url(e)) : o(e)
                }), i.$$phase || i.$digest())
            });
            var d = 0;
            return i.$watch(function() {
                var t = r.url(),
                e = l.$$replace;
                return d && t == l.absUrl() || (d++, i.$evalAsync(function() {
                    i.$broadcast("$locationChangeStart", l.absUrl(), t).defaultPrevented ? l.$$parse(t) : (r.url(l.absUrl(), e), o(t))
                })),
                l.$$replace = !1,
                d
            }),
            l
        }]
    }
    function nn() {
        var t = !0,
        e = this;
        this.debugEnabled = function(e) {
            return y(e) ? (t = e, this) : t
        },
        this.$get = ["$window",
        function(n) {
            function i(t) {
                return t instanceof Error && (t.stack ? t = t.message && -1 === t.stack.indexOf(t.message) ? "Error: " + t.message + "\n" + t.stack: t.stack: t.sourceURL && (t = t.message + "\n" + t.sourceURL + ":" + t.line)),
                t
            }
            function r(t) {
                var e = n.console || {},
                r = e[t] || e.log || p;
                t = !1;
                try {
                    t = !!r.apply
                } catch(a) {}
                return t ?
                function() {
                    var t = [];
                    return s(arguments,
                    function(e) {
                        t.push(i(e))
                    }),
                    r.apply(e, t)
                }: function(t, e) {
                    r(t, null == e ? "": e)
                }
            }
            return {
                "log": r("log"),
                "info": r("info"),
                "warn": r("warn"),
                "error": r("error"),
                "debug": function() {
                    var n = r("debug");
                    return function() {
                        t && n.apply(e, arguments)
                    }
                } ()
            }
        }]
    }
    function rn(t, e) {
        if ("__defineGetter__" === t || "__defineSetter__" === t || "__lookupGetter__" === t || "__lookupSetter__" === t || "__proto__" === t) throw Ri("isecfld", e);
        return t
    }
    function sn(t, e) {
        if (t) {
            if (t.constructor === t) throw Ri("isecfn", e);
            if (t.document && t.location && t.alert && t.setInterval) throw Ri("isecwindow", e);
            if (t.children && (t.nodeName || t.prop && t.attr && t.find)) throw Ri("isecdom", e);
            if (t === Object) throw Ri("isecobj", e)
        }
        return t
    }
    function an(t, e, i, r, s) {
        sn(t, r),
        s = s || {},
        e = e.split(".");
        for (var a, o = 0; 1 < e.length; o++) {
            a = rn(e.shift(), r);
            var l = sn(t[a], r);
            l || (l = {},
            t[a] = l),
            t = l,
            t.then && s.unwrapPromises && (Hi(r), "$$v" in t ||
            function(t) {
                t.then(function(e) {
                    t.$$v = e
                })
            } (t), t.$$v === n && (t.$$v = {}), t = t.$$v)
        }
        return a = rn(e.shift(), r),
        sn(t[a], r),
        t[a] = i
    }
    function on(t) {
        return "constructor" == t
    }
    function ln(t, e, i, r, s, a, o) {
        rn(t, a),
        rn(e, a),
        rn(i, a),
        rn(r, a),
        rn(s, a);
        var l = function(t) {
            return sn(t, a)
        },
        u = o.expensiveChecks,
        c = u || on(t) ? l: m,
        h = u || on(e) ? l: m,
        d = u || on(i) ? l: m,
        f = u || on(r) ? l: m,
        p = u || on(s) ? l: m;
        return o.unwrapPromises ?
        function(o, l) {
            var u, m = l && l.hasOwnProperty(t) ? l: o;
            return null == m ? m: ((m = c(m[t])) && m.then && (Hi(a), "$$v" in m || (u = m, u.$$v = n, u.then(function(t) {
                u.$$v = c(t)
            })), m = c(m.$$v)), e ? null == m ? n: ((m = h(m[e])) && m.then && (Hi(a), "$$v" in m || (u = m, u.$$v = n, u.then(function(t) {
                u.$$v = h(t)
            })), m = h(m.$$v)), i ? null == m ? n: ((m = d(m[i])) && m.then && (Hi(a), "$$v" in m || (u = m, u.$$v = n, u.then(function(t) {
                u.$$v = d(t)
            })), m = d(m.$$v)), r ? null == m ? n: ((m = f(m[r])) && m.then && (Hi(a), "$$v" in m || (u = m, u.$$v = n, u.then(function(t) {
                u.$$v = f(t)
            })), m = f(m.$$v)), s ? null == m ? n: ((m = p(m[s])) && m.then && (Hi(a), "$$v" in m || (u = m, u.$$v = n, u.then(function(t) {
                u.$$v = p(t)
            })), m = p(m.$$v)), m) : m) : m) : m) : m)
        }: function(a, o) {
            var l = o && o.hasOwnProperty(t) ? o: a;
            return null == l ? l: (l = c(l[t]), e ? null == l ? n: (l = h(l[e]), i ? null == l ? n: (l = d(l[i]), r ? null == l ? n: (l = f(l[r]), s ? null == l ? n: l = p(l[s]) : l) : l) : l) : l)
        }
    }
    function un(t, e) {
        return function(n, i) {
            return t(n, i, Hi, sn, e)
        }
    }
    function cn(t, e, i) {
        var r = e.expensiveChecks,
        a = r ? Zi: Qi;
        if (a.hasOwnProperty(t)) return a[t];
        var o, l = t.split("."),
        u = l.length;
        if (e.csp) o = 6 > u ? ln(l[0], l[1], l[2], l[3], l[4], i, e) : function(t, r) {
            var s, a = 0;
            do s = ln(l[a++], l[a++], l[a++], l[a++], l[a++], i, e)(t, r),
            r = n,
            t = s;
            while (u > a);
            return s
        };
        else {
            var c = "var p;\n";
            r && (c += "s = eso(s, fe);\nl = eso(l, fe);\n");
            var h = r;
            s(l,
            function(t, n) {
                rn(t, i);
                var s = (n ? "s": '((l&&l.hasOwnProperty("' + t + '"))?l:s)') + '["' + t + '"]',
                a = r || on(t);
                a && (s = "eso(" + s + ", fe)", h = !0),
                c += "if(s == null) return undefined;\ns=" + s + ";\n",
                e.unwrapPromises && (c += 'if (s && s.then) {\n pw("' + i.replace(/(["\r\n])/g, "\\$1") + '");\n if (!("$$v" in s)) {\n p=s;\n p.$$v = undefined;\n p.then(function(v) {p.$$v=' + (a ? "eso(v)": "v") + ";});\n}\n s=" + (a ? "eso(s.$$v)": "s.$$v") + "\n}\n")
            }),
            c += "return s;",
            o = new Function("s", "l", "pw", "eso", "fe", c),
            o.toString = g(c),
            (h || e.unwrapPromises) && (o = un(o, i))
        }
        return "hasOwnProperty" !== t && (a[t] = o),
        o
    }
    function hn() {
        var t = {},
        e = {},
        n = {
            "csp": !1,
            "unwrapPromises": !1,
            "logPromiseWarnings": !0,
            "expensiveChecks": !1
        };
        this.unwrapPromises = function(t) {
            return y(t) ? (n.unwrapPromises = !!t, this) : n.unwrapPromises
        },
        this.logPromiseWarnings = function(t) {
            return y(t) ? (n.logPromiseWarnings = t, this) : n.logPromiseWarnings
        },
        this.$get = ["$filter", "$sniffer", "$log",
        function(i, r, s) {
            n.csp = r.csp;
            var a = {
                "csp": n.csp,
                "unwrapPromises": n.unwrapPromises,
                "logPromiseWarnings": n.logPromiseWarnings,
                "expensiveChecks": !0
            };
            return Hi = function(t) {
                n.logPromiseWarnings && !Ui.hasOwnProperty(t) && (Ui[t] = !0, s.warn("[$parse] Promise found in the expression `" + t + "`. Automatic unwrapping of promises in Angular expressions is deprecated."))
            },
            function(r, s) {
                var o;
                switch (typeof r) {
                case "string":
                    var l = s ? e: t;
                    if (l.hasOwnProperty(r)) return l[r];
                    o = s ? a: n;
                    var u = new Bi(o);
                    return o = new Gi(u, i, o).parse(r),
                    "hasOwnProperty" !== r && (l[r] = o),
                    o;
                case "function":
                    return r;
                default:
                    return p
                }
            }
        }]
    }
    function dn() {
        this.$get = ["$rootScope", "$exceptionHandler",
        function(t, e) {
            return fn(function(e) {
                t.$evalAsync(e)
            },
            e)
        }]
    }
    function fn(t, e) {
        function i(t) {
            return t
        }
        function r(t) {
            return l(t)
        }
        var a = function() {
            var s, l, c = [];
            return l = {
                "resolve": function(e) {
                    if (c) {
                        var i = c;
                        c = n,
                        s = o(e),
                        i.length && t(function() {
                            for (var t, e = 0,
                            n = i.length; n > e; e++) t = i[e],
                            s.then(t[0], t[1], t[2])
                        })
                    }
                },
                "reject": function(t) {
                    l.resolve(u(t))
                },
                "notify": function(e) {
                    if (c) {
                        var n = c;
                        c.length && t(function() {
                            for (var t, i = 0,
                            r = n.length; r > i; i++) t = n[i],
                            t[2](e)
                        })
                    }
                },
                "promise": {
                    "then": function(t, n, o) {
                        var l = a(),
                        u = function(n) {
                            try {
                                l.resolve((C(t) ? t: i)(n))
                            } catch(r) {
                                l.reject(r),
                                e(r)
                            }
                        },
                        h = function(t) {
                            try {
                                l.resolve((C(n) ? n: r)(t))
                            } catch(i) {
                                l.reject(i),
                                e(i)
                            }
                        },
                        d = function(t) {
                            try {
                                l.notify((C(o) ? o: i)(t))
                            } catch(n) {
                                e(n)
                            }
                        };
                        return c ? c.push([u, h, d]) : s.then(u, h, d),
                        l.promise
                    },
                    "catch": function(t) {
                        return this.then(null, t)
                    },
                    "finally": function(t) {
                        function e(t, e) {
                            var n = a();
                            return e ? n.resolve(t) : n.reject(t),
                            n.promise
                        }
                        function n(n, r) {
                            var s = null;
                            try {
                                s = (t || i)()
                            } catch(a) {
                                return e(a, !1)
                            }
                            return s && C(s.then) ? s.then(function() {
                                return e(n, r)
                            },
                            function(t) {
                                return e(t, !1)
                            }) : e(n, r)
                        }
                        return this.then(function(t) {
                            return n(t, !0)
                        },
                        function(t) {
                            return n(t, !1)
                        })
                    }
                }
            }
        },
        o = function(e) {
            return e && C(e.then) ? e: {
                "then": function(n) {
                    var i = a();
                    return t(function() {
                        i.resolve(n(e))
                    }),
                    i.promise
                }
            }
        },
        l = function(t) {
            var e = a();
            return e.reject(t),
            e.promise
        },
        u = function(n) {
            return {
                "then": function(i, s) {
                    var o = a();
                    return t(function() {
                        try {
                            o.resolve((C(s) ? s: r)(n))
                        } catch(t) {
                            o.reject(t),
                            e(t)
                        }
                    }),
                    o.promise
                }
            }
        };
        return {
            "defer": a,
            "reject": l,
            "when": function(n, s, u, c) {
                var h, d = a(),
                f = function(t) {
                    try {
                        return (C(s) ? s: i)(t)
                    } catch(n) {
                        return e(n),
                        l(n)
                    }
                },
                p = function(t) {
                    try {
                        return (C(u) ? u: r)(t)
                    } catch(n) {
                        return e(n),
                        l(n)
                    }
                },
                m = function(t) {
                    try {
                        return (C(c) ? c: i)(t)
                    } catch(n) {
                        e(n)
                    }
                };
                return t(function() {
                    o(n).then(function(t) {
                        h || (h = !0, d.resolve(o(t).then(f, p, m)))
                    },
                    function(t) {
                        h || (h = !0, d.resolve(p(t)))
                    },
                    function(t) {
                        h || d.notify(m(t))
                    })
                }),
                d.promise
            },
            "all": function(t) {
                var e = a(),
                n = 0,
                i = ai(t) ? [] : {};
                return s(t,
                function(t, r) {
                    n++,
                    o(t).then(function(t) {
                        i.hasOwnProperty(r) || (i[r] = t, --n || e.resolve(i))
                    },
                    function(t) {
                        i.hasOwnProperty(r) || e.reject(t)
                    })
                }),
                0 === n && e.resolve(i),
                e.promise
            }
        }
    }
    function pn() {
        this.$get = ["$window", "$timeout",
        function(t, e) {
            var n = t.requestAnimationFrame || t.webkitRequestAnimationFrame || t.mozRequestAnimationFrame,
            i = t.cancelAnimationFrame || t.webkitCancelAnimationFrame || t.mozCancelAnimationFrame || t.webkitCancelRequestAnimationFrame,
            r = !!n,
            s = r ?
            function(t) {
                var e = n(t);
                return function() {
                    i(e)
                }
            }: function(t) {
                var n = e(t, 16.66, !1);
                return function() {
                    e.cancel(n)
                }
            };
            return s.supported = r,
            s
        }]
    }
    function mn() {
        var t = 10,
        e = i("$rootScope"),
        n = null;
        this.digestTtl = function(e) {
            return arguments.length && (t = e),
            t
        },
        this.$get = ["$injector", "$exceptionHandler", "$parse", "$browser",
        function(i, a, o, l) {
            function c() {
                this.$id = u(),
                this.$$phase = this.$parent = this.$$watchers = this.$$nextSibling = this.$$prevSibling = this.$$childHead = this.$$childTail = null,
                this["this"] = this.$root = this,
                this.$$destroyed = !1,
                this.$$asyncQueue = [],
                this.$$postDigestQueue = [],
                this.$$listeners = {},
                this.$$listenerCount = {},
                this.$$isolateBindings = {}
            }
            function h(t) {
                if (g.$$phase) throw e("inprog", g.$$phase);
                g.$$phase = t
            }
            function d(t, e) {
                var n = o(t);
                return G(n, e),
                n
            }
            function f(t, e, n) {
                do t.$$listenerCount[n] -= e,
                0 === t.$$listenerCount[n] && delete t.$$listenerCount[n];
                while (t = t.$parent)
            }
            function m() {}
            c.prototype = {
                "constructor": c,
                "$new": function(t) {
                    return t ? (t = new c, t.$root = this.$root, t.$$asyncQueue = this.$$asyncQueue, t.$$postDigestQueue = this.$$postDigestQueue) : (this.$$childScopeClass || (this.$$childScopeClass = function() {
                        this.$$watchers = this.$$nextSibling = this.$$childHead = this.$$childTail = null,
                        this.$$listeners = {},
                        this.$$listenerCount = {},
                        this.$id = u(),
                        this.$$childScopeClass = null
                    },
                    this.$$childScopeClass.prototype = this), t = new this.$$childScopeClass),
                    t["this"] = t,
                    t.$parent = this,
                    t.$$prevSibling = this.$$childTail,
                    this.$$childHead ? this.$$childTail = this.$$childTail.$$nextSibling = t: this.$$childHead = this.$$childTail = t,
                    t
                },
                "$watch": function(t, e, i) {
                    var r = d(t, "watch"),
                    s = this.$$watchers,
                    a = {
                        "fn": e,
                        "last": m,
                        "get": r,
                        "exp": t,
                        "eq": !!i
                    };
                    if (n = null, !C(e)) {
                        var o = d(e || p, "listener");
                        a.fn = function(t, e, n) {
                            o(n)
                        }
                    }
                    if ("string" == typeof t && r.constant) {
                        var l = a.fn;
                        a.fn = function(t, e, n) {
                            l.call(this, t, e, n),
                            _(s, a)
                        }
                    }
                    return s || (s = this.$$watchers = []),
                    s.unshift(a),
                    function() {
                        _(s, a),
                        n = null
                    }
                },
                "$watchCollection": function(t, e) {
                    var n, i, s, a = this,
                    l = 1 < e.length,
                    u = 0,
                    c = o(t),
                    h = [],
                    d = {},
                    f = !0,
                    p = 0;
                    return this.$watch(function() {
                        n = c(a);
                        var t, e, s;
                        if (b(n)) if (r(n)) for (i !== h && (i = h, p = i.length = 0, u++), t = n.length, p !== t && (u++, i.length = p = t), e = 0; t > e; e++) s = i[e] !== i[e] && n[e] !== n[e],
                        s || i[e] === n[e] || (u++, i[e] = n[e]);
                        else {
                            i !== d && (i = d = {},
                            p = 0, u++),
                            t = 0;
                            for (e in n) n.hasOwnProperty(e) && (t++, i.hasOwnProperty(e) ? (s = i[e] !== i[e] && n[e] !== n[e], s || i[e] === n[e] || (u++, i[e] = n[e])) : (p++, i[e] = n[e], u++));
                            if (p > t) for (e in u++, i) i.hasOwnProperty(e) && !n.hasOwnProperty(e) && (p--, delete i[e])
                        } else i !== n && (i = n, u++);
                        return u
                    },
                    function() {
                        if (f ? (f = !1, e(n, n, a)) : e(n, s, a), l) if (b(n)) if (r(n)) {
                            s = Array(n.length);
                            for (var t = 0; t < n.length; t++) s[t] = n[t]
                        } else for (t in s = {},
                        n) Xn.call(n, t) && (s[t] = n[t]);
                        else s = n
                    })
                },
                "$digest": function() {
                    var i, r, s, o, u, c, d, f, p, v, y = this.$$asyncQueue,
                    b = this.$$postDigestQueue,
                    w = t,
                    $ = [];
                    h("$digest"),
                    l.$$checkUrlChange(),
                    n = null;
                    do {
                        for (c = !1, d = this; y.length;) {
                            try {
                                v = y.shift(),
                                v.scope.$eval(v.expression)
                            } catch(x) {
                                g.$$phase = null,
                                a(x)
                            }
                            n = null
                        }
                        t: do {
                            if (o = d.$$watchers) for (u = o.length; u--;) try {
                                if (i = o[u]) if ((r = i.get(d)) === (s = i.last) || (i.eq ? O(r, s) : "number" == typeof r && "number" == typeof s && isNaN(r) && isNaN(s))) {
                                    if (i === n) {
                                        c = !1;
                                        break t
                                    }
                                } else c = !0,
                                n = i,
                                i.last = i.eq ? M(r, null) : r,
                                i.fn(r, s === m ? r: s, d),
                                5 > w && (f = 4 - w, $[f] || ($[f] = []), p = C(i.exp) ? "fn: " + (i.exp.name || i.exp.toString()) : i.exp, p += "; newVal: " + j(r) + "; oldVal: " + j(s), $[f].push(p))
                            } catch(k) {
                                g.$$phase = null,
                                a(k)
                            }
                            if (! (o = d.$$childHead || d !== this && d.$$nextSibling)) for (; d !== this && !(o = d.$$nextSibling);) d = d.$parent
                        } while ( d = o );
                        if ((c || y.length) && !w--) throw g.$$phase = null, e("infdig", t, j($))
                    } while ( c || y . length );
                    for (g.$$phase = null; b.length;) try {
                        b.shift()()
                    } catch(T) {
                        a(T)
                    }
                },
                "$destroy": function() {
                    if (!this.$$destroyed) {
                        var t = this.$parent;
                        this.$broadcast("$destroy"),
                        this.$$destroyed = !0,
                        this !== g && (s(this.$$listenerCount, F(null, f, this)), t.$$childHead == this && (t.$$childHead = this.$$nextSibling), t.$$childTail == this && (t.$$childTail = this.$$prevSibling), this.$$prevSibling && (this.$$prevSibling.$$nextSibling = this.$$nextSibling), this.$$nextSibling && (this.$$nextSibling.$$prevSibling = this.$$prevSibling), this.$parent = this.$$nextSibling = this.$$prevSibling = this.$$childHead = this.$$childTail = this.$root = null, this.$$listeners = {},
                        this.$$watchers = this.$$asyncQueue = this.$$postDigestQueue = [], this.$destroy = this.$digest = this.$apply = p, this.$on = this.$watch = function() {
                            return p
                        })
                    }
                },
                "$eval": function(t, e) {
                    return o(t)(this, e)
                },
                "$evalAsync": function(t) {
                    g.$$phase || g.$$asyncQueue.length || l.defer(function() {
                        g.$$asyncQueue.length && g.$digest()
                    }),
                    this.$$asyncQueue.push({
                        "scope": this,
                        "expression": t
                    })
                },
                "$$postDigest": function(t) {
                    this.$$postDigestQueue.push(t)
                },
                "$apply": function(t) {
                    try {
                        return h("$apply"),
                        this.$eval(t)
                    } catch(e) {
                        a(e)
                    } finally {
                        g.$$phase = null;
                        try {
                            g.$digest()
                        } catch(n) {
                            throw a(n),
                            n
                        }
                    }
                },
                "$on": function(t, e) {
                    var n = this.$$listeners[t];
                    n || (this.$$listeners[t] = n = []),
                    n.push(e);
                    var i = this;
                    do i.$$listenerCount[t] || (i.$$listenerCount[t] = 0),
                    i.$$listenerCount[t]++;
                    while (i = i.$parent);
                    var r = this;
                    return function() {
                        var i = E(n, e); - 1 !== i && (n[i] = null, f(r, 1, t))
                    }
                },
                "$emit": function(t) {
                    var e, n, i, r = [],
                    s = this,
                    o = !1,
                    l = {
                        "name": t,
                        "targetScope": s,
                        "stopPropagation": function() {
                            o = !0
                        },
                        "preventDefault": function() {
                            l.defaultPrevented = !0
                        },
                        "defaultPrevented": !1
                    },
                    u = [l].concat(ti.call(arguments, 1));
                    do {
                        for (e = s.$$listeners[t] || r, l.currentScope = s, n = 0, i = e.length; i > n; n++) if (e[n]) try {
                            e[n].apply(null, u)
                        } catch(c) {
                            a(c)
                        } else e.splice(n, 1), n--, i--;
                        if (o) break;
                        s = s.$parent
                    } while ( s );
                    return l
                },
                "$broadcast": function(t) {
                    for (var e, n, i = this,
                    r = this,
                    s = {
                        "name": t,
                        "targetScope": this,
                        "preventDefault": function() {
                            s.defaultPrevented = !0
                        },
                        "defaultPrevented": !1
                    },
                    o = [s].concat(ti.call(arguments, 1)); i = r;) {
                        for (s.currentScope = i, r = i.$$listeners[t] || [], e = 0, n = r.length; n > e; e++) if (r[e]) try {
                            r[e].apply(null, o)
                        } catch(l) {
                            a(l)
                        } else r.splice(e, 1),
                        e--,
                        n--;
                        if (! (r = i.$$listenerCount[t] && i.$$childHead || i !== this && i.$$nextSibling)) for (; i !== this && !(r = i.$$nextSibling);) i = i.$parent
                    }
                    return s
                }
            };
            var g = new c;
            return g
        }]
    }
    function gn() {
        var t = /^\s*(https?|ftp|mailto|tel|file):/,
        e = /^\s*((https?|ftp|file):|data:image\/)/;
        this.aHrefSanitizationWhitelist = function(e) {
            return y(e) ? (t = e, this) : t
        },
        this.imgSrcSanitizationWhitelist = function(t) {
            return y(t) ? (e = t, this) : e
        },
        this.$get = function() {
            return function(n, i) {
                var r, s = i ? e: t;
                return Wn && !(Wn >= 8) || (r = Cn(n).href, "" === r || r.match(s)) ? n: "unsafe:" + r
            }
        }
    }
    function vn(t) {
        if ("self" === t) return t;
        if (w(t)) {
            if ( - 1 < t.indexOf("***")) throw Ji("iwcard", t);
            return t = t.replace(/([-()\[\]{}+?*.$\^|,:#<!\\])/g, "\\$1").replace(/\x08/g, "\\x08").replace("\\*\\*", ".*").replace("\\*", "[^:/.?&;]*"),
            RegExp("^" + t + "$")
        }
        if (k(t)) return RegExp("^" + t.source + "$");
        throw Ji("imatcher")
    }
    function yn(t) {
        var e = [];
        return y(t) && s(t,
        function(t) {
            e.push(vn(t))
        }),
        e
    }
    function bn() {
        this.SCE_CONTEXTS = Xi;
        var t = ["self"],
        e = [];
        this.resourceUrlWhitelist = function(e) {
            return arguments.length && (t = yn(e)),
            t
        },
        this.resourceUrlBlacklist = function(t) {
            return arguments.length && (e = yn(t)),
            e
        },
        this.$get = ["$injector",
        function(i) {
            function r(t) {
                var e = function(t) {
                    this.$$unwrapTrustedValue = function() {
                        return t
                    }
                };
                return t && (e.prototype = new t),
                e.prototype.valueOf = function() {
                    return this.$$unwrapTrustedValue()
                },
                e.prototype.toString = function() {
                    return this.$$unwrapTrustedValue().toString()
                },
                e
            }
            var s = function() {
                throw Ji("unsafe")
            };
            i.has("$sanitize") && (s = i.get("$sanitize"));
            var a = r(),
            o = {};
            return o[Xi.HTML] = r(a),
            o[Xi.CSS] = r(a),
            o[Xi.URL] = r(a),
            o[Xi.JS] = r(a),
            o[Xi.RESOURCE_URL] = r(o[Xi.URL]),
            {
                "trustAs": function(t, e) {
                    var i = o.hasOwnProperty(t) ? o[t] : null;
                    if (!i) throw Ji("icontext", t, e);
                    if (null === e || e === n || "" === e) return e;
                    if ("string" != typeof e) throw Ji("itype", t);
                    return new i(e)
                },
                "getTrusted": function(i, r) {
                    if (null === r || r === n || "" === r) return r;
                    var a = o.hasOwnProperty(i) ? o[i] : null;
                    if (a && r instanceof a) return r.$$unwrapTrustedValue();
                    if (i === Xi.RESOURCE_URL) {
                        var l, u, a = Cn(r.toString()),
                        c = !1;
                        for (l = 0, u = t.length; u > l; l++) if ("self" === t[l] ? kn(a) : t[l].exec(a.href)) {
                            c = !0;
                            break
                        }
                        if (c) for (l = 0, u = e.length; u > l; l++) if ("self" === e[l] ? kn(a) : e[l].exec(a.href)) {
                            c = !1;
                            break
                        }
                        if (c) return r;
                        throw Ji("insecurl", r.toString())
                    }
                    if (i === Xi.HTML) return s(r);
                    throw Ji("unsafe")
                },
                "valueOf": function(t) {
                    return t instanceof a ? t.$$unwrapTrustedValue() : t
                }
            }
        }]
    }
    function wn() {
        var t = !0;
        this.enabled = function(e) {
            return arguments.length && (t = !!e),
            t
        },
        this.$get = ["$parse", "$sniffer", "$sceDelegate",
        function(e, n, i) {
            if (t && n.msie && 8 > n.msieDocumentMode) throw Ji("iequirks");
            var r = A(Xi);
            r.isEnabled = function() {
                return t
            },
            r.trustAs = i.trustAs,
            r.getTrusted = i.getTrusted,
            r.valueOf = i.valueOf,
            t || (r.trustAs = r.getTrusted = function(t, e) {
                return e
            },
            r.valueOf = m),
            r.parseAs = function(t, n) {
                var i = e(n);
                return i.literal && i.constant ? i: function(e, n) {
                    return r.getTrusted(t, i(e, n))
                }
            };
            var a = r.parseAs,
            o = r.getTrusted,
            l = r.trustAs;
            return s(Xi,
            function(t, e) {
                var n = Jn(e);
                r[te("parse_as_" + n)] = function(e) {
                    return a(t, e)
                },
                r[te("get_trusted_" + n)] = function(e) {
                    return o(t, e)
                },
                r[te("trust_as_" + n)] = function(e) {
                    return l(t, e)
                }
            }),
            r
        }]
    }
    function $n() {
        this.$get = ["$window", "$document",
        function(t, e) {
            var n, i = {},
            r = d((/android (\d+)/.exec(Jn((t.navigator || {}).userAgent)) || [])[1]),
            s = /Boxee/i.test((t.navigator || {}).userAgent),
            a = e[0] || {},
            o = a.documentMode,
            l = /^(Moz|webkit|O|ms)(?=[A-Z])/,
            u = a.body && a.body.style,
            c = !1,
            h = !1;
            if (u) {
                for (var f in u) if (c = l.exec(f)) {
                    n = c[0],
                    n = n.substr(0, 1).toUpperCase() + n.substr(1);
                    break
                }
                n || (n = "WebkitOpacity" in u && "webkit"),
                c = !!("transition" in u || n + "Transition" in u),
                h = !!("animation" in u || n + "Animation" in u),
                !r || c && h || (c = w(a.body.style.webkitTransition), h = w(a.body.style.webkitAnimation))
            }
            return {
                "history": ! (!t.history || !t.history.pushState || 4 > r || s),
                "hashchange": "onhashchange" in t && (!o || o > 7),
                "hasEvent": function(t) {
                    if ("input" == t && 9 == Wn) return ! 1;
                    if (v(i[t])) {
                        var e = a.createElement("div");
                        i[t] = "on" + t in e
                    }
                    return i[t]
                },
                "csp": li(),
                "vendorPrefix": n,
                "transitions": c,
                "animations": h,
                "android": r,
                "msie": Wn,
                "msieDocumentMode": o
            }
        }]
    }
    function xn() {
        this.$get = ["$rootScope", "$browser", "$q", "$exceptionHandler",
        function(t, e, n, i) {
            function r(r, a, o) {
                var l = n.defer(),
                u = l.promise,
                c = y(o) && !o;
                return a = e.defer(function() {
                    try {
                        l.resolve(r())
                    } catch(e) {
                        l.reject(e),
                        i(e)
                    } finally {
                        delete s[u.$$timeoutId]
                    }
                    c || t.$apply()
                },
                a),
                u.$$timeoutId = a,
                s[a] = l,
                u
            }
            var s = {};
            return r.cancel = function(t) {
                return t && t.$$timeoutId in s ? (s[t.$$timeoutId].reject("canceled"), delete s[t.$$timeoutId], e.defer.cancel(t.$$timeoutId)) : !1
            },
            r
        }]
    }
    function Cn(t) {
        var e = t;
        return Wn && (Ki.setAttribute("href", e), e = Ki.href),
        Ki.setAttribute("href", e),
        {
            "href": Ki.href,
            "protocol": Ki.protocol ? Ki.protocol.replace(/:$/, "") : "",
            "host": Ki.host,
            "search": Ki.search ? Ki.search.replace(/^\?/, "") : "",
            "hash": Ki.hash ? Ki.hash.replace(/^#/, "") : "",
            "hostname": Ki.hostname,
            "port": Ki.port,
            "pathname": "/" === Ki.pathname.charAt(0) ? Ki.pathname: "/" + Ki.pathname
        }
    }
    function kn(t) {
        return t = w(t) ? Cn(t) : t,
        t.protocol === tr.protocol && t.host === tr.host
    }
    function Tn() {
        this.$get = g(t)
    }
    function Sn(t) {
        function e(i, r) {
            if (b(i)) {
                var a = {};
                return s(i,
                function(t, n) {
                    a[n] = e(n, t)
                }),
                a
            }
            return t.factory(i + n, r)
        }
        var n = "Filter";
        this.register = e,
        this.$get = ["$injector",
        function(t) {
            return function(e) {
                return t.get(e + n)
            }
        }],
        e("currency", En),
        e("date", Nn),
        e("filter", Dn),
        e("json", jn),
        e("limitTo", Pn),
        e("lowercase", sr),
        e("number", _n),
        e("orderBy", In),
        e("uppercase", ar)
    }
    function Dn() {
        return function(t, e, n) {
            if (!ai(t)) return t;
            var i = typeof n,
            r = [];
            r.check = function(t) {
                for (var e = 0; e < r.length; e++) if (!r[e](t)) return ! 1;
                return ! 0
            },
            "function" !== i && (n = "boolean" === i && n ?
            function(t, e) {
                return ri.equals(t, e)
            }: function(t, e) {
                if (t && e && "object" == typeof t && "object" == typeof e) {
                    for (var i in t) if ("$" !== i.charAt(0) && Xn.call(t, i) && n(t[i], e[i])) return ! 0;
                    return ! 1
                }
                return e = ("" + e).toLowerCase(),
                -1 < ("" + t).toLowerCase().indexOf(e)
            });
            var s = function(t, e) {
                if ("string" == typeof e && "!" === e.charAt(0)) return ! s(t, e.substr(1));
                switch (typeof t) {
                case "boolean":
                case "number":
                case "string":
                    return n(t, e);
                case "object":
                    switch (typeof e) {
                    case "object":
                        return n(t, e);
                    default:
                        for (var i in t) if ("$" !== i.charAt(0) && s(t[i], e)) return ! 0
                    }
                    return ! 1;
                case "array":
                    for (i = 0; i < t.length; i++) if (s(t[i], e)) return ! 0;
                    return ! 1;
                default:
                    return ! 1
                }
            };
            switch (typeof e) {
            case "boolean":
            case "number":
            case "string":
                e = {
                    "$": e
                };
            case "object":
                for (var a in e)(function(t) {
                    "undefined" != typeof e[t] && r.push(function(n) {
                        return s("$" == t ? n: n && n[t], e[t])
                    })
                })(a);
                break;
            case "function":
                r.push(e);
                break;
            default:
                return t
            }
            for (i = [], a = 0; a < t.length; a++) {
                var o = t[a];
                r.check(o) && i.push(o)
            }
            return i
        }
    }
    function En(t) {
        var e = t.NUMBER_FORMATS;
        return function(t, n) {
            return v(n) && (n = e.CURRENCY_SYM),
            Mn(t, e.PATTERNS[1], e.GROUP_SEP, e.DECIMAL_SEP, 2).replace(/\u00A4/g, n)
        }
    }
    function _n(t) {
        var e = t.NUMBER_FORMATS;
        return function(t, n) {
            return Mn(t, e.PATTERNS[0], e.GROUP_SEP, e.DECIMAL_SEP, n)
        }
    }
    function Mn(t, e, n, i, r) {
        if (null == t || !isFinite(t) || b(t)) return "";
        var s = 0 > t;
        t = Math.abs(t);
        var a = t + "",
        o = "",
        l = [],
        u = !1;
        if ( - 1 !== a.indexOf("e")) {
            var c = a.match(/([\d\.]+)e(-?)(\d+)/);
            c && "-" == c[2] && c[3] > r + 1 ? (a = "0", t = 0) : (o = a, u = !0)
        }
        if (u) r > 0 && t > -1 && 1 > t && (o = t.toFixed(r));
        else {
            a = (a.split(er)[1] || "").length,
            v(r) && (r = Math.min(Math.max(e.minFrac, a), e.maxFrac)),
            t = +(Math.round( + (t.toString() + "e" + r)).toString() + "e" + -r),
            0 === t && (s = !1),
            t = ("" + t).split(er),
            a = t[0],
            t = t[1] || "";
            var c = 0,
            h = e.lgSize,
            d = e.gSize;
            if (a.length >= h + d) for (c = a.length - h, u = 0; c > u; u++) 0 === (c - u) % d && 0 !== u && (o += n),
            o += a.charAt(u);
            for (u = c; u < a.length; u++) 0 === (a.length - u) % h && 0 !== u && (o += n),
            o += a.charAt(u);
            for (; t.length < r;) t += "0";
            r && "0" !== r && (o += i + t.substr(0, r))
        }
        return l.push(s ? e.negPre: e.posPre),
        l.push(o),
        l.push(s ? e.negSuf: e.posSuf),
        l.join("")
    }
    function An(t, e, n) {
        var i = "";
        for (0 > t && (i = "-", t = -t), t = "" + t; t.length < e;) t = "0" + t;
        return n && (t = t.substr(t.length - e)),
        i + t
    }
    function On(t, e, n, i) {
        return n = n || 0,
        function(r) {
            return r = r["get" + t](),
            (n > 0 || r > -n) && (r += n),
            0 === r && -12 == n && (r = 12),
            An(r, e, i)
        }
    }
    function Fn(t, e) {
        return function(n, i) {
            var r = n["get" + t](),
            s = Kn(e ? "SHORT" + t: t);
            return i[s][r]
        }
    }
    function Nn(t) {
        function e(t) {
            var e;
            if (e = t.match(n)) {
                t = new Date(0);
                var i = 0,
                r = 0,
                s = e[8] ? t.setUTCFullYear: t.setFullYear,
                a = e[8] ? t.setUTCHours: t.setHours;
                e[9] && (i = d(e[9] + e[10]), r = d(e[9] + e[11])),
                s.call(t, d(e[1]), d(e[2]) - 1, d(e[3])),
                i = d(e[4] || 0) - i,
                r = d(e[5] || 0) - r,
                s = d(e[6] || 0),
                e = Math.round(1e3 * parseFloat("0." + (e[7] || 0))),
                a.call(t, i, r, s, e)
            }
            return t
        }
        var n = /^(\d{4})-?(\d\d)-?(\d\d)(?:T(\d\d)(?::?(\d\d)(?::?(\d\d)(?:\.(\d+))?)?)?(Z|([+-])(\d\d):?(\d\d))?)?$/;
        return function(n, i) {
            var r, a, o = "",
            l = [];
            if (i = i || "mediumDate", i = t.DATETIME_FORMATS[i] || i, w(n) && (n = rr.test(n) ? d(n) : e(n)), $(n) && (n = new Date(n)), !x(n)) return n;
            for (; i;)(a = ir.exec(i)) ? (l = l.concat(ti.call(a, 1)), i = l.pop()) : (l.push(i), i = null);
            return s(l,
            function(e) {
                r = nr[e],
                o += r ? r(n, t.DATETIME_FORMATS) : e.replace(/(^'|'$)/g, "").replace(/''/g, "'")
            }),
            o
        }
    }
    function jn() {
        return function(t) {
            return j(t, !0)
        }
    }
    function Pn() {
        return function(t, e) {
            if (!ai(t) && !w(t)) return t;
            if (e = 1 / 0 === Math.abs(Number(e)) ? Number(e) : d(e), w(t)) return e ? e >= 0 ? t.slice(0, e) : t.slice(e, t.length) : "";
            var n, i, r = [];
            for (e > t.length ? e = t.length: e < -t.length && (e = -t.length), e > 0 ? (n = 0, i = e) : (n = t.length + e, i = t.length); i > n; n++) r.push(t[n]);
            return r
        }
    }
    function In(t) {
        return function(e, n, i) {
            function s(t, e) {
                return I(e) ?
                function(e, n) {
                    return t(n, e)
                }: t
            }
            function a(t, e) {
                var n = typeof t,
                i = typeof e;
                return n == i ? (x(t) && x(e) && (t = t.valueOf(), e = e.valueOf()), "string" == n && (t = t.toLowerCase(), e = e.toLowerCase()), t === e ? 0 : e > t ? -1 : 1) : i > n ? -1 : 1
            }
            return r(e) ? (n = ai(n) ? n: [n], 0 === n.length && (n = ["+"]), n = D(n,
            function(e) {
                var n = !1,
                i = e || m;
                if (w(e)) {
                    if (("+" == e.charAt(0) || "-" == e.charAt(0)) && (n = "-" == e.charAt(0), e = e.substring(1)), "" === e) return s(function(t, e) {
                        return a(t, e)
                    },
                    n);
                    if (i = t(e), i.constant) {
                        var r = i();
                        return s(function(t, e) {
                            return a(t[r], e[r])
                        },
                        n)
                    }
                }
                return s(function(t, e) {
                    return a(i(t), i(e))
                },
                n)
            }), ti.call(e).sort(s(function(t, e) {
                for (var i = 0; i < n.length; i++) {
                    var r = n[i](t, e);
                    if (0 !== r) return r
                }
                return 0
            },
            i))) : e
        }
    }
    function Ln(t) {
        return C(t) && (t = {
            "link": t
        }),
        t.restrict = t.restrict || "AC",
        g(t)
    }
    function Hn(t, e, n, i) {
        function r(e, n) {
            n = n ? "-" + z(n, "-") : "",
            i.setClass(t, (e ? br: wr) + n, (e ? wr: br) + n)
        }
        var a = this,
        o = t.parent().controller("form") || ur,
        l = 0,
        u = a.$error = {},
        c = [];
        a.$name = e.name || e.ngForm,
        a.$dirty = !1,
        a.$pristine = !0,
        a.$valid = !0,
        a.$invalid = !1,
        o.$addControl(a),
        t.addClass($r),
        r(!0),
        a.$addControl = function(t) {
            Q(t.$name, "input"),
            c.push(t),
            t.$name && (a[t.$name] = t)
        },
        a.$removeControl = function(t) {
            t.$name && a[t.$name] === t && delete a[t.$name],
            s(u,
            function(e, n) {
                a.$setValidity(n, !0, t)
            }),
            _(c, t)
        },
        a.$setValidity = function(t, e, n) {
            var i = u[t];
            if (e) i && (_(i, n), i.length || (l--, l || (r(e), a.$valid = !0, a.$invalid = !1), u[t] = !1, r(!0, t), o.$setValidity(t, !0, a)));
            else {
                if (l || r(e), i) {
                    if ( - 1 != E(i, n)) return
                } else u[t] = i = [],
                l++,
                r(!1, t),
                o.$setValidity(t, !1, a);
                i.push(n),
                a.$valid = !1,
                a.$invalid = !0
            }
        },
        a.$setDirty = function() {
            i.removeClass(t, $r),
            i.addClass(t, xr),
            a.$dirty = !0,
            a.$pristine = !1,
            o.$setDirty()
        },
        a.$setPristine = function() {
            i.removeClass(t, xr),
            i.addClass(t, $r),
            a.$dirty = !1,
            a.$pristine = !0,
            s(c,
            function(t) {
                t.$setPristine()
            })
        }
    }
    function Rn(t, e, i, r) {
        return t.$setValidity(e, i),
        i ? r: n
    }
    function Un(t, e) {
        var n, i;
        if (e) for (n = 0; n < e.length; ++n) if (i = e[n], t[i]) return ! 0;
        return ! 1
    }
    function qn(t, e, n, i, r) {
        b(r) && (t.$$hasNativeValidators = !0, t.$parsers.push(function(s) {
            return t.$error[e] || Un(r, i) || !Un(r, n) ? s: void t.$setValidity(e, !1)
        }))
    }
    function Yn(t, e, n, r, s, a) {
        var o = e.prop(Zn),
        l = e[0].placeholder,
        u = {},
        c = Jn(e[0].type);
        if (r.$$validityState = o, !s.android) {
            var h = !1;
            e.on("compositionstart",
            function() {
                h = !0
            }),
            e.on("compositionend",
            function() {
                h = !1,
                f()
            })
        }
        var f = function(i) {
            if (!h) {
                var s = e.val();
                Wn && "input" === (i || u).type && e[0].placeholder !== l ? l = e[0].placeholder: ("password" !== c && I(n.ngTrim || "T") && (s = oi(s)), i = o && r.$$hasNativeValidators, (r.$viewValue !== s || "" === s && i) && (t.$root.$$phase ? r.$setViewValue(s) : t.$apply(function() {
                    r.$setViewValue(s)
                })))
            }
        };
        if (s.hasEvent("input")) e.on("input", f);
        else {
            var p, m = function() {
                p || (p = a.defer(function() {
                    f(),
                    p = null
                }))
            };
            e.on("keydown",
            function(t) {
                t = t.keyCode,
                91 === t || t > 15 && 19 > t || t >= 37 && 40 >= t || m()
            }),
            s.hasEvent("paste") && e.on("paste cut", m)
        }
        e.on("change", f),
        r.$render = function() {
            e.val(r.$isEmpty(r.$viewValue) ? "": r.$viewValue)
        };
        var g = n.ngPattern;
        if (g && ((s = g.match(/^\/(.*)\/([gim]*)$/)) ? (g = RegExp(s[1], s[2]), s = function(t) {
            return Rn(r, "pattern", r.$isEmpty(t) || g.test(t), t)
        }) : s = function(n) {
            var s = t.$eval(g);
            if (!s || !s.test) throw i("ngPattern")("noregexp", g, s, L(e));
            return Rn(r, "pattern", r.$isEmpty(n) || s.test(n), n)
        },
        r.$formatters.push(s), r.$parsers.push(s)), n.ngMinlength) {
            var v = d(n.ngMinlength);
            s = function(t) {
                return Rn(r, "minlength", r.$isEmpty(t) || t.length >= v, t)
            },
            r.$parsers.push(s),
            r.$formatters.push(s)
        }
        if (n.ngMaxlength) {
            var y = d(n.ngMaxlength);
            s = function(t) {
                return Rn(r, "maxlength", r.$isEmpty(t) || t.length <= y, t)
            },
            r.$parsers.push(s),
            r.$formatters.push(s)
        }
    }
    function Vn(t, e) {
        return t = "ngClass" + t,
        ["$animate",
        function(n) {
            function i(t, e) {
                var n = [],
                i = 0;
                t: for (; i < t.length; i++) {
                    for (var r = t[i], s = 0; s < e.length; s++) if (r == e[s]) continue t;
                    n.push(r)
                }
                return n
            }
            function r(t) {
                if (!ai(t)) {
                    if (w(t)) return t.split(" ");
                    if (b(t)) {
                        var e = [];
                        return s(t,
                        function(t, n) {
                            t && (e = e.concat(n.split(" ")))
                        }),
                        e
                    }
                }
                return t
            }
            return {
                "restrict": "AC",
                "link": function(a, o, l) {
                    function u(t, e) {
                        var n = o.data("$classCounts") || {},
                        i = [];
                        return s(t,
                        function(t) { (e > 0 || n[t]) && (n[t] = (n[t] || 0) + e, n[t] === +(e > 0) && i.push(t))
                        }),
                        o.data("$classCounts", n),
                        i.join(" ")
                    }
                    function c(t) {
                        if (!0 === e || a.$index % 2 === e) {
                            var s = r(t || []);
                            if (h) {
                                if (!O(t, h)) {
                                    var c = r(h),
                                    d = i(s, c),
                                    s = i(c, s),
                                    s = u(s, -1),
                                    d = u(d, 1);
                                    0 === d.length ? n.removeClass(o, s) : 0 === s.length ? n.addClass(o, d) : n.setClass(o, d, s)
                                }
                            } else {
                                var d = u(s, 1);
                                l.$addClass(d)
                            }
                        }
                        h = A(t)
                    }
                    var h;
                    a.$watch(l[t], c, !0),
                    l.$observe("class",
                    function() {
                        c(a.$eval(l[t]))
                    }),
                    "ngClass" !== t && a.$watch("$index",
                    function(n, i) {
                        var s = 1 & n;
                        if (s !== (1 & i)) {
                            var o = r(a.$eval(l[t]));
                            s === e ? (s = u(o, 1), l.$addClass(s)) : (s = u(o, -1), l.$removeClass(s))
                        }
                    })
                }
            }
        }]
    }
    var Wn, zn, Bn, Gn, Qn, Zn = "validity",
    Jn = function(t) {
        return w(t) ? t.toLowerCase() : t
    },
    Xn = Object.prototype.hasOwnProperty,
    Kn = function(t) {
        return w(t) ? t.toUpperCase() : t
    },
    ti = [].slice,
    ei = [].push,
    ni = Object.prototype.toString,
    ii = i("ng"),
    ri = t.angular || (t.angular = {}),
    si = ["0", "0", "0"];
    Wn = d((/msie (\d+)/.exec(Jn(navigator.userAgent)) || [])[1]),
    isNaN(Wn) && (Wn = d((/trident\/.*; rv:(\d+)/.exec(Jn(navigator.userAgent)) || [])[1])),
    p.$inject = [],
    m.$inject = [];
    var ai = function() {
        return C(Array.isArray) ? Array.isArray: function(t) {
            return "[object Array]" === ni.call(t)
        }
    } (),
    oi = function() {
        return String.prototype.trim ?
        function(t) {
            return w(t) ? t.trim() : t
        }: function(t) {
            return w(t) ? t.replace(/^\s\s*/, "").replace(/\s\s*$/, "") : t
        }
    } ();
    Qn = 9 > Wn ?
    function(t) {
        return t = t.nodeName ? t: t[0],
        t.scopeName && "HTML" != t.scopeName ? Kn(t.scopeName + ":" + t.nodeName) : t.nodeName
    }: function(t) {
        return t.nodeName ? t.nodeName: t[0].nodeName
    };
    var li = function() {
        if (y(li.isActive_)) return li.isActive_;
        var t = !(!e.querySelector("[ng-csp]") && !e.querySelector("[data-ng-csp]"));
        if (!t) try {
            new Function("")
        } catch(n) {
            t = !0
        }
        return li.isActive_ = t
    },
    ui = /[A-Z]/g,
    ci = {
        "full": "1.2.28",
        "major": 1,
        "minor": 2,
        "dot": 28,
        "codeName": "finnish-disembarkation"
    };
    ne.expando = "ng339";
    var hi = ne.cache = {},
    di = 1,
    fi = t.document.addEventListener ?
    function(t, e, n) {
        t.addEventListener(e, n, !1)
    }: function(t, e, n) {
        t.attachEvent("on" + e, n)
    },
    pi = t.document.removeEventListener ?
    function(t, e, n) {
        t.removeEventListener(e, n, !1)
    }: function(t, e, n) {
        t.detachEvent("on" + e, n)
    };
    ne._data = function(t) {
        return this.cache[t[this.expando]] || {}
    };
    var mi = /([\:\-\_]+(.))/g,
    gi = /^moz([A-Z])/,
    vi = i("jqLite"),
    yi = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
    bi = /<|&#?\w+;/,
    wi = /<([\w:]+)/,
    $i = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
    xi = {
        "option": [1, '<select multiple="multiple">', "</select>"],
        "thead": [1, "<table>", "</table>"],
        "col": [2, "<table><colgroup>", "</colgroup></table>"],
        "tr": [2, "<table><tbody>", "</tbody></table>"],
        "td": [3, "<table><tbody><tr>", "</tr></tbody></table>"],
        "_default": [0, "", ""]
    };
    xi.optgroup = xi.option,
    xi.tbody = xi.tfoot = xi.colgroup = xi.caption = xi.thead,
    xi.th = xi.td;
    var Ci = ne.prototype = {
        "ready": function(n) {
            function i() {
                r || (r = !0, n())
            }
            var r = !1;
            "complete" === e.readyState ? setTimeout(i) : (this.on("DOMContentLoaded", i), ne(t).on("load", i))
        },
        "toString": function() {
            var t = [];
            return s(this,
            function(e) {
                t.push("" + e)
            }),
            "[" + t.join(", ") + "]"
        },
        "eq": function(t) {
            return zn(t >= 0 ? this[t] : this[this.length + t])
        },
        "length": 0,
        "push": ei,
        "sort": [].sort,
        "splice": [].splice
    },
    ki = {};
    s("multiple selected checked disabled readOnly required open".split(" "),
    function(t) {
        ki[Jn(t)] = t
    });
    var Ti = {};
    s("input select option textarea button form details".split(" "),
    function(t) {
        Ti[Kn(t)] = !0
    }),
    s({
        "data": le,
        "removeData": ae
    },
    function(t, e) {
        ne[e] = t
    }),
    s({
        "data": le,
        "inheritedData": pe,
        "scope": function(t) {
            return zn.data(t, "$scope") || pe(t.parentNode || t, ["$isolateScope", "$scope"])
        },
        "isolateScope": function(t) {
            return zn.data(t, "$isolateScope") || zn.data(t, "$isolateScopeNoTemplate")
        },
        "controller": fe,
        "injector": function(t) {
            return pe(t, "$injector")
        },
        "removeAttr": function(t, e) {
            t.removeAttribute(e)
        },
        "hasClass": ue,
        "css": function(t, e, i) {
            if (e = te(e), !y(i)) {
                var r;
                return 8 >= Wn && (r = t.currentStyle && t.currentStyle[e], "" === r && (r = "auto")),
                r = r || t.style[e],
                8 >= Wn && (r = "" === r ? n: r),
                r
            }
            t.style[e] = i
        },
        "attr": function(t, e, i) {
            var r = Jn(e);
            if (ki[r]) {
                if (!y(i)) return t[e] || (t.attributes.getNamedItem(e) || p).specified ? r: n;
                i ? (t[e] = !0, t.setAttribute(e, r)) : (t[e] = !1, t.removeAttribute(r))
            } else if (y(i)) t.setAttribute(e, i);
            else if (t.getAttribute) return t = t.getAttribute(e, 2),
            null === t ? n: t
        },
        "prop": function(t, e, n) {
            return y(n) ? void(t[e] = n) : t[e]
        },
        "text": function() {
            function t(t, n) {
                var i = e[t.nodeType];
                return v(n) ? i ? t[i] : "": void(t[i] = n)
            }
            var e = [];
            return 9 > Wn ? (e[1] = "innerText", e[3] = "nodeValue") : e[1] = e[3] = "textContent",
            t.$dv = "",
            t
        } (),
        "val": function(t, e) {
            if (v(e)) {
                if ("SELECT" === Qn(t) && t.multiple) {
                    var n = [];
                    return s(t.options,
                    function(t) {
                        t.selected && n.push(t.value || t.text)
                    }),
                    0 === n.length ? null: n
                }
                return t.value
            }
            t.value = e
        },
        "html": function(t, e) {
            if (v(e)) return t.innerHTML;
            for (var n = 0,
            i = t.childNodes; n < i.length; n++) re(i[n]);
            t.innerHTML = e
        },
        "empty": me
    },
    function(t, e) {
        ne.prototype[e] = function(e, i) {
            var r, s, a = this.length;
            if (t !== me && (2 == t.length && t !== ue && t !== fe ? e: i) === n) {
                if (b(e)) {
                    for (r = 0; a > r; r++) if (t === le) t(this[r], e);
                    else for (s in e) t(this[r], s, e[s]);
                    return this
                }
                for (r = t.$dv, a = r === n ? Math.min(a, 1) : a, s = 0; a > s; s++) {
                    var o = t(this[s], e, i);
                    r = r ? r + o: o
                }
                return r
            }
            for (r = 0; a > r; r++) t(this[r], e, i);
            return this
        }
    }),
    s({
        "removeData": ae,
        "dealoc": re,
        "on": function as(t, n, i, r) {
            if (y(r)) throw vi("onargs");
            var a = oe(t, "events"),
            o = oe(t, "handle");
            a || oe(t, "events", a = {}),
            o || oe(t, "handle", o = ve(t, a)),
            s(n.split(" "),
            function(n) {
                var r = a[n];
                if (!r) {
                    if ("mouseenter" == n || "mouseleave" == n) {
                        var s = e.body.contains || e.body.compareDocumentPosition ?
                        function(t, e) {
                            var n = 9 === t.nodeType ? t.documentElement: t,
                            i = e && e.parentNode;
                            return t === i || !(!i || 1 !== i.nodeType || !(n.contains ? n.contains(i) : t.compareDocumentPosition && 16 & t.compareDocumentPosition(i)))
                        }: function(t, e) {
                            if (e) for (; e = e.parentNode;) if (e === t) return ! 0;
                            return ! 1
                        };
                        a[n] = [],
                        as(t, {
                            "mouseleave": "mouseout",
                            "mouseenter": "mouseover"
                        } [n],
                        function(t) {
                            var e = t.relatedTarget;
                            e && (e === this || s(this, e)) || o(t, n)
                        })
                    } else fi(t, n, o),
                    a[n] = [];
                    r = a[n]
                }
                r.push(i)
            })
        },
        "off": se,
        "one": function(t, e, n) {
            t = zn(t),
            t.on(e,
            function i() {
                t.off(e, n),
                t.off(e, i)
            }),
            t.on(e, n)
        },
        "replaceWith": function(t, e) {
            var n, i = t.parentNode;
            re(t),
            s(new ne(e),
            function(e) {
                n ? i.insertBefore(e, n.nextSibling) : i.replaceChild(e, t),
                n = e
            })
        },
        "children": function(t) {
            var e = [];
            return s(t.childNodes,
            function(t) {
                1 === t.nodeType && e.push(t)
            }),
            e
        },
        "contents": function(t) {
            return t.contentDocument || t.childNodes || []
        },
        "append": function(t, e) {
            s(new ne(e),
            function(e) {
                1 !== t.nodeType && 11 !== t.nodeType || t.appendChild(e)
            })
        },
        "prepend": function(t, e) {
            if (1 === t.nodeType) {
                var n = t.firstChild;
                s(new ne(e),
                function(e) {
                    t.insertBefore(e, n)
                })
            }
        },
        "wrap": function(t, e) {
            e = zn(e)[0];
            var n = t.parentNode;
            n && n.replaceChild(e, t),
            e.appendChild(t)
        },
        "remove": function(t) {
            re(t);
            var e = t.parentNode;
            e && e.removeChild(t)
        },
        "after": function(t, e) {
            var n = t,
            i = t.parentNode;
            s(new ne(e),
            function(t) {
                i.insertBefore(t, n.nextSibling),
                n = t
            })
        },
        "addClass": he,
        "removeClass": ce,
        "toggleClass": function(t, e, n) {
            e && s(e.split(" "),
            function(e) {
                var i = n;
                v(i) && (i = !ue(t, e)),
                (i ? he: ce)(t, e)
            })
        },
        "parent": function(t) {
            return (t = t.parentNode) && 11 !== t.nodeType ? t: null
        },
        "next": function(t) {
            if (t.nextElementSibling) return t.nextElementSibling;
            for (t = t.nextSibling; null != t && 1 !== t.nodeType;) t = t.nextSibling;
            return t
        },
        "find": function(t, e) {
            return t.getElementsByTagName ? t.getElementsByTagName(e) : []
        },
        "clone": ie,
        "triggerHandler": function(t, e, n) {
            var i, r;
            i = e.type || e;
            var a = (oe(t, "events") || {})[i];
            a && (i = {
                "preventDefault": function() {
                    this.defaultPrevented = !0
                },
                "isDefaultPrevented": function() {
                    return ! 0 === this.defaultPrevented
                },
                "stopPropagation": p,
                "type": i,
                "target": t
            },
            e.type && (i = h(i, e)), e = A(a), r = n ? [i].concat(n) : [i], s(e,
            function(e) {
                e.apply(t, r)
            }))
        }
    },
    function(t, e) {
        ne.prototype[e] = function(e, n, i) {
            for (var r, s = 0; s < this.length; s++) v(r) ? (r = t(this[s], e, n, i), y(r) && (r = zn(r))) : de(r, t(this[s], e, n, i));
            return y(r) ? r: this
        },
        ne.prototype.bind = ne.prototype.on,
        ne.prototype.unbind = ne.prototype.off
    }),
    be.prototype = {
        "put": function(t, e) {
            this[ye(t, this.nextUid)] = e
        },
        "get": function(t) {
            return this[ye(t, this.nextUid)]
        },
        "remove": function(t) {
            var e = this[t = ye(t, this.nextUid)];
            return delete this[t],
            e
        }
    };
    var Si = /^function\s*[^\(]*\(\s*([^\)]*)\)/m,
    Di = /,/,
    Ei = /^\s*(_?)(\S+?)\1\s*$/,
    _i = /((\/\/.*$)|(\/\*[\s\S]*?\*\/))/gm,
    Mi = i("$injector"),
    Ai = i("$animate"),
    Oi = ["$provide",
    function(t) {
        this.$$selectors = {},
        this.register = function(e, n) {
            var i = e + "-animation";
            if (e && "." != e.charAt(0)) throw Ai("notcsel", e);
            this.$$selectors[e.substr(1)] = i,
            t.factory(i, n)
        },
        this.classNameFilter = function(t) {
            return 1 === arguments.length && (this.$$classNameFilter = t instanceof RegExp ? t: null),
            this.$$classNameFilter
        },
        this.$get = ["$timeout", "$$asyncCallback",
        function(t, e) {
            return {
                "enter": function(t, n, i, r) {
                    i ? i.after(t) : (n && n[0] || (n = i.parent()), n.append(t)),
                    r && e(r)
                },
                "leave": function(t, n) {
                    t.remove(),
                    n && e(n)
                },
                "move": function(t, e, n, i) {
                    this.enter(t, e, n, i)
                },
                "addClass": function(t, n, i) {
                    n = w(n) ? n: ai(n) ? n.join(" ") : "",
                    s(t,
                    function(t) {
                        he(t, n)
                    }),
                    i && e(i)
                },
                "removeClass": function(t, n, i) {
                    n = w(n) ? n: ai(n) ? n.join(" ") : "",
                    s(t,
                    function(t) {
                        ce(t, n)
                    }),
                    i && e(i)
                },
                "setClass": function(t, n, i, r) {
                    s(t,
                    function(t) {
                        he(t, n),
                        ce(t, i)
                    }),
                    r && e(r)
                },
                "enabled": p
            }
        }]
    }],
    Fi = i("$compile");
    Ee.$inject = ["$provide", "$$sanitizeUriProvider"];
    var Ni = /^(x[\:\-_]|data[\:\-_])/i,
    ji = i("$interpolate"),
    Pi = /^([^\?#]*)(\?([^#]*))?(#(.*))?$/,
    Ii = {
        "http": 80,
        "https": 443,
        "ftp": 21
    },
    Li = i("$location");
    Xe.prototype = Je.prototype = Ze.prototype = {
        "$$html5": !1,
        "$$replace": !1,
        "absUrl": Ke("$$absUrl"),
        "url": function(t) {
            return v(t) ? this.$$url: (t = Pi.exec(t), t[1] && this.path(decodeURIComponent(t[1])), (t[2] || t[1]) && this.search(t[3] || ""), this.hash(t[5] || ""), this)
        },
        "protocol": Ke("$$protocol"),
        "host": Ke("$$host"),
        "port": Ke("$$port"),
        "path": tn("$$path",
        function(t) {
            return t = null !== t ? t.toString() : "",
            "/" == t.charAt(0) ? t: "/" + t
        }),
        "search": function(t, e) {
            switch (arguments.length) {
            case 0:
                return this.$$search;
            case 1:
                if (w(t) || $(t)) t = t.toString(),
                this.$$search = R(t);
                else {
                    if (!b(t)) throw Li("isrcharg");
                    s(t,
                    function(e, n) {
                        null == e && delete t[n]
                    }),
                    this.$$search = t
                }
                break;
            default:
                v(e) || null === e ? delete this.$$search[t] : this.$$search[t] = e
            }
            return this.$$compose(),
            this
        },
        "hash": tn("$$hash",
        function(t) {
            return null !== t ? t.toString() : ""
        }),
        "replace": function() {
            return this.$$replace = !0,
            this
        }
    };
    var Hi, Ri = i("$parse"),
    Ui = {},
    qi = Function.prototype.call,
    Yi = Function.prototype.apply,
    Vi = Function.prototype.bind,
    Wi = {
        "null": function() {
            return null
        },
        "true": function() {
            return ! 0
        },
        "false": function() {
            return ! 1
        },
        "undefined": p,
        "+": function(t, e, i, r) {
            return i = i(t, e),
            r = r(t, e),
            y(i) ? y(r) ? i + r: i: y(r) ? r: n
        },
        "-": function(t, e, n, i) {
            return n = n(t, e),
            i = i(t, e),
            (y(n) ? n: 0) - (y(i) ? i: 0)
        },
        "*": function(t, e, n, i) {
            return n(t, e) * i(t, e)
        },
        "/": function(t, e, n, i) {
            return n(t, e) / i(t, e)
        },
        "%": function(t, e, n, i) {
            return n(t, e) % i(t, e)
        },
        "^": function(t, e, n, i) {
            return n(t, e) ^ i(t, e)
        },
        "=": p,
        "===": function(t, e, n, i) {
            return n(t, e) === i(t, e)
        },
        "!==": function(t, e, n, i) {
            return n(t, e) !== i(t, e)
        },
        "==": function(t, e, n, i) {
            return n(t, e) == i(t, e)
        },
        "!=": function(t, e, n, i) {
            return n(t, e) != i(t, e)
        },
        "<": function(t, e, n, i) {
            return n(t, e) < i(t, e)
        },
        ">": function(t, e, n, i) {
            return n(t, e) > i(t, e)
        },
        "<=": function(t, e, n, i) {
            return n(t, e) <= i(t, e)
        },
        ">=": function(t, e, n, i) {
            return n(t, e) >= i(t, e)
        },
        "&&": function(t, e, n, i) {
            return n(t, e) && i(t, e)
        },
        "||": function(t, e, n, i) {
            return n(t, e) || i(t, e)
        },
        "&": function(t, e, n, i) {
            return n(t, e) & i(t, e)
        },
        "|": function(t, e, n, i) {
            return i(t, e)(t, e, n(t, e))
        },
        "!": function(t, e, n) {
            return ! n(t, e)
        }
    },
    zi = {
        "n": "\n",
        "f": "\f",
        "r": "\r",
        "t": "	",
        "v": "",
        "'": "'",
        '"': '"'
    },
    Bi = function(t) {
        this.options = t
    };
    Bi.prototype = {
        "constructor": Bi,
        "lex": function(t) {
            for (this.text = t, this.index = 0, this.ch = n, this.lastCh = ":", this.tokens = []; this.index < this.text.length;) {
                if (this.ch = this.text.charAt(this.index), this.is("\"'")) this.readString(this.ch);
                else if (this.isNumber(this.ch) || this.is(".") && this.isNumber(this.peek())) this.readNumber();
                else if (this.isIdent(this.ch)) this.readIdent();
                else if (this.is("(){}[].,;:?")) this.tokens.push({
                    "index": this.index,
                    "text": this.ch
                }),
                this.index++;
                else {
                    if (this.isWhitespace(this.ch)) {
                        this.index++;
                        continue
                    }
                    t = this.ch + this.peek();
                    var e = t + this.peek(2),
                    i = Wi[this.ch],
                    r = Wi[t],
                    s = Wi[e];
                    s ? (this.tokens.push({
                        "index": this.index,
                        "text": e,
                        "fn": s
                    }), this.index += 3) : r ? (this.tokens.push({
                        "index": this.index,
                        "text": t,
                        "fn": r
                    }), this.index += 2) : i ? (this.tokens.push({
                        "index": this.index,
                        "text": this.ch,
                        "fn": i
                    }), this.index += 1) : this.throwError("Unexpected next character ", this.index, this.index + 1)
                }
                this.lastCh = this.ch
            }
            return this.tokens
        },
        "is": function(t) {
            return - 1 !== t.indexOf(this.ch)
        },
        "was": function(t) {
            return - 1 !== t.indexOf(this.lastCh)
        },
        "peek": function(t) {
            return t = t || 1,
            this.index + t < this.text.length ? this.text.charAt(this.index + t) : !1
        },
        "isNumber": function(t) {
            return t >= "0" && "9" >= t
        },
        "isWhitespace": function(t) {
            return " " === t || "\r" === t || "	" === t || "\n" === t || "" === t || "\xa0" === t
        },
        "isIdent": function(t) {
            return t >= "a" && "z" >= t || t >= "A" && "Z" >= t || "_" === t || "$" === t
        },
        "isExpOperator": function(t) {
            return "-" === t || "+" === t || this.isNumber(t)
        },
        "throwError": function(t, e, n) {
            throw n = n || this.index,
            e = y(e) ? "s " + e + "-" + this.index + " [" + this.text.substring(e, n) + "]": " " + n,
            Ri("lexerr", t, e, this.text)
        },
        "readNumber": function() {
            for (var t = "",
            e = this.index; this.index < this.text.length;) {
                var n = Jn(this.text.charAt(this.index));
                if ("." == n || this.isNumber(n)) t += n;
                else {
                    var i = this.peek();
                    if ("e" == n && this.isExpOperator(i)) t += n;
                    else if (this.isExpOperator(n) && i && this.isNumber(i) && "e" == t.charAt(t.length - 1)) t += n;
                    else {
                        if (!this.isExpOperator(n) || i && this.isNumber(i) || "e" != t.charAt(t.length - 1)) break;
                        this.throwError("Invalid exponent")
                    }
                }
                this.index++
            }
            t *= 1,
            this.tokens.push({
                "index": e,
                "text": t,
                "literal": !0,
                "constant": !0,
                "fn": function() {
                    return t
                }
            })
        },
        "readIdent": function() {
            for (var t, e, n, i, r = this,
            s = "",
            a = this.index; this.index < this.text.length && (i = this.text.charAt(this.index), "." === i || this.isIdent(i) || this.isNumber(i));)"." === i && (t = this.index),
            s += i,
            this.index++;
            if (t) for (e = this.index; e < this.text.length;) {
                if (i = this.text.charAt(e), "(" === i) {
                    n = s.substr(t - a + 1),
                    s = s.substr(0, t - a),
                    this.index = e;
                    break
                }
                if (!this.isWhitespace(i)) break;
                e++
            }
            if (a = {
                "index": a,
                "text": s
            },
            Wi.hasOwnProperty(s)) a.fn = Wi[s],
            a.literal = !0,
            a.constant = !0;
            else {
                var o = cn(s, this.options, this.text);
                a.fn = h(function(t, e) {
                    return o(t, e)
                },
                {
                    "assign": function(t, e) {
                        return an(t, s, e, r.text, r.options)
                    }
                })
            }
            this.tokens.push(a),
            n && (this.tokens.push({
                "index": t,
                "text": "."
            }), this.tokens.push({
                "index": t + 1,
                "text": n
            }))
        },
        "readString": function(t) {
            var e = this.index;
            this.index++;
            for (var n = "",
            i = t,
            r = !1; this.index < this.text.length;) {
                var s = this.text.charAt(this.index),
                i = i + s;
                if (r)"u" === s ? (r = this.text.substring(this.index + 1, this.index + 5), r.match(/[\da-f]{4}/i) || this.throwError("Invalid unicode escape [\\u" + r + "]"), this.index += 4, n += String.fromCharCode(parseInt(r, 16))) : n += zi[s] || s,
                r = !1;
                else if ("\\" === s) r = !0;
                else {
                    if (s === t) return this.index++,
                    void this.tokens.push({
                        "index": e,
                        "text": i,
                        "string": n,
                        "literal": !0,
                        "constant": !0,
                        "fn": function() {
                            return n
                        }
                    });
                    n += s
                }
                this.index++
            }
            this.throwError("Unterminated quote", e)
        }
    };
    var Gi = function(t, e, n) {
        this.lexer = t,
        this.$filter = e,
        this.options = n
    };
    Gi.ZERO = h(function() {
        return 0
    },
    {
        "constant": !0
    }),
    Gi.prototype = {
        "constructor": Gi,
        "parse": function(t) {
            return this.text = t,
            this.tokens = this.lexer.lex(t),
            t = this.statements(),
            0 !== this.tokens.length && this.throwError("is an unexpected token", this.tokens[0]),
            t.literal = !!t.literal,
            t.constant = !!t.constant,
            t
        },
        "primary": function() {
            var t;
            if (this.expect("(")) t = this.filterChain(),
            this.consume(")");
            else if (this.expect("[")) t = this.arrayDeclaration();
            else if (this.expect("{")) t = this.object();
            else {
                var e = this.expect(); (t = e.fn) || this.throwError("not a primary expression", e),
                t.literal = !!e.literal,
                t.constant = !!e.constant
            }
            for (var n; e = this.expect("(", "[", ".");)"(" === e.text ? (t = this.functionCall(t, n), n = null) : "[" === e.text ? (n = t, t = this.objectIndex(t)) : "." === e.text ? (n = t, t = this.fieldAccess(t)) : this.throwError("IMPOSSIBLE");
            return t
        },
        "throwError": function(t, e) {
            throw Ri("syntax", e.text, t, e.index + 1, this.text, this.text.substring(e.index))
        },
        "peekToken": function() {
            if (0 === this.tokens.length) throw Ri("ueoe", this.text);
            return this.tokens[0]
        },
        "peek": function(t, e, n, i) {
            if (0 < this.tokens.length) {
                var r = this.tokens[0],
                s = r.text;
                if (s === t || s === e || s === n || s === i || !(t || e || n || i)) return r
            }
            return ! 1
        },
        "expect": function(t, e, n, i) {
            return (t = this.peek(t, e, n, i)) ? (this.tokens.shift(), t) : !1
        },
        "consume": function(t) {
            this.expect(t) || this.throwError("is unexpected, expecting [" + t + "]", this.peek())
        },
        "unaryFn": function(t, e) {
            return h(function(n, i) {
                return t(n, i, e)
            },
            {
                "constant": e.constant
            })
        },
        "ternaryFn": function(t, e, n) {
            return h(function(i, r) {
                return t(i, r) ? e(i, r) : n(i, r)
            },
            {
                "constant": t.constant && e.constant && n.constant
            })
        },
        "binaryFn": function(t, e, n) {
            return h(function(i, r) {
                return e(i, r, t, n)
            },
            {
                "constant": t.constant && n.constant
            })
        },
        "statements": function() {
            for (var t = [];;) if (0 < this.tokens.length && !this.peek("}", ")", ";", "]") && t.push(this.filterChain()), !this.expect(";")) return 1 === t.length ? t[0] : function(e, n) {
                for (var i, r = 0; r < t.length; r++) {
                    var s = t[r];
                    s && (i = s(e, n))
                }
                return i
            }
        },
        "filterChain": function() {
            for (var t, e = this.expression();;) {
                if (! (t = this.expect("|"))) return e;
                e = this.binaryFn(e, t.fn, this.filter())
            }
        },
        "filter": function() {
            for (var t = this.expect(), e = this.$filter(t.text), n = [];;) {
                if (! (t = this.expect(":"))) {
                    var i = function(t, i, r) {
                        r = [r];
                        for (var s = 0; s < n.length; s++) r.push(n[s](t, i));
                        return e.apply(t, r)
                    };
                    return function() {
                        return i
                    }
                }
                n.push(this.expression())
            }
        },
        "expression": function() {
            return this.assignment()
        },
        "assignment": function() {
            var t, e, n = this.ternary();
            return (e = this.expect("=")) ? (n.assign || this.throwError("implies assignment but [" + this.text.substring(0, e.index) + "] can not be assigned to", e), t = this.ternary(),
            function(e, i) {
                return n.assign(e, t(e, i), i)
            }) : n
        },
        "ternary": function() {
            var t, e, n = this.logicalOR();
            return this.expect("?") ? (t = this.assignment(), (e = this.expect(":")) ? this.ternaryFn(n, t, this.assignment()) : void this.throwError("expected :", e)) : n
        },
        "logicalOR": function() {
            for (var t, e = this.logicalAND();;) {
                if (! (t = this.expect("||"))) return e;
                e = this.binaryFn(e, t.fn, this.logicalAND())
            }
        },
        "logicalAND": function() {
            var t, e = this.equality();
            return (t = this.expect("&&")) && (e = this.binaryFn(e, t.fn, this.logicalAND())),
            e
        },
        "equality": function() {
            var t, e = this.relational();
            return (t = this.expect("==", "!=", "===", "!==")) && (e = this.binaryFn(e, t.fn, this.equality())),
            e
        },
        "relational": function() {
            var t, e = this.additive();
            return (t = this.expect("<", ">", "<=", ">=")) && (e = this.binaryFn(e, t.fn, this.relational())),
            e
        },
        "additive": function() {
            for (var t, e = this.multiplicative(); t = this.expect("+", "-");) e = this.binaryFn(e, t.fn, this.multiplicative());
            return e
        },
        "multiplicative": function() {
            for (var t, e = this.unary(); t = this.expect("*", "/", "%");) e = this.binaryFn(e, t.fn, this.unary());
            return e
        },
        "unary": function() {
            var t;
            return this.expect("+") ? this.primary() : (t = this.expect("-")) ? this.binaryFn(Gi.ZERO, t.fn, this.unary()) : (t = this.expect("!")) ? this.unaryFn(t.fn, this.unary()) : this.primary()
        },
        "fieldAccess": function(t) {
            var e = this,
            n = this.expect().text,
            i = cn(n, this.options, this.text);
            return h(function(e, n, r) {
                return i(r || t(e, n))
            },
            {
                "assign": function(i, r, s) {
                    return (s = t(i, s)) || t.assign(i, s = {}),
                    an(s, n, r, e.text, e.options)
                }
            })
        },
        "objectIndex": function(t) {
            var e = this,
            i = this.expression();
            return this.consume("]"),
            h(function(r, s) {
                var a, o = t(r, s),
                l = i(r, s);
                return rn(l, e.text),
                o ? ((o = sn(o[l], e.text)) && o.then && e.options.unwrapPromises && (a = o, "$$v" in o || (a.$$v = n, a.then(function(t) {
                    a.$$v = t
                })), o = o.$$v), o) : n
            },
            {
                "assign": function(n, r, s) {
                    var a = rn(i(n, s), e.text);
                    return (s = sn(t(n, s), e.text)) || t.assign(n, s = {}),
                    s[a] = r
                }
            })
        },
        "functionCall": function(t, e) {
            var n = [];
            if (")" !== this.peekToken().text) do n.push(this.expression());
            while (this.expect(","));
            this.consume(")");
            var i = this;
            return function(r, s) {
                for (var a = [], o = e ? e(r, s) : r, l = 0; l < n.length; l++) a.push(sn(n[l](r, s), i.text));
                l = t(r, s, o) || p,
                sn(o, i.text);
                var u = i.text;
                if (l) {
                    if (l.constructor === l) throw Ri("isecfn", u);
                    if (l === qi || l === Yi || Vi && l === Vi) throw Ri("isecff", u)
                }
                return a = l.apply ? l.apply(o, a) : l(a[0], a[1], a[2], a[3], a[4]),
                sn(a, i.text)
            }
        },
        "arrayDeclaration": function() {
            var t = [],
            e = !0;
            if ("]" !== this.peekToken().text) do {
                if (this.peek("]")) break;
                var n = this.expression();
                t.push(n), n.constant || (e = !1)
            } while ( this . expect (","));
            return this.consume("]"),
            h(function(e, n) {
                for (var i = [], r = 0; r < t.length; r++) i.push(t[r](e, n));
                return i
            },
            {
                "literal": !0,
                "constant": e
            })
        },
        "object": function() {
            var t = [],
            e = !0;
            if ("}" !== this.peekToken().text) do {
                if (this.peek("}")) break;
                var n = this.expect(), n = n.string || n.text;
                this.consume(":");
                var i = this.expression();
                t.push({
                    "key": n,
                    "value": i
                }), i.constant || (e = !1)
            } while ( this . expect (","));
            return this.consume("}"),
            h(function(e, n) {
                for (var i = {},
                r = 0; r < t.length; r++) {
                    var s = t[r];
                    i[s.key] = s.value(e, n)
                }
                return i
            },
            {
                "literal": !0,
                "constant": e
            })
        }
    };
    var Qi = {},
    Zi = {},
    Ji = i("$sce"),
    Xi = {
        "HTML": "html",
        "CSS": "css",
        "URL": "url",
        "RESOURCE_URL": "resourceUrl",
        "JS": "js"
    },
    Ki = e.createElement("a"),
    tr = Cn(t.location.href, !0);
    Sn.$inject = ["$provide"],
    En.$inject = ["$locale"],
    _n.$inject = ["$locale"];
    var er = ".",
    nr = {
        "yyyy": On("FullYear", 4),
        "yy": On("FullYear", 2, 0, !0),
        "y": On("FullYear", 1),
        "MMMM": Fn("Month"),
        "MMM": Fn("Month", !0),
        "MM": On("Month", 2, 1),
        "M": On("Month", 1, 1),
        "dd": On("Date", 2),
        "d": On("Date", 1),
        "HH": On("Hours", 2),
        "H": On("Hours", 1),
        "hh": On("Hours", 2, -12),
        "h": On("Hours", 1, -12),
        "mm": On("Minutes", 2),
        "m": On("Minutes", 1),
        "ss": On("Seconds", 2),
        "s": On("Seconds", 1),
        "sss": On("Milliseconds", 3),
        "EEEE": Fn("Day"),
        "EEE": Fn("Day", !0),
        "a": function(t, e) {
            return 12 > t.getHours() ? e.AMPMS[0] : e.AMPMS[1]
        },
        "Z": function(t) {
            return t = -1 * t.getTimezoneOffset(),
            t = (t >= 0 ? "+": "") + (An(Math[t > 0 ? "floor": "ceil"](t / 60), 2) + An(Math.abs(t % 60), 2))
        }
    },
    ir = /((?:[^yMdHhmsaZE']+)|(?:'(?:[^']|'')*')|(?:E+|y+|M+|d+|H+|h+|m+|s+|a|Z))(.*)/,
    rr = /^\-?\d+$/;
    Nn.$inject = ["$locale"];
    var sr = g(Jn),
    ar = g(Kn);
    In.$inject = ["$parse"];
    var or = g({
        "restrict": "E",
        "compile": function(t, n) {
            return 8 >= Wn && (n.href || n.name || n.$set("href", ""), t.append(e.createComment("IE fix"))),
            n.href || n.xlinkHref || n.name ? void 0 : function(t, e) {
                var n = "[object SVGAnimatedString]" === ni.call(e.prop("href")) ? "xlink:href": "href";
                e.on("click",
                function(t) {
                    e.attr(n) || t.preventDefault()
                })
            }
        }
    }),
    lr = {};
    s(ki,
    function(t, e) {
        if ("multiple" != t) {
            var n = _e("ng-" + e);
            lr[n] = function() {
                return {
                    "priority": 100,
                    "link": function(t, i, r) {
                        t.$watch(r[n],
                        function(t) {
                            r.$set(e, !!t)
                        })
                    }
                }
            }
        }
    }),
    s(["src", "srcset", "href"],
    function(t) {
        var e = _e("ng-" + t);
        lr[e] = function() {
            return {
                "priority": 99,
                "link": function(n, i, r) {
                    var s = t,
                    a = t;
                    "href" === t && "[object SVGAnimatedString]" === ni.call(i.prop("href")) && (a = "xlinkHref", r.$attr[a] = "xlink:href", s = null),
                    r.$observe(e,
                    function(e) {
                        e ? (r.$set(a, e), Wn && s && i.prop(s, r[a])) : "href" === t && r.$set(a, null)
                    })
                }
            }
        }
    });
    var ur = {
        "$addControl": p,
        "$removeControl": p,
        "$setValidity": p,
        "$setDirty": p,
        "$setPristine": p
    };
    Hn.$inject = ["$element", "$attrs", "$scope", "$animate"];
    var cr = function(t) {
        return ["$timeout",
        function(e) {
            return {
                "name": "form",
                "restrict": t ? "EAC": "E",
                "controller": Hn,
                "compile": function() {
                    return {
                        "pre": function(t, i, r, s) {
                            if (!r.action) {
                                var a = function(t) {
                                    t.preventDefault ? t.preventDefault() : t.returnValue = !1
                                };
                                fi(i[0], "submit", a),
                                i.on("$destroy",
                                function() {
                                    e(function() {
                                        pi(i[0], "submit", a)
                                    },
                                    0, !1)
                                })
                            }
                            var o = i.parent().controller("form"),
                            l = r.name || r.ngForm;
                            l && an(t, l, s, l),
                            o && i.on("$destroy",
                            function() {
                                o.$removeControl(s),
                                l && an(t, l, n, l),
                                h(s, ur)
                            })
                        }
                    }
                }
            }
        }]
    },
    hr = cr(),
    dr = cr(!0),
    fr = /^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/,
    pr = /^[a-z0-9!#$%&'*+\/=?^_`{|}~.-]+@[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)*$/i,
    mr = /^\s*(\-|\+)?(\d+|(\d*(\.\d*)))\s*$/,
    gr = {
        "text": Yn,
        "number": function(t, e, i, r, s, a) {
            Yn(t, e, i, r, s, a),
            r.$parsers.push(function(t) {
                var e = r.$isEmpty(t);
                return e || mr.test(t) ? (r.$setValidity("number", !0), "" === t ? null: e ? t: parseFloat(t)) : (r.$setValidity("number", !1), n)
            }),
            qn(r, "number", vr, null, r.$$validityState),
            r.$formatters.push(function(t) {
                return r.$isEmpty(t) ? "": "" + t
            }),
            i.min && (t = function(t) {
                var e = parseFloat(i.min);
                return Rn(r, "min", r.$isEmpty(t) || t >= e, t)
            },
            r.$parsers.push(t), r.$formatters.push(t)),
            i.max && (t = function(t) {
                var e = parseFloat(i.max);
                return Rn(r, "max", r.$isEmpty(t) || e >= t, t)
            },
            r.$parsers.push(t), r.$formatters.push(t)),
            r.$formatters.push(function(t) {
                return Rn(r, "number", r.$isEmpty(t) || $(t), t)
            })
        },
        "url": function(t, e, n, i, r, s) {
            Yn(t, e, n, i, r, s),
            t = function(t) {
                return Rn(i, "url", i.$isEmpty(t) || fr.test(t), t)
            },
            i.$formatters.push(t),
            i.$parsers.push(t)
        },
        "email": function(t, e, n, i, r, s) {
            Yn(t, e, n, i, r, s),
            t = function(t) {
                return Rn(i, "email", i.$isEmpty(t) || pr.test(t), t)
            },
            i.$formatters.push(t),
            i.$parsers.push(t)
        },
        "radio": function(t, e, n, i) {
            v(n.name) && e.attr("name", u()),
            e.on("click",
            function() {
                e[0].checked && t.$apply(function() {
                    i.$setViewValue(n.value)
                })
            }),
            i.$render = function() {
                e[0].checked = n.value == i.$viewValue
            },
            n.$observe("value", i.$render)
        },
        "checkbox": function(t, e, n, i) {
            var r = n.ngTrueValue,
            s = n.ngFalseValue;
            w(r) || (r = !0),
            w(s) || (s = !1),
            e.on("click",
            function() {
                t.$apply(function() {
                    i.$setViewValue(e[0].checked)
                })
            }),
            i.$render = function() {
                e[0].checked = i.$viewValue
            },
            i.$isEmpty = function(t) {
                return t !== r
            },
            i.$formatters.push(function(t) {
                return t === r
            }),
            i.$parsers.push(function(t) {
                return t ? r: s
            })
        },
        "hidden": p,
        "button": p,
        "submit": p,
        "reset": p,
        "file": p
    },
    vr = ["badInput"],
    yr = ["$browser", "$sniffer",
    function(t, e) {
        return {
            "restrict": "E",
            "require": "?ngModel",
            "link": function(n, i, r, s) {
                s && (gr[Jn(r.type)] || gr.text)(n, i, r, s, e, t)
            }
        }
    }],
    br = "ng-valid",
    wr = "ng-invalid",
    $r = "ng-pristine",
    xr = "ng-dirty",
    Cr = ["$scope", "$exceptionHandler", "$attrs", "$element", "$parse", "$animate",
    function(t, e, n, r, a, o) {
        function l(t, e) {
            e = e ? "-" + z(e, "-") : "",
            o.removeClass(r, (t ? wr: br) + e),
            o.addClass(r, (t ? br: wr) + e)
        }
        this.$modelValue = this.$viewValue = Number.NaN,
        this.$parsers = [],
        this.$formatters = [],
        this.$viewChangeListeners = [],
        this.$pristine = !0,
        this.$dirty = !1,
        this.$valid = !0,
        this.$invalid = !1,
        this.$name = n.name;
        var u = a(n.ngModel),
        c = u.assign;
        if (!c) throw i("ngModel")("nonassign", n.ngModel, L(r));
        this.$render = p,
        this.$isEmpty = function(t) {
            return v(t) || "" === t || null === t || t !== t
        };
        var h = r.inheritedData("$formController") || ur,
        d = 0,
        f = this.$error = {};
        r.addClass($r),
        l(!0),
        this.$setValidity = function(t, e) {
            f[t] !== !e && (e ? (f[t] && d--, d || (l(!0), this.$valid = !0, this.$invalid = !1)) : (l(!1), this.$invalid = !0, this.$valid = !1, d++), f[t] = !e, l(e, t), h.$setValidity(t, e, this))
        },
        this.$setPristine = function() {
            this.$dirty = !1,
            this.$pristine = !0,
            o.removeClass(r, xr),
            o.addClass(r, $r)
        },
        this.$setViewValue = function(n) {
            this.$viewValue = n,
            this.$pristine && (this.$dirty = !0, this.$pristine = !1, o.removeClass(r, $r), o.addClass(r, xr), h.$setDirty()),
            s(this.$parsers,
            function(t) {
                n = t(n)
            }),
            this.$modelValue !== n && (this.$modelValue = n, c(t, n), s(this.$viewChangeListeners,
            function(t) {
                try {
                    t()
                } catch(n) {
                    e(n)
                }
            }))
        };
        var m = this;
        t.$watch(function() {
            var e = u(t);
            if (m.$modelValue !== e) {
                var n = m.$formatters,
                i = n.length;
                for (m.$modelValue = e; i--;) e = n[i](e);
                m.$viewValue !== e && (m.$viewValue = e, m.$render())
            }
            return e
        })
    }],
    kr = function() {
        return {
            "require": ["ngModel", "^?form"],
            "controller": Cr,
            "link": function(t, e, n, i) {
                var r = i[0],
                s = i[1] || ur;
                s.$addControl(r),
                t.$on("$destroy",
                function() {
                    s.$removeControl(r)
                })
            }
        }
    },
    Tr = g({
        "require": "ngModel",
        "link": function(t, e, n, i) {
            i.$viewChangeListeners.push(function() {
                t.$eval(n.ngChange)
            })
        }
    }),
    Sr = function() {
        return {
            "require": "?ngModel",
            "link": function(t, e, n, i) {
                if (i) {
                    n.required = !0;
                    var r = function(t) {
                        return n.required && i.$isEmpty(t) ? void i.$setValidity("required", !1) : (i.$setValidity("required", !0), t)
                    };
                    i.$formatters.push(r),
                    i.$parsers.unshift(r),
                    n.$observe("required",
                    function() {
                        r(i.$viewValue)
                    })
                }
            }
        }
    },
    Dr = function() {
        return {
            "require": "ngModel",
            "link": function(t, e, i, r) {
                var a = (t = /\/(.*)\//.exec(i.ngList)) && RegExp(t[1]) || i.ngList || ",";
                r.$parsers.push(function(t) {
                    if (!v(t)) {
                        var e = [];
                        return t && s(t.split(a),
                        function(t) {
                            t && e.push(oi(t))
                        }),
                        e
                    }
                }),
                r.$formatters.push(function(t) {
                    return ai(t) ? t.join(", ") : n
                }),
                r.$isEmpty = function(t) {
                    return ! t || !t.length
                }
            }
        }
    },
    Er = /^(true|false|\d+)$/,
    _r = function() {
        return {
            "priority": 100,
            "compile": function(t, e) {
                return Er.test(e.ngValue) ?
                function(t, e, n) {
                    n.$set("value", t.$eval(n.ngValue))
                }: function(t, e, n) {
                    t.$watch(n.ngValue,
                    function(t) {
                        n.$set("value", t)
                    })
                }
            }
        }
    },
    Mr = Ln({
        "compile": function(t) {
            return t.addClass("ng-binding"),
            function(t, e, i) {
                e.data("$binding", i.ngBind),
                t.$watch(i.ngBind,
                function(t) {
                    e.text(t == n ? "": t)
                })
            }
        }
    }),
    Ar = ["$interpolate",
    function(t) {
        return function(e, n, i) {
            e = t(n.attr(i.$attr.ngBindTemplate)),
            n.addClass("ng-binding").data("$binding", e),
            i.$observe("ngBindTemplate",
            function(t) {
                n.text(t)
            })
        }
    }],
    Or = ["$sce", "$parse",
    function(t, e) {
        return {
            "compile": function(n) {
                return n.addClass("ng-binding"),
                function(n, i, r) {
                    i.data("$binding", r.ngBindHtml);
                    var s = e(r.ngBindHtml);
                    n.$watch(function() {
                        return (s(n) || "").toString()
                    },
                    function() {
                        i.html(t.getTrustedHtml(s(n)) || "")
                    })
                }
            }
        }
    }],
    Fr = Vn("", !0),
    Nr = Vn("Odd", 0),
    jr = Vn("Even", 1),
    Pr = Ln({
        "compile": function(t, e) {
            e.$set("ngCloak", n),
            t.removeClass("ng-cloak")
        }
    }),
    Ir = [function() {
        return {
            "scope": !0,
            "controller": "@",
            "priority": 500
        }
    }],
    Lr = {},
    Hr = {
        "blur": !0,
        "focus": !0
    };
    s("click dblclick mousedown mouseup mouseover mouseout mousemove mouseenter mouseleave keydown keyup keypress submit focus blur copy cut paste".split(" "),
    function(t) {
        var e = _e("ng-" + t);
        Lr[e] = ["$parse", "$rootScope",
        function(n, i) {
            return {
                "compile": function(r, s) {
                    var a = n(s[e], !0);
                    return function(e, n) {
                        n.on(t,
                        function(n) {
                            var r = function() {
                                a(e, {
                                    "$event": n
                                })
                            };
                            Hr[t] && i.$$phase ? e.$evalAsync(r) : e.$apply(r)
                        })
                    }
                }
            }
        }]
    });
    var Rr = ["$animate",
    function(t) {
        return {
            "transclude": "element",
            "priority": 600,
            "terminal": !0,
            "restrict": "A",
            "$$tlb": !0,
            "link": function(n, i, r, s, a) {
                var o, l, u;
                n.$watch(r.ngIf,
                function(s) {
                    I(s) ? l || (l = n.$new(), a(l,
                    function(n) {
                        n[n.length++] = e.createComment(" end ngIf: " + r.ngIf + " "),
                        o = {
                            "clone": n
                        },
                        t.enter(n, i.parent(), i)
                    })) : (u && (u.remove(), u = null), l && (l.$destroy(), l = null), o && (u = J(o.clone), t.leave(u,
                    function() {
                        u = null
                    }), o = null))
                })
            }
        }
    }],
    Ur = ["$http", "$templateCache", "$anchorScroll", "$animate", "$sce",
    function(t, e, n, i, r) {
        return {
            "restrict": "ECA",
            "priority": 400,
            "terminal": !0,
            "transclude": "element",
            "controller": ri.noop,
            "compile": function(s, a) {
                var o = a.ngInclude || a.src,
                l = a.onload || "",
                u = a.autoscroll;
                return function(s, a, c, h, d) {
                    var f, p, m, g = 0,
                    v = function() {
                        p && (p.remove(), p = null),
                        f && (f.$destroy(), f = null),
                        m && (i.leave(m,
                        function() {
                            p = null
                        }), p = m, m = null)
                    };
                    s.$watch(r.parseAsResourceUrl(o),
                    function(r) {
                        var o = function() { ! y(u) || u && !s.$eval(u) || n()
                        },
                        c = ++g;
                        r ? (t.get(r, {
                            "cache": e
                        }).success(function(t) {
                            if (c === g) {
                                var e = s.$new();
                                h.template = t,
                                t = d(e,
                                function(t) {
                                    v(),
                                    i.enter(t, null, a, o)
                                }),
                                f = e,
                                m = t,
                                f.$emit("$includeContentLoaded"),
                                s.$eval(l)
                            }
                        }).error(function() {
                            c === g && v()
                        }), s.$emit("$includeContentRequested")) : (v(), h.template = null)
                    })
                }
            }
        }
    }],
    qr = ["$compile",
    function(t) {
        return {
            "restrict": "ECA",
            "priority": -400,
            "require": "ngInclude",
            "link": function(e, n, i, r) {
                n.html(r.template),
                t(n.contents())(e)
            }
        }
    }],
    Yr = Ln({
        "priority": 450,
        "compile": function() {
            return {
                "pre": function(t, e, n) {
                    t.$eval(n.ngInit)
                }
            }
        }
    }),
    Vr = Ln({
        "terminal": !0,
        "priority": 1e3
    }),
    Wr = ["$locale", "$interpolate",
    function(t, e) {
        var n = /{}/g;
        return {
            "restrict": "EA",
            "link": function(i, r, a) {
                var o = a.count,
                l = a.$attr.when && r.attr(a.$attr.when),
                u = a.offset || 0,
                c = i.$eval(l) || {},
                h = {},
                d = e.startSymbol(),
                f = e.endSymbol(),
                p = /^when(Minus)?(.+)$/;
                s(a,
                function(t, e) {
                    p.test(e) && (c[Jn(e.replace("when", "").replace("Minus", "-"))] = r.attr(a.$attr[e]))
                }),
                s(c,
                function(t, i) {
                    h[i] = e(t.replace(n, d + o + "-" + u + f))
                }),
                i.$watch(function() {
                    var e = parseFloat(i.$eval(o));
                    return isNaN(e) ? "": (e in c || (e = t.pluralCat(e - u)), h[e](i, r, !0))
                },
                function(t) {
                    r.text(t)
                })
            }
        }
    }],
    zr = ["$parse", "$animate",
    function(t, n) {
        var a = i("ngRepeat");
        return {
            "transclude": "element",
            "priority": 1e3,
            "terminal": !0,
            "$$tlb": !0,
            "link": function(i, o, l, u, c) {
                var h, d, f, p, m, g, v = l.ngRepeat,
                y = v.match(/^\s*([\s\S]+?)\s+in\s+([\s\S]+?)(?:\s+track\s+by\s+([\s\S]+?))?\s*$/),
                b = {
                    "$id": ye
                };
                if (!y) throw a("iexp", v);
                if (l = y[1], u = y[2], (y = y[3]) ? (h = t(y), d = function(t, e, n) {
                    return g && (b[g] = t),
                    b[m] = e,
                    b.$index = n,
                    h(i, b)
                }) : (f = function(t, e) {
                    return ye(e)
                },
                p = function(t) {
                    return t
                }), y = l.match(/^(?:([\$\w]+)|\(([\$\w]+)\s*,\s*([\$\w]+)\))$/), !y) throw a("iidexp", l);
                m = y[3] || y[1],
                g = y[2];
                var w = {};
                i.$watchCollection(u,
                function(t) {
                    var l, u, h, y, b, $, x, C, k, T, S = o[0],
                    D = {},
                    E = [];
                    if (r(t)) k = t,
                    C = d || f;
                    else {
                        C = d || p,
                        k = [];
                        for ($ in t) t.hasOwnProperty($) && "$" != $.charAt(0) && k.push($);
                        k.sort()
                    }
                    for (y = k.length, u = E.length = k.length, l = 0; u > l; l++) if ($ = t === k ? l: k[l], x = t[$], h = C($, x, l), Q(h, "`track by` id"), w.hasOwnProperty(h)) T = w[h],
                    delete w[h],
                    D[h] = T,
                    E[l] = T;
                    else {
                        if (D.hasOwnProperty(h)) throw s(E,
                        function(t) {
                            t && t.scope && (w[t.id] = t)
                        }),
                        a("dupes", v, h, j(x));
                        E[l] = {
                            "id": h
                        },
                        D[h] = !1
                    }
                    for ($ in w) w.hasOwnProperty($) && (T = w[$], l = J(T.clone), n.leave(l), s(l,
                    function(t) {
                        t.$$NG_REMOVED = !0
                    }), T.scope.$destroy());
                    for (l = 0, u = k.length; u > l; l++) {
                        if ($ = t === k ? l: k[l], x = t[$], T = E[l], E[l - 1] && (S = E[l - 1].clone[E[l - 1].clone.length - 1]), T.scope) {
                            b = T.scope,
                            h = S;
                            do h = h.nextSibling;
                            while (h && h.$$NG_REMOVED);
                            T.clone[0] != h && n.move(J(T.clone), null, zn(S)),
                            S = T.clone[T.clone.length - 1]
                        } else b = i.$new();
                        b[m] = x,
                        g && (b[g] = $),
                        b.$index = l,
                        b.$first = 0 === l,
                        b.$last = l === y - 1,
                        b.$middle = !(b.$first || b.$last),
                        b.$odd = !(b.$even = 0 === (1 & l)),
                        T.scope || c(b,
                        function(t) {
                            t[t.length++] = e.createComment(" end ngRepeat: " + v + " "),
                            n.enter(t, null, zn(S)),
                            S = t,
                            T.scope = b,
                            T.clone = t,
                            D[T.id] = T
                        })
                    }
                    w = D
                })
            }
        }
    }],
    Br = ["$animate",
    function(t) {
        return function(e, n, i) {
            e.$watch(i.ngShow,
            function(e) {
                t[I(e) ? "removeClass": "addClass"](n, "ng-hide")
            })
        }
    }],
    Gr = ["$animate",
    function(t) {
        return function(e, n, i) {
            e.$watch(i.ngHide,
            function(e) {
                t[I(e) ? "addClass": "removeClass"](n, "ng-hide")
            })
        }
    }],
    Qr = Ln(function(t, e, n) {
        t.$watch(n.ngStyle,
        function(t, n) {
            n && t !== n && s(n,
            function(t, n) {
                e.css(n, "")
            }),
            t && e.css(t)
        },
        !0)
    }),
    Zr = ["$animate",
    function(t) {
        return {
            "restrict": "EA",
            "require": "ngSwitch",
            "controller": ["$scope",
            function() {
                this.cases = {}
            }],
            "link": function(e, n, i, r) {
                var a = [],
                o = [],
                l = [],
                u = [];
                e.$watch(i.ngSwitch || i.on,
                function(n) {
                    var c, h;
                    for (c = 0, h = l.length; h > c; ++c) l[c].remove();
                    for (c = l.length = 0, h = u.length; h > c; ++c) {
                        var d = o[c];
                        u[c].$destroy(),
                        l[c] = d,
                        t.leave(d,
                        function() {
                            l.splice(c, 1)
                        })
                    }
                    o.length = 0,
                    u.length = 0,
                    (a = r.cases["!" + n] || r.cases["?"]) && (e.$eval(i.change), s(a,
                    function(n) {
                        var i = e.$new();
                        u.push(i),
                        n.transclude(i,
                        function(e) {
                            var i = n.element;
                            o.push(e),
                            t.enter(e, i.parent(), i)
                        })
                    }))
                })
            }
        }
    }],
    Jr = Ln({
        "transclude": "element",
        "priority": 800,
        "require": "^ngSwitch",
        "link": function(t, e, n, i, r) {
            i.cases["!" + n.ngSwitchWhen] = i.cases["!" + n.ngSwitchWhen] || [],
            i.cases["!" + n.ngSwitchWhen].push({
                "transclude": r,
                "element": e
            })
        }
    }),
    Xr = Ln({
        "transclude": "element",
        "priority": 800,
        "require": "^ngSwitch",
        "link": function(t, e, n, i, r) {
            i.cases["?"] = i.cases["?"] || [],
            i.cases["?"].push({
                "transclude": r,
                "element": e
            })
        }
    }),
    Kr = Ln({
        "link": function(t, e, n, r, s) {
            if (!s) throw i("ngTransclude")("orphan", L(e));
            s(function(t) {
                e.empty(),
                e.append(t)
            })
        }
    }),
    ts = ["$templateCache",
    function(t) {
        return {
            "restrict": "E",
            "terminal": !0,
            "compile": function(e, n) {
                "text/ng-template" == n.type && t.put(n.id, e[0].text)
            }
        }
    }],
    es = i("ngOptions"),
    ns = g({
        "terminal": !0
    }),
    is = ["$compile", "$parse",
    function(t, i) {
        var r = /^\s*([\s\S]+?)(?:\s+as\s+([\s\S]+?))?(?:\s+group\s+by\s+([\s\S]+?))?\s+for\s+(?:([\$\w][\$\w]*)|(?:\(\s*([\$\w][\$\w]*)\s*,\s*([\$\w][\$\w]*)\s*\)))\s+in\s+([\s\S]+?)(?:\s+track\s+by\s+([\s\S]+?))?$/,
        o = {
            "$setViewValue": p
        };
        return {
            "restrict": "E",
            "require": ["select", "?ngModel"],
            "controller": ["$element", "$scope", "$attrs",
            function(t, e, n) {
                var i, r = this,
                s = {},
                a = o;
                r.databound = n.ngModel,
                r.init = function(t, e, n) {
                    a = t,
                    i = n
                },
                r.addOption = function(e) {
                    Q(e, '"option value"'),
                    s[e] = !0,
                    a.$viewValue == e && (t.val(e), i.parent() && i.remove())
                },
                r.removeOption = function(t) {
                    this.hasOption(t) && (delete s[t], a.$viewValue == t && this.renderUnknownOption(t))
                },
                r.renderUnknownOption = function(e) {
                    e = "? " + ye(e) + " ?",
                    i.val(e),
                    t.prepend(i),
                    t.val(e),
                    i.prop("selected", !0)
                },
                r.hasOption = function(t) {
                    return s.hasOwnProperty(t)
                },
                e.$on("$destroy",
                function() {
                    r.renderUnknownOption = p
                })
            }],
            "link": function(o, l, u, c) {
                function h(t, e, n, i) {
                    n.$render = function() {
                        var t = n.$viewValue;
                        i.hasOption(t) ? (C.parent() && C.remove(), e.val(t), "" === t && m.prop("selected", !0)) : v(t) && m ? e.val("") : i.renderUnknownOption(t)
                    },
                    e.on("change",
                    function() {
                        t.$apply(function() {
                            C.parent() && C.remove(),
                            n.$setViewValue(e.val())
                        })
                    })
                }
                function d(t, e, n) {
                    var i;
                    n.$render = function() {
                        var t = new be(n.$viewValue);
                        s(e.find("option"),
                        function(e) {
                            e.selected = y(t.get(e.value))
                        })
                    },
                    t.$watch(function() {
                        O(i, n.$viewValue) || (i = A(n.$viewValue), n.$render())
                    }),
                    e.on("change",
                    function() {
                        t.$apply(function() {
                            var t = [];
                            s(e.find("option"),
                            function(e) {
                                e.selected && t.push(e.value)
                            }),
                            n.$setViewValue(t)
                        })
                    })
                }
                function f(e, s, o) {
                    function l() {
                        var t, n, i, r, l, u = {
                            "": []
                        },
                        b = [""];
                        i = o.$modelValue,
                        r = v(e) || [];
                        var T, S, D, E = d ? a(r) : r;
                        if (S = {},
                        D = !1, g) if (n = o.$modelValue, C && ai(n)) for (D = new be([]), t = {},
                        l = 0; l < n.length; l++) t[h] = n[l],
                        D.put(C(e, t), n[l]);
                        else D = new be(n);
                        l = D;
                        var _, M;
                        for (D = 0; T = E.length, T > D; D++) {
                            if (n = D, d) {
                                if (n = E[D], "$" === n.charAt(0)) continue;
                                S[d] = n
                            }
                            S[h] = r[n],
                            t = f(e, S) || "",
                            (n = u[t]) || (n = u[t] = [], b.push(t)),
                            g ? t = y(l.remove(C ? C(e, S) : m(e, S))) : (C ? (t = {},
                            t[h] = i, t = C(e, t) === C(e, S)) : t = i === m(e, S), l = l || t),
                            _ = c(e, S),
                            _ = y(_) ? _: "",
                            n.push({
                                "id": C ? C(e, S) : d ? E[D] : D,
                                "label": _,
                                "selected": t
                            })
                        }
                        for (g || (w || null === i ? u[""].unshift({
                            "id": "",
                            "label": "",
                            "selected": !l
                        }) : l || u[""].unshift({
                            "id": "?",
                            "label": "",
                            "selected": !0
                        })), S = 0, E = b.length; E > S; S++) {
                            for (t = b[S], n = u[t], k.length <= S ? (i = {
                                "element": x.clone().attr("label", t),
                                "label": n.label
                            },
                            r = [i], k.push(r), s.append(i.element)) : (r = k[S], i = r[0], i.label != t && i.element.attr("label", i.label = t)), _ = null, D = 0, T = n.length; T > D; D++) t = n[D],
                            (l = r[D + 1]) ? (_ = l.element, l.label !== t.label && (_.text(l.label = t.label), _.prop("label", l.label)), l.id !== t.id && _.val(l.id = t.id), _[0].selected !== t.selected && (_.prop("selected", l.selected = t.selected), Wn && _.prop("selected", l.selected))) : ("" === t.id && w ? M = w: (M = $.clone()).val(t.id).prop("selected", t.selected).attr("selected", t.selected).prop("label", t.label).text(t.label), r.push({
                                "element": M,
                                "label": t.label,
                                "id": t.id,
                                "selected": t.selected
                            }), p.addOption(t.label, M), _ ? _.after(M) : i.element.append(M), _ = M);
                            for (D++; r.length > D;) t = r.pop(),
                            p.removeOption(t.label),
                            t.element.remove()
                        }
                        for (; k.length > S;) k.pop()[0].element.remove()
                    }
                    var u;
                    if (! (u = b.match(r))) throw es("iexp", b, L(s));
                    var c = i(u[2] || u[1]),
                    h = u[4] || u[6],
                    d = u[5],
                    f = i(u[3] || ""),
                    m = i(u[2] ? u[1] : h),
                    v = i(u[7]),
                    C = u[8] ? i(u[8]) : null,
                    k = [[{
                        "element": s,
                        "label": ""
                    }]];
                    w && (t(w)(e), w.removeClass("ng-scope"), w.remove()),
                    s.empty(),
                    s.on("change",
                    function() {
                        e.$apply(function() {
                            var t, i, r, a, u, c, f, p, y = v(e) || [],
                            b = {};
                            if (g) {
                                for (r = [], u = 0, f = k.length; f > u; u++) for (t = k[u], a = 1, c = t.length; c > a; a++) if ((i = t[a].element)[0].selected) {
                                    if (i = i.val(), d && (b[d] = i), C) for (p = 0; p < y.length && (b[h] = y[p], C(e, b) != i); p++);
                                    else b[h] = y[i];
                                    r.push(m(e, b))
                                }
                            } else if (i = s.val(), "?" == i) r = n;
                            else if ("" === i) r = null;
                            else if (C) {
                                for (p = 0; p < y.length; p++) if (b[h] = y[p], C(e, b) == i) {
                                    r = m(e, b);
                                    break
                                }
                            } else b[h] = y[i],
                            d && (b[d] = i),
                            r = m(e, b);
                            o.$setViewValue(r),
                            l()
                        })
                    }),
                    o.$render = l,
                    e.$watchCollection(v, l),
                    e.$watchCollection(function() {
                        var t = {},
                        n = v(e);
                        if (n) {
                            for (var i = Array(n.length), r = 0, s = n.length; s > r; r++) t[h] = n[r],
                            i[r] = c(e, t);
                            return i
                        }
                    },
                    l),
                    g && e.$watchCollection(function() {
                        return o.$modelValue
                    },
                    l)
                }
                if (c[1]) {
                    var p = c[0];
                    c = c[1];
                    var m, g = u.multiple,
                    b = u.ngOptions,
                    w = !1,
                    $ = zn(e.createElement("option")),
                    x = zn(e.createElement("optgroup")),
                    C = $.clone();
                    u = 0;
                    for (var k = l.children(), T = k.length; T > u; u++) if ("" === k[u].value) {
                        m = w = k.eq(u);
                        break
                    }
                    p.init(c, w, C),
                    g && (c.$isEmpty = function(t) {
                        return ! t || 0 === t.length
                    }),
                    b ? f(o, l, c) : g ? d(o, l, c) : h(o, l, c, p)
                }
            }
        }
    }],
    rs = ["$interpolate",
    function(t) {
        var e = {
            "addOption": p,
            "removeOption": p
        };
        return {
            "restrict": "E",
            "priority": 100,
            "compile": function(n, i) {
                if (v(i.value)) {
                    var r = t(n.text(), !0);
                    r || i.$set("value", n.text())
                }
                return function(t, n, i) {
                    var s = n.parent(),
                    a = s.data("$selectController") || s.parent().data("$selectController");
                    a && a.databound ? n.prop("selected", !1) : a = e,
                    r ? t.$watch(r,
                    function(t, e) {
                        i.$set("value", t),
                        t !== e && a.removeOption(e),
                        a.addOption(t)
                    }) : a.addOption(i.value),
                    n.on("$destroy",
                    function() {
                        a.removeOption(i.value)
                    })
                }
            }
        }
    }],
    ss = g({
        "restrict": "E",
        "terminal": !0
    });
    t.angular.bootstrap ? void 0 : ((Bn = t.jQuery) && Bn.fn.on ? (zn = Bn, h(Bn.fn, {
        "scope": Ci.scope,
        "isolateScope": Ci.isolateScope,
        "controller": Ci.controller,
        "injector": Ci.injector,
        "inheritedData": Ci.inheritedData
    }), ee("remove", !0, !0, !1), ee("empty", !1, !1, !1), ee("html", !1, !1, !0)) : zn = ne, ri.element = zn, K(ri), zn(e).ready(function() {
        V(e, W)
    }))
} (window, document),
!window.angular.$$csp() && window.angular.element(document).find("head").prepend('<style type="text/css">@charset "UTF-8";[ng\\:cloak],[ng-cloak],[data-ng-cloak],[x-ng-cloak],.ng-cloak,.x-ng-cloak,.ng-hide{display:none !important;}ng\\:form{display:block;}.ng-animate-block-transitions{transition:0s all!important;-webkit-transition:0s all!important;}.ng-hide-add-active,.ng-hide-remove{display:block!important;}</style>');