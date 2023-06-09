!function(t, e) {
    "use strict";
    var i = 1,
        n = {},
        o = {
            _setEntry: function(t, e) {
                var o = t.__pubSubUID;
                return o || (o = i++, t.__pubSubUID = o, n[o] = {
                    obj: t
                }), n[o][e] || (n[o][e] = []), n[o]
            },
            _getEntryProp: function(t, e) {
                var i = n[t.__pubSubUID];
                return i ? n[t.__pubSubUID][e] : null
            },
            _clearEntry: function(t) {
                var e = n[t.__pubSubUID];
                !e || e.subscriptions && e.subscriptions.length || e.receivers && e.receivers.length || (e.subscriptions = null, e.receivers = null, e.obj = null, delete n[t.__pubSubUID], t.__pubSubUID = null)
            },
            subscribe: function(t, e, i) {
                for (var n, o = this._setEntry(t, "subscriptions"), r = 0, s = o.subscriptions, a = s.length; r < a; r++)
                    if (n = s[r], n.type === e && n.fn === i)
                        return;
                s.push({
                    type: e,
                    fn: i
                })
            },
            unsubscribe: function(t, e, i) {
                var n = this._getEntryProp(t, "subscriptions");
                if (n) {
                    for (var o, r = n.length; --r >= 0;)
                        o = n[r], o.type !== e || o.fn !== i && i || n.splice(r, 1);
                    n.length || this._clearEntry(t)
                }
            },
            publishAsync: function(t, e, i) {
                var n = this;
                setTimeout(function() {
                    n.publish(t, e, i)
                }, 0)
            },
            publish: function(t, e, i) {
                for (var n, o = this._setEntry(t, "subscriptions"), r = [], s = 0, a = o.subscriptions, h = a.length, u = o.receivers; s < h; s++)
                    n = a[s], n.type === e && r.push(n);
                for (h = r.length, s = 0; s < h; s++)
                    r[s].fn(i);
                if (u && (u.length || a.length) || this._clearEntry(t), u)
                    for (h = u.length, s = 0; s < h; s++)
                        this.publish(u[s], e, i)
            },
            inform: function(t, e) {
                if (!t || !e)
                    return !1;
                var i = this._setEntry(t, "receivers");
                return i.receivers.push(e), !0
            },
            conceal: function(t, e) {
                if (!t || !e)
                    return !1;
                var i = this._getEntryProp(t, "receivers");
                if (!i)
                    return !1;
                for (var n, o = i.length, r = !1; --o >= 0;)
                    n = i[o], n === e && (i.splice(o, 1), r = !0);
                return i.length || this._clearEntry(t), r
            }
        };
    "undefined" != typeof module && module.exports ? module.exports = o : "function" == typeof define && define.amd ? define("utils/Observer", [], function() {
        return o
    }) : t.Observer = o
}(this), function(t, e) {
    "use strict";
    var i = function() {
        this._thens = []
    };
    i.prototype = {
        then: function(t, e) {
            this._thens.push({
                resolve: t,
                reject: e
            })
        },
        resolve: function(t) {
            this._complete("resolve", t)
        },
        reject: function(t) {
            this._complete("reject", t)
        },
        _complete: function(t, e) {
            this.then = "resolve" === t ? function(t, i) {
                t(e)
            } : function(t, i) {
                i(e)
            }, this.resolve = this.reject = function() {
                throw new Error("Promise already completed.")
            };
            for (var i, n = 0; i = this._thens[n++];)
                i[t] && i[t](e);
            delete this._thens
        }
    }, "undefined" != typeof module && module.exports ? module.exports = i : "function" == typeof define && define.amd ? define("utils/Promise", [], function() {
        return i
    }) : t.Promise = i
}(this), function(t, e) {
    "use strict";
    var i,
        n = t.document ? t.document.body : null;
    i = n && n.compareDocumentPosition ? function(t, e) {
        return !!(16 & t.compareDocumentPosition(e))
    } : n && n.contains ? function(t, e) {
        return t != e && t.contains(e)
    } : function(t, e) {
        for (var i = e.parentNode; i;) {
            if (i === t)
                return !0;
            i = i.parentNode
        }
        return !1
    }, "undefined" != typeof module && module.exports ? module.exports = i : "function" == typeof define && define.amd ? define("utils/contains", [], function() {
        return i
    }) : t.contains = i
}(this), function(t, e) {
    "use strict";
    var i = null,
        n = null,
        o = t.document ? t.document.body : null;
    !o || o.matches ? n = "matches" : (o.webkitMatchesSelector ? n = "webkit" : o.mozMatchesSelector ? n = "moz" : o.msMatchesSelector ? n = "ms" : o.oMatchesSelector && (n = "o"), n += "MatchesSelector"), i = n ? function(t, e) {
        return t[n](e)
    } : function(e, i) {
        for (var n = (e.parentNode || t.document).querySelectorAll(i) || [], o = n.length; o--;)
            if (n[o] == e)
                return !0;
        return !1
    }, "undefined" != typeof module && module.exports ? module.exports = i : "function" == typeof define && define.amd ? define("utils/matchesSelector", [], function() {
        return i
    }) : t.matchesSelector = i
}(this), function(t, e) {
    "use strict";
    var i = function(t, e) {
        var n = Array.isArray(e),
            o = n && [] || {};
        return e = e || {}, n ? o = e.concat() : (t && "object" == typeof t && Object.keys(t).forEach(function(e) {
            o[e] = t[e]
        }), Object.keys(e).forEach(function(n) {
            "object" == typeof e[n] && e[n] && t[n] ? o[n] = i(t[n], e[n]) : o[n] = e[n]
        })), o
    };
    "undefined" != typeof module && module.exports ? module.exports = i : "function" == typeof define && define.amd ? define("utils/mergeObjects", [], function() {
        return i
    }) : t.mergeObjects = i
}(this), !function(t, e) {
    "use strict";
    var i = function(e, i, n, o, r, s) {
        var a,
            h,
            u,
            l = function(t, e) {
                this._path = t, this._expected = e, this._watches = [], this._count = 0, this._monitor = null
            };
        l.prototype = {
            getPath: function() {
                return this._path
            },
            getExpected: function() {
                return this._expected
            },
            isTrue: function() {
                for (var t = 0, e = this._count; e > t; t++)
                    if (!this._watches[t].valid)
                        return !1;
                return !0
            },
            assignMonitor: function(t) {
                this._monitor = t
            },
            assignWatches: function(t) {
                this._watches = t, this._count = t.length
            },
            getMonitor: function() {
                return this._monitor
            },
            getWatches: function() {
                return this._watches
            },
            toString: function() {
                return this._path + ":{" + this._expected + "}"
            }
        };
        var c = function(t, e) {
            this._expression = t, this._change = e, this._currentState = null
        };
        c.prototype = {
            evaluate: function() {
                var t = this._expression.isTrue();
                t != this._currentState && (this._currentState = t, this._change(t))
            }
        };
        var d = function() {
            this._uid = 1, this._db = [], this._expressions = []
        };
        d.prototype = {
            _parse: function(t, e) {
                if (this._expressions[t])
                    return this._expressions[t];
                for (var i, n, o, r = 0, s = t.split(","), a = s.length, h = []; a > r; r++)
                    i = s[r].split(":"), n = 0 === i[0].indexOf("was "), o = n ? i[0].substr(4) : i[0], h.push({
                        retain: n,
                        test: o,
                        value: e ? o : "undefined" == typeof i[1] || i[1]
                    });
                return this._expressions[t] = h, h
            },
            _mergeData: function(t, e, i) {
                return s({
                    element: i,
                    expected: e
                }, t)
            },
            create: function(t, e) {
                var i = new n,
                    o = t.getPath(),
                    r = t.getExpected(),
                    s = this;
                return a.loader.require([a.paths.monitors + o], function(n) {
                    var a,
                        h,
                        u,
                        l,
                        c,
                        d,
                        p,
                        f,
                        _ = 0,
                        m = s._db[o],
                        g = n.unload ? s._uid++ : o;
                    if (!m) {
                        if (m = {
                            watches: [],
                            change: function(t) {
                                for (_ = 0, a = m.watches.length; a > _; _++)
                                    m.watches[_].test(t)
                            }
                        }, p = n.unload ? s._mergeData(n.data, r, e) : n.data, "function" == typeof n.unload && (m.unload = function(t) {
                            return function() {
                                n.unload(t)
                            }
                        }(p)), "function" == typeof n.trigger)
                            n.trigger(m.change, p);
                        else
                            for (c in n.trigger)
                                n.trigger.hasOwnProperty(c) && n.trigger[c].addEventListener(c, m.change, !1);
                        s._db[g] = m
                    }
                    for (t.assignMonitor(g), u = [], f = "function" == typeof n.test, l = n.parse ? n.parse(r, f) : s._parse(r, f), a = l.length; a > _; _++)
                        d = l[_], h = {
                            changed: null,
                            retain: d.retain,
                            retained: null,
                            valid: null,
                            data: n.unload ? p : s._mergeData(n.data, d.value, e),
                            test: function(t) {
                                return function(e) {
                                    if (!this.retained) {
                                        var i = t(this.data, e);
                                        this.valid != i && (this.valid = i, this.changed && this.changed()), this.valid && this.retain && (this.retained = !0)
                                    }
                                }
                            }(f ? n.test : n.test[d.test])
                        }, h.test(), u.push(h);
                    m.watches = m.watches.concat(u), i.resolve(u)
                }), i
            },
            destroy: function(t) {
                var e = t.getMonitor();
                if (null !== e) {
                    var i,
                        n = this._db[e],
                        o = n.watches,
                        r = o.length;
                    t.getWatches().forEach(function(t) {
                        for (i = 0; r > i; i++)
                            o[i] === t && o.splice(i, 1)
                    }), n.unload && (n.unload(), this._db[e] = null)
                }
            }
        };
        var p = function(t, e, i) {
            var n = g.parse(t);
            this._element = e, this._tests = n.getTests(), this._condition = new c(n, i), this._conditionChangeBind = this._condition.evaluate.bind(this._condition), this._load()
        };
        p.prototype = {
            _load: function() {
                for (var t = 0, e = this._tests.length; e > t; t++)
                    this._setupMonitorForTest(this._tests[t])
            },
            _setupMonitorForTest: function(t) {
                var e,
                    i = this,
                    n = 0;
                h.create(t, this._element).then(function(o) {
                    for (t.assignWatches(o), e = o.length; e > n; n++)
                        o[n].changed = i._conditionChangeBind;
                    i._condition.evaluate()
                })
            },
            destroy: function() {
                for (var t = 0, e = this._tests.length; e > t; t++)
                    h.destroy(this._tests[t]);
                this._conditionChangeBind = null
            }
        };
        var f = {
                _uid: 0,
                _db: [],
                clearTest: function(t) {
                    var e = this._db[t];
                    return !!e && (this._db[t] = null, void e.destroy())
                },
                setTest: function(t, e, i) {
                    var n = this._uid++;
                    return this._db[n] = new p(t, e, i), n
                }
            },
            _ = function(t, e) {
                this._expression = t, this._negate = "undefined" != typeof e && e
            };
        _.prototype = {
            isTrue: function() {
                return this._expression.isTrue() !== this._negate
            },
            getTests: function() {
                return this._expression instanceof l ? [this._expression] : this._expression.getTests()
            },
            toString: function() {
                return (this._negate ? "not " : "") + this._expression.toString()
            }
        };
        var m = function(t, e, i) {
            this._a = t, this._operator = e, this._b = i
        };
        m.prototype = {
            isTrue: function() {
                return "and" === this._operator ? this._a.isTrue() && this._b.isTrue() : this._a.isTrue() || this._b.isTrue()
            },
            getTests: function() {
                return this._a.getTests().concat(this._b.getTests())
            },
            toString: function() {
                return "(" + this._a.toString() + " " + this._operator + " " + this._b.toString() + ")"
            }
        };
        var g = function(t, e) {
                return {
                    parse: function(i) {
                        var n,
                            o,
                            r,
                            s,
                            a,
                            h,
                            u,
                            c,
                            d,
                            p,
                            f,
                            _ = 0,
                            m = "",
                            g = [],
                            v = "",
                            y = !1,
                            w = !1,
                            b = null,
                            x = null,
                            k = [],
                            S = i.length;
                        for (b || (b = g); S > _; _++)
                            if (h = i.charCodeAt(_), 123 !== h)
                                if (125 === h && (n = b.length - 1, o = n + 1, y = "not" === b[n], o = y ? n : n + 1, s = new l(m, v), b[o] = new t(s, y), m = "", v = "", y = !1, w = !1), w)
                                    v += i.charAt(_);
                                else {
                                    if (40 === h && (b.push([]), k.push(b), b = b[b.length - 1]), 32 === h || 0 === _ || 40 === h) {
                                        if (r = i.substr(_, 5).match(/and |or |not /g), !r)
                                            continue;
                                        d = r[0], p = d.length - 1, b.push(d.substring(0, p)), _ += p
                                    }
                                    if (41 === h || _ === S - 1)
                                        do if (x = k.pop(), 0 !== b.length) {
                                            for (a = 0, f = b.length; f > a; a++)
                                                "string" == typeof b[a] && ("not" === b[a] ? (b.splice(a, 2, new t(b[a + 1], !0)), a = -1, f = b.length) : "not" !== b[a + 1] && (b.splice(a - 1, 3, new e(b[a - 1], b[a], b[a + 1])), a = -1, f = b.length));
                                            1 === b.length && x && (x[x.length - 1] = b[0], b = x)
                                        } else
                                            b = x;
                                        while (_ === S - 1 && x)
                                }
                            else
                                for (w = !0, m = "", u = _ - 2; u >= 0 && (c = i.charCodeAt(u), 32 !== c && 40 !== c);)
                                    m = i.charAt(u) + m, u--;
                        return 1 === g.length ? g[0] : g
                    }
                }
            }(_, m),
            v = {
                init: function(t) {
                    t()
                },
                allowsActivation: function() {
                    return !0
                },
                destroy: function() {}
            },
            y = function(t, e) {
                "string" == typeof t && t.length && (this._conditions = t, this._element = e, this._state = !1, this._test = null)
            };
        y.prototype = {
            init: function(t) {
                var e = this,
                    n = !1;
                this._test = f.setTest(this._conditions, this._element, function(o) {
                    e._state = o, i.publish(e, "change"), n || (n = !0, t())
                })
            },
            allowsActivation: function() {
                return this._state
            },
            destroy: function() {
                f.clearTest(this._test)
            }
        };
        var w = {
                _options: {},
                _redirects: {},
                _enabled: {},
                registerModule: function(t, e, i, n) {
                    this._options[a.loader.toUrl(t)] = e, this._enabled[t] = n, i && (this._redirects[i] = t), a.loader.config(t, e)
                },
                isModuleEnabled: function(t) {
                    return this._enabled[t] !== !1
                },
                getRedirect: function(t) {
                    return this._redirects[t] || t
                },
                getModule: function(t) {
                    return this._options[t] || this._options[a.loader.toUrl(t)]
                }
            },
            b = function(t, e, i, n) {
                this._path = w.getRedirect(t), this._alias = t, this._element = e, this._options = i, this._agent = n || v, this._Module = null, this._module = null, this._initialized = !1, this._onAgentStateChangeBind = this._onAgentStateChange.bind(this);
                var o = this;
                this._agent.init(function() {
                    o._initialize()
                })
            };
        b.prototype = {
            hasInitialized: function() {
                return this._initialized
            },
            getElement: function() {
                return this._element
            },
            getModulePath: function() {
                return this._path
            },
            isModuleAvailable: function() {
                return this._agent.allowsActivation() && !this._module
            },
            isModuleActive: function() {
                return null !== this._module
            },
            wrapsModuleWithPath: function(t) {
                return this._path === t || this._alias === t
            },
            _initialize: function() {
                this._initialized = !0, i.subscribe(this._agent, "change", this._onAgentStateChangeBind), i.publishAsync(this, "init", this), this._agent.allowsActivation() && this._onBecameAvailable()
            },
            _onBecameAvailable: function() {
                i.publishAsync(this, "available", this), this._load()
            },
            _onAgentStateChange: function() {
                var t = this._agent.allowsActivation();
                this._module && !t ? this._unload() : !this._module && t && this._onBecameAvailable()
            },
            _load: function() {
                if (this._Module)
                    return void this._onLoad();
                var t = this;
                a.loader.require([this._path], function(e) {
                    t._agent && (t._Module = e, t._onLoad())
                })
            },
            _applyOverrides: function(t, e) {
                if ("string" == typeof e) {
                    if (123 != e.charCodeAt(0)) {
                        for (var i = 0, n = e.split(", "), o = n.length; o > i; i++)
                            this._overrideObjectWithUri(t, n[i]);
                        return t
                    }
                    e = JSON.parse(e)
                }
                return s(t, e)
            },
            _overrideObjectWithUri: function(t, e) {
                for (var i, n = t, o = "", r = 0, s = e.length; s > r;) {
                    if (i = e.charCodeAt(r), 46 != i && 58 != i)
                        o += e.charAt(r);
                    else {
                        if (58 == i) {
                            n[o] = this._castValueToType(e.substr(r + 1));
                            break
                        }
                        n = n[o], o = ""
                    }
                    r++
                }
            },
            _castValueToType: function(t) {
                return 39 == t.charCodeAt(0) ? t.substring(1, t.length - 1) : isNaN(t) ? "true" == t || "false" == t ? "true" === t : -1 !== t.indexOf(",") ? t.split(",").map(this._castValueToType) : t : parseFloat(t)
            },
            _parseOptions: function(t, e, i) {
                var n,
                    o,
                    r = [],
                    a = {},
                    h = {};
                do n = w.getModule(t), r.push({
                    page: n,
                    module: e.options
                }), t = e.__superUrl;
                while (e = e.__super);
                for (o = r.length; o--;)
                    a = s(a, r[o].page), h = s(h, r[o].module);
                return n = s(h, a), i && (n = this._applyOverrides(n, i)), n
            },
            _onLoad: function() {
                if (this._agent.allowsActivation()) {
                    var t = this._parseOptions(this._path, this._Module, this._options);
                    "function" == typeof this._Module ? this._module = new this._Module(this._element, t) : (this._module = this._Module.load ? this._Module.load(this._element, t) : null, this._module || (this._module = this._Module)), i.inform(this._module, this), i.publishAsync(this, "load", this)
                }
            },
            _unload: function() {
                return !!this._module && (i.conceal(this._module, this), this._module.unload && this._module.unload(), this._module = null, i.publish(this, "unload", this), !0)
            },
            destroy: function() {
                i.unsubscribe(this._agent, "change", this._onAgentStateChangeBind), this._unload(), this._agent.destroy(), this._onAgentStateChangeBind = null
            },
            execute: function(t, e) {
                if (!this._module)
                    return {
                        status: 404,
                        response: null
                    };
                var i = this._module[t];
                return e = e || [], {
                    status: 200,
                    response: i.apply(this._module, e)
                }
            }
        };
        var x = function() {
                var t = function(t) {
                        return t.isModuleActive()
                    },
                    e = function(t) {
                        return t.isModuleAvailable()
                    },
                    n = function(t) {
                        return t.getModulePath()
                    },
                    s = function(t, e) {
                        this._element = t, this._element.setAttribute(a.attr.processed, "true"), this._priority = e ? parseInt(e, 10) : 0, this._moduleControllers = [], this._moduleAvailableBind = this._onModuleAvailable.bind(this), this._moduleLoadBind = this._onModuleLoad.bind(this), this._moduleUnloadBind = this._onModuleUnload.bind(this)
                    };
                return s.hasProcessed = function(t) {
                    return "true" === t.getAttribute(a.attr.processed)
                }, s.prototype = {
                    load: function(t) {
                        if (t && t.length) {
                            this._moduleControllers = t;
                            for (var e, n = 0, o = this._moduleControllers.length; o > n; n++)
                                e = this._moduleControllers[n], i.subscribe(e, "available", this._moduleAvailableBind), i.subscribe(e, "load", this._moduleLoadBind)
                        }
                    },
                    destroy: function() {
                        for (var t = 0, e = this._moduleControllers.length; e > t; t++)
                            this._destroyModule(this._moduleControllers[t]);
                        this._moduleAvailableBind = null, this._moduleLoadBind = null, this._moduleUnloadBind = null, this._updateAttribute(a.attr.initialized, this._moduleControllers), this._moduleControllers = null, this._element.removeAttribute(a.attr.processed)
                    },
                    _destroyModule: function(t) {
                        i.unsubscribe(t, "available", this._moduleAvailableBind), i.unsubscribe(t, "load", this._moduleLoadBind), i.unsubscribe(t, "unload", this._moduleUnloadBind), i.conceal(t, this), t.destroy()
                    },
                    getPriority: function() {
                        return this._priority
                    },
                    getElement: function() {
                        return this._element
                    },
                    matchesSelector: function(t, e) {
                        return !t && e ? o(e, this._element) : !(e && !o(e, this._element)) && r(this._element, t)
                    },
                    areAllModulesActive: function() {
                        return this.getActiveModules().length === this._moduleControllers.length
                    },
                    getActiveModules: function() {
                        return this._moduleControllers.filter(t)
                    },
                    getModule: function(t) {
                        return this._getModules(t, !0)
                    },
                    getModules: function(t) {
                        return this._getModules(t)
                    },
                    _getModules: function(t, e) {
                        if ("undefined" == typeof t)
                            return e ? this._moduleControllers[0] : this._moduleControllers.concat();
                        for (var i, n = 0, o = this._moduleControllers.length, r = []; o > n; n++)
                            if (i = this._moduleControllers[n], i.wrapsModuleWithPath(t)) {
                                if (e)
                                    return i;
                                r.push(i)
                            }
                        return e ? null : r
                    },
                    execute: function(t, e) {
                        return this._moduleControllers.map(function(i) {
                            return {
                                controller: i,
                                result: i.execute(t, e)
                            }
                        })
                    },
                    _onModuleAvailable: function(t) {
                        i.inform(t, this), this._updateAttribute(a.attr.loading, this._moduleControllers.filter(e))
                    },
                    _onModuleLoad: function(t) {
                        i.unsubscribe(t, "load", this._moduleLoadBind), i.subscribe(t, "unload", this._moduleUnloadBind), this._updateAttribute(a.attr.loading, this._moduleControllers.filter(e)), this._updateAttribute(a.attr.initialized, this.getActiveModules())
                    },
                    _onModuleUnload: function(t) {
                        i.subscribe(t, "load", this._moduleLoadBind), i.unsubscribe(t, "unload", this._moduleUnloadBind), this._updateAttribute(a.attr.initialized, this.getActiveModules());
                        var e = this;
                        setTimeout(function() {
                            i.conceal(t, e)
                        }, 0)
                    },
                    _updateAttribute: function(t, e) {
                        var i = e.map(n);
                        i.length ? this._element.setAttribute(t, i.join(",")) : this._element.removeAttribute(t)
                    }
                }, s
            }(),
            k = function(t) {
                this._inSync = !1, this._controllers = t, this._controllerLoadedBind = this._onLoad.bind(this), this._controllerUnloadedBind = this._onUnload.bind(this);
                for (var e, n = 0, o = this._controllers.length; o > n; n++)
                    e = this._controllers[n], i.subscribe(e, "load", this._controllerLoadedBind), i.subscribe(e, "unload", this._controllerUnloadedBind);
                this._test()
            };
        k.prototype = {
            destroy: function() {
                for (var t, e = 0, n = this._controllers.length; n > e; e++)
                    t = this._controllers[e], t && (i.unsubscribe(t, "load", this._controllerLoadedBind), i.unsubscribe(t, "unload", this._controllerUnloadedBind));
                this._controllerLoadedBind = null, this._controllerUnloadedBind = null, this._controllers = null
            },
            areAllModulesActive: function() {
                for (var t, e = 0, i = this._controllers.length; i > e; e++)
                    if (t = this._controllers[e], !this._isActiveController(t))
                        return !1;
                return !0
            },
            _onLoad: function() {
                this._test()
            },
            _onUnload: function() {
                this._unload()
            },
            _isActiveController: function(t) {
                return t.isModuleActive && t.isModuleActive() || t.areAllModulesActive && t.areAllModulesActive()
            },
            _test: function() {
                this.areAllModulesActive() && this._load()
            },
            _load: function() {
                this._inSync || (this._inSync = !0, i.publishAsync(this, "load", this._controllers))
            },
            _unload: function() {
                this._inSync && (this._inSync = !1, i.publish(this, "unload", this._controllers))
            }
        };
        var S = new RegExp("^\\[\\s*{"),
            C = function() {
                this._nodes = []
            };
        return C.prototype = {
            parse: function(t) {
                var e,
                    i,
                    n = t.querySelectorAll("[data-module]"),
                    o = n.length,
                    r = 0,
                    s = [];
                if (!n)
                    return [];
                for (; o > r; r++)
                    i = n[r], x.hasProcessed(i) || s.push(new x(i, i.getAttribute(a.attr.priority)));
                for (s.sort(function(t, e) {
                    return t.getPriority() - e.getPriority()
                }), r = s.length; --r >= 0;)
                    e = s[r], e.load.call(e, this._getModuleControllersByElement(e.getElement()));
                return this._nodes = this._nodes.concat(s), s
            },
            load: function(t, e) {
                e = e.length ? e : [e];
                var i,
                    n,
                    o = 0,
                    r = e.length,
                    s = [];
                for (n = new x(t); r > o; o++)
                    i = e[o], s.push(this._getModuleController(i.path, t, i.options, i.conditions));
                return n.load(s), this._nodes.push(n), n
            },
            getNodeByElement: function(t) {
                for (var e, i = 0, n = this._nodes.length; n > i; i++)
                    if (e = this._nodes[i], e.getElement() === t)
                        return e;
                return null
            },
            getNodes: function(t, e, i) {
                if ("undefined" == typeof t && "undefined" == typeof e)
                    return i ? this._nodes[0] : this._nodes.concat();
                for (var n, o = 0, r = this._nodes.length, s = []; r > o; o++)
                    if (n = this._nodes[o], n.matchesSelector(t, e)) {
                        if (i)
                            return n;
                        s.push(n)
                    }
                return i ? null : s
            },
            destroy: function(t) {
                for (var e, i = t.length, n = 0; i--;)
                    e = this._nodes.indexOf(t[i]), -1 !== e && (this._nodes.splice(e, 1), t[i].destroy(), n++);
                return t.length === n
            },
            _getModuleControllersByElement: function(t) {
                var e = t.getAttribute(a.attr.module) || "";
                if (91 == e.charCodeAt(0)) {
                    var i,
                        n,
                        o,
                        r = [],
                        s = 0;
                    try {
                        n = JSON.parse(e)
                    } catch (t) {}
                    if (!n)
                        return [];
                    if (i = n.length, S.test(e)) {
                        for (; i > s; s++)
                            o = n[s], w.isModuleEnabled(o.path) && (r[s] = this._getModuleController(o.path, t, o.options, o.conditions));
                        return r
                    }
                    for (; i > s; s++)
                        o = n[s], w.isModuleEnabled("string" == typeof o ? o : o[0]) && ("string" == typeof o ? r[s] = this._getModuleController(o, t) : r[s] = this._getModuleController(o[0], t, "string" == typeof o[1] ? o[2] : o[1], "string" == typeof o[1] ? o[1] : o[2]));
                    return r
                }
                return w.isModuleEnabled(e) ? [this._getModuleController(e, t, t.getAttribute(a.attr.options), t.getAttribute(a.attr.conditions))] : null
            },
            _getModuleController: function(t, e, i, n) {
                return new b(t, e, i, n ? new y(n, e) : v)
            }
        }, a = {
            paths: {
                monitors: "./monitors/"
            },
            attr: {
                options: "data-options",
                module: "data-module",
                conditions: "data-conditions",
                priority: "data-priority",
                initialized: "data-initialized",
                processed: "data-processed",
                loading: "data-loading"
            },
            loader: {
                require: function(t, i) {
                    e(t, i)
                },
                config: function(t, e) {
                    var i = {};
                    i[t] = e, requirejs.config({
                        config: i
                    })
                },
                toUrl: function(t) {
                    return requirejs.toUrl(t)
                }
            },
            modules: {}
        }, h = new d, u = new C, {
            init: function(e) {
                return e && this.setOptions(e), u.parse(t.document)
            },
            setOptions: function(t) {
                var e,
                    i,
                    n,
                    o,
                    r;
                a = s(a, t);
                for (i in a.paths)
                    a.paths.hasOwnProperty(i) && (a.paths[i] += "/" !== a.paths[i].slice(-1) ? "/" : "");
                for (i in a.modules)
                    a.modules.hasOwnProperty(i) && (n = a.modules[i], o = "string" == typeof n ? n : n.alias, e = "string" == typeof n ? null : n.options || {}, r = "string" == typeof n ? null : n.enabled, w.registerModule(i, e, o, r))
            },
            parse: function(t) {
                return u.parse(t)
            },
            load: function(t, e) {
                return u.load(t, e)
            },
            sync: function() {
                var t = Object.create(k.prototype);
                return k.apply(t, [arguments[0].slice ? arguments[0] : Array.prototype.slice.call(arguments, 0)]), t
            },
            getNode: function() {
                return "object" == typeof arguments[0] ? u.getNodeByElement(arguments[0]) : u.getNodes(arguments[0], arguments[1], !0)
            },
            getNodes: function(t, e) {
                return u.getNodes(t, e, !1)
            },
            destroy: function() {
                var t = [],
                    e = arguments[0];
                return Array.isArray(e) && (t = e), "string" == typeof e ? t = u.getNodes(e, arguments[1]) : e instanceof x ? t.push(e) : e.nodeName && (t = u.getNodes().filter(function(t) {
                    return o(e, t.getElement())
                })), 0 !== t.length && u.destroy(t)
            },
            getModule: function() {
                var t,
                    e,
                    i,
                    n,
                    o,
                    r,
                    s,
                    a;
                if ("object" == typeof arguments[0])
                    return s = u.getNodeByElement(arguments[0]), s ? s.getModule(arguments[1]) : null;
                for (e = arguments[0], "string" == typeof arguments[1] ? (i = arguments[1], n = arguments[2]) : n = arguments[1], t = 0, o = u.getNodes(i, n, !1), r = o.length; r > t; t++)
                    if (a = o[t].getModule(e))
                        return a;
                return null
            },
            getModules: function() {
                var t,
                    e,
                    i,
                    n,
                    o,
                    r,
                    s,
                    a,
                    h;
                if ("object" == typeof arguments[0])
                    return s = u.getNodeByElement(arguments[0]), s.getModules(arguments[1]);
                for (e = arguments[0], "string" == typeof arguments[1] ? (i = arguments[1], n = arguments[2]) : n = arguments[1], t = 0, o = this.getNodes(i, n), r = o.length, h = []; r > t; t++)
                    a = o[t].getModules(e), a.length && (h = h.concat(a));
                return h
            },
            is: function(t, e) {
                this.matchesCondition(t, e)
            },
            on: function(t, e, i) {
                return this.addConditionMonitor(t, e, i)
            },
            matchesCondition: function(t, e) {
                var i = new n,
                    o = f.setTest(t, e, function(t) {
                        i.resolve(t), f.clearTest(o)
                    });
                return i
            },
            addConditionMonitor: function(t, e, i) {
                return i = "function" == typeof e ? e : i, f.setTest(t, e, function(t) {
                    i(t)
                })
            },
            removeConditionMonitor: function(t) {
                f.clearTest(t)
            }
        }
    };
    "undefined" != typeof module && module.exports ? module.exports = i(require, require("./utils/Observer"), require("./utils/Promise"), require("./utils/contains"), require("./utils/matchesSelector"), require("./utils/mergeObjects")) : "function" == typeof define && define.amd && define("conditioner", ["require", "./utils/Observer", "./utils/Promise", "./utils/contains", "./utils/matchesSelector", "./utils/mergeObjects"], i)
}(this), require.config({
    map: {
        "*": {
            slim: "slim.amd"
        }
    }
}), require(["conditioner"], function(t) {
    t.init()
}), define("main", function() {}), function(t, e) {
    "use strict";
    var i = function(e) {
            var i = t.innerHeight,
                n = e.getBoundingClientRect();
            return n.top > 0 && n.top < i || n.bottom > 0 && n.bottom < i
        },
        n = function(t) {
            return parseInt(t, 10)
        },
        o = {
            trigger: {
                resize: t,
                scroll: t
            },
            test: {
                visible: function(t) {
                    return t.seen = i(t.element), t.seen && t.expected
                },
                "min-width": function(t) {
                    return n(t.expected) <= t.element.offsetWidth
                },
                "max-width": function(t) {
                    return n(t.expected) >= t.element.offsetWidth
                },
                "min-height": function(t) {
                    return n(t.expected) <= t.element.offsetHeight
                },
                "max-height": function(t) {
                    return n(t.expected) >= t.element.offsetHeight
                }
            }
        };
    "undefined" != typeof module && module.exports ? module.exports = o : "function" == typeof define && define.amd && define("monitors/element", [], function() {
        return o
    })
}(this), function(t, e) {
    "use strict";
    var i = {
        data: {
            mql: null
        },
        trigger: function(e, i) {
            "supported" !== i.expected && (i.change = function() {
                e()
            }, i.mql = t.matchMedia(i.expected), i.mql.addListener(i.change))
        },
        parse: function(t) {
            var e = [];
            return "supported" === t ? e.push({
                test: "supported",
                value: !0
            }) : e.push({
                test: "query",
                value: t
            }), e
        },
        test: {
            supported: function() {
                return "matchMedia" in t
            },
            query: function(t) {
                return t.mql.matches
            }
        },
        unload: function(t) {
            t.mql.removeListener(t.change)
        }
    };
    "undefined" != typeof module && module.exports ? module.exports = i : "function" == typeof define && define.amd && define("monitors/media", [], function() {
        return i
    })
}(this), function(t, e) {
    "use strict";
    var i = null;
    t.addEventListener("devicelight", function e() {
        i = !0, t.removeEventListener("devicelight", e)
    });
    var n = {
        trigger: function(e) {
            t.addEventListener("devicelight", function(t) {
                e(t)
            })
        },
        test: {
            supported: function() {
                return i
            },
            "max-level": function(t, e) {
                return !!e && e.value < parseInt(t.expected, 10)
            },
            "min-level": function(t, e) {
                return !!e && e.value >= parseInt(t.expected, 10)
            }
        }
    };
    "undefined" != typeof module && module.exports ? module.exports = n : "function" == typeof define && define.amd && define("monitors/light", [], function() {
        return n
    })
}(this), define("slim.amd", [], function() {
    function t(t, e) {
        if (!(t instanceof e))
            throw new TypeError("Cannot call a class as a function")
    }
    function e(t, e, i, n) {
        if (!(e >= 1)) {
            for (var o = t.width, r = t.height, s = Math.max(n.width, Math.min(i.width, Math.round(t.width * e))), a = Math.max(n.height, Math.min(i.height, Math.round(t.height * e))), h = st(t), u = void 0, l = void 0; o > s && r > a;)
                u = document.createElement("canvas"), o = Math.round(.5 * h.width), r = Math.round(.5 * h.height), o < s && (o = s), r < a && (r = a), u.width = o, u.height = r, l = u.getContext("2d"), l.drawImage(h, 0, 0, o, r), h = st(u);
            t.width = s, t.height = a, l = t.getContext("2d"), l.drawImage(h, 0, 0, s, a)
        }
    }
    !function() {
        function t(t, e) {
            e = e || {
                bubbles: !1,
                cancelable: !1,
                detail: void 0
            };
            var i = document.createEvent("CustomEvent");
            return i.initCustomEvent(t, e.bubbles, e.cancelable, e.detail), i
        }
        return "function" != typeof window.CustomEvent && (t.prototype = window.Event.prototype, void (window.CustomEvent = t))
    }();
    var i = function(t, e, n) {
            var o,
                r,
                s = document.createElement("img");
            if (s.onerror = e, s.onload = function() {
                !r || n && n.noRevoke || i.revokeObjectURL(r), e && e(i.scale(s, n))
            }, i.isInstanceOf("Blob", t) || i.isInstanceOf("File", t))
                o = r = i.createObjectURL(t), s._type = t.type;
            else {
                if ("string" != typeof t)
                    return !1;
                o = t, n && n.crossOrigin && (s.crossOrigin = n.crossOrigin)
            }
            return o ? (s.src = o, s) : i.readFile(t, function(t) {
                var i = t.target;
                i && i.result ? s.src = i.result : e && e(t)
            })
        },
        n = window.createObjectURL && window || window.URL && URL.revokeObjectURL && URL || window.webkitURL && webkitURL;
    i.isInstanceOf = function(t, e) {
        return Object.prototype.toString.call(e) === "[object " + t + "]"
    }, i.transformCoordinates = function() {}, i.getTransformedOptions = function(t, e) {
        var i,
            n,
            o,
            r,
            s = e.aspectRatio;
        if (!s)
            return e;
        i = {};
        for (n in e)
            e.hasOwnProperty(n) && (i[n] = e[n]);
        return i.crop = !0, o = t.naturalWidth || t.width, r = t.naturalHeight || t.height, o / r > s ? (i.maxWidth = r * s, i.maxHeight = r) : (i.maxWidth = o, i.maxHeight = o / s), i
    }, i.renderImageToCanvas = function(t, e, i, n, o, r, s, a, h, u) {
        return t.getContext("2d").drawImage(e, i, n, o, r, s, a, h, u), t
    }, i.hasCanvasOption = function(t) {
        return t.canvas || t.crop || !!t.aspectRatio
    }, i.scale = function(t, e) {
        function n() {
            var t = Math.max((a || w) / w, (h || b) / b);
            t > 1 && (w *= t, b *= t)
        }
        function o() {
            var t = Math.min((r || w) / w, (s || b) / b);
            t < 1 && (w *= t, b *= t)
        }
        e = e || {};
        var r,
            s,
            a,
            h,
            u,
            l,
            c,
            d,
            p,
            f,
            _,
            m = document.createElement("canvas"),
            g = t.getContext || i.hasCanvasOption(e) && m.getContext,
            v = t.naturalWidth || t.width,
            y = t.naturalHeight || t.height,
            w = v,
            b = y;
        if (g && (e = i.getTransformedOptions(t, e), c = e.left || 0, d = e.top || 0, e.sourceWidth ? (u = e.sourceWidth, void 0 !== e.right && void 0 === e.left && (c = v - u - e.right)) : u = v - c - (e.right || 0), e.sourceHeight ? (l = e.sourceHeight, void 0 !== e.bottom && void 0 === e.top && (d = y - l - e.bottom)) : l = y - d - (e.bottom || 0), w = u, b = l), r = e.maxWidth, s = e.maxHeight, a = e.minWidth, h = e.minHeight, g && r && s && e.crop ? (w = r, b = s, _ = u / l - r / s, _ < 0 ? (l = s * u / r, void 0 === e.top && void 0 === e.bottom && (d = (y - l) / 2)) : _ > 0 && (u = r * l / s, void 0 === e.left && void 0 === e.right && (c = (v - u) / 2))) : ((e.contain || e.cover) && (a = r = r || a, h = s = s || h), e.cover ? (o(), n()) : (n(), o())), g) {
            if (p = e.pixelRatio, p > 1 && (m.style.width = w + "px", m.style.height = b + "px", w *= p, b *= p, m.getContext("2d").scale(p, p)), f = e.downsamplingRatio, f > 0 && f < 1 && w < u && b < l)
                for (; u * f > w;)
                    m.width = u * f, m.height = l * f, i.renderImageToCanvas(m, t, c, d, u, l, 0, 0, m.width, m.height), u = m.width, l = m.height, t = document.createElement("canvas"), t.width = u, t.height = l, i.renderImageToCanvas(t, m, 0, 0, u, l, 0, 0, u, l);
            return m.width = w, m.height = b, i.transformCoordinates(m, e), i.renderImageToCanvas(m, t, c, d, u, l, 0, 0, w, b)
        }
        return t.width = w, t.height = b, t
    }, i.createObjectURL = function(t) {
        return !!n && n.createObjectURL(t)
    }, i.revokeObjectURL = function(t) {
        return !!n && n.revokeObjectURL(t)
    }, i.readFile = function(t, e, i) {
        if (window.FileReader) {
            var n = new FileReader;
            if (n.onload = n.onerror = e, i = i || "readAsDataURL", n[i])
                return n[i](t), n
        }
        return !1
    };
    var o = i.hasCanvasOption,
        r = i.transformCoordinates,
        s = i.getTransformedOptions;
    i.hasCanvasOption = function(t) {
        return !!t.orientation || o.call(i, t)
    }, i.transformCoordinates = function(t, e) {
        r.call(i, t, e);
        var n = t.getContext("2d"),
            o = t.width,
            s = t.height,
            a = t.style.width,
            h = t.style.height,
            u = e.orientation;
        if (u && !(u > 8))
            switch (u > 4 && (t.width = s, t.height = o, t.style.width = h, t.style.height = a), u) {
            case 2:
                n.translate(o, 0), n.scale(-1, 1);
                break;
            case 3:
                n.translate(o, s), n.rotate(Math.PI);
                break;
            case 4:
                n.translate(0, s), n.scale(1, -1);
                break;
            case 5:
                n.rotate(.5 * Math.PI), n.scale(1, -1);
                break;
            case 6:
                n.rotate(.5 * Math.PI), n.translate(0, -s);
                break;
            case 7:
                n.rotate(.5 * Math.PI), n.translate(o, -s), n.scale(-1, 1);
                break;
            case 8:
                n.rotate(-.5 * Math.PI), n.translate(-o, 0)
            }
    }, i.getTransformedOptions = function(t, e) {
        var n,
            o,
            r = s.call(i, t, e),
            a = r.orientation;
        if (!a || a > 8 || 1 === a)
            return r;
        n = {};
        for (o in r)
            r.hasOwnProperty(o) && (n[o] = r[o]);
        switch (r.orientation) {
        case 2:
            n.left = r.right, n.right = r.left;
            break;
        case 3:
            n.left = r.right, n.top = r.bottom, n.right = r.left, n.bottom = r.top;
            break;
        case 4:
            n.top = r.bottom, n.bottom = r.top;
            break;
        case 5:
            n.left = r.top, n.top = r.left, n.right = r.bottom, n.bottom = r.right;
            break;
        case 6:
            n.left = r.top, n.top = r.right, n.right = r.bottom, n.bottom = r.left;
            break;
        case 7:
            n.left = r.bottom, n.top = r.right, n.right = r.top, n.bottom = r.left;
            break;
        case 8:
            n.left = r.bottom, n.top = r.left, n.right = r.top, n.bottom = r.right
        }
        return r.orientation > 4 && (n.maxWidth = r.maxHeight, n.maxHeight = r.maxWidth, n.minWidth = r.minHeight, n.minHeight = r.minWidth, n.sourceWidth = r.sourceHeight, n.sourceHeight = r.sourceWidth), n
    };
    var a = window.Blob && (Blob.prototype.slice || Blob.prototype.webkitSlice || Blob.prototype.mozSlice);
    i.blobSlice = a && function() {
        var t = this.slice || this.webkitSlice || this.mozSlice;
        return t.apply(this, arguments)
    }, i.metaDataParsers = {
        jpeg: {
            65505: []
        }
    }, i.parseMetaData = function(t, e, n) {
        n = n || {};
        var o = this,
            r = n.maxMetaDataSize || 262144,
            s = {},
            a = !(window.DataView && t && t.size >= 12 && "image/jpeg" === t.type && i.blobSlice);
        !a && i.readFile(i.blobSlice.call(t, 0, r), function(t) {
            if (t.target.error)
                return void e(s);
            var r,
                a,
                h,
                u,
                l = t.target.result,
                c = new DataView(l),
                d = 2,
                p = c.byteLength - 4,
                f = d;
            if (65496 === c.getUint16(0)) {
                for (; d < p && (r = c.getUint16(d), r >= 65504 && r <= 65519 || 65534 === r) && (a = c.getUint16(d + 2) + 2, !(d + a > c.byteLength));) {
                    if (h = i.metaDataParsers.jpeg[r])
                        for (u = 0; u < h.length; u += 1)
                            h[u].call(o, c, d, a, s, n);
                    d += a, f = d
                }
                !n.disableImageHead && f > 6 && (l.slice ? s.imageHead = l.slice(0, f) : s.imageHead = new Uint8Array(l).subarray(0, f))
            }
            e(s)
        }, "readAsArrayBuffer") || e(s)
    }, i.ExifMap = function() {
        return this
    }, i.ExifMap.prototype.map = {
        Orientation: 274
    }, i.ExifMap.prototype.get = function(t) {
        return this[t] || this[this.map[t]]
    }, i.getExifThumbnail = function(t, e, i) {
        var n,
            o,
            r;
        if (i && !(e + i > t.byteLength)) {
            for (n = [], o = 0; o < i; o += 1)
                r = t.getUint8(e + o), n.push((r < 16 ? "0" : "") + r.toString(16));
            return "data:image/jpeg,%" + n.join("%")
        }
    }, i.exifTagTypes = {
        1: {
            getValue: function(t, e) {
                return t.getUint8(e)
            },
            size: 1
        },
        2: {
            getValue: function(t, e) {
                return String.fromCharCode(t.getUint8(e))
            },
            size: 1,
            ascii: !0
        },
        3: {
            getValue: function(t, e, i) {
                return t.getUint16(e, i)
            },
            size: 2
        },
        4: {
            getValue: function(t, e, i) {
                return t.getUint32(e, i)
            },
            size: 4
        },
        5: {
            getValue: function(t, e, i) {
                return t.getUint32(e, i) / t.getUint32(e + 4, i)
            },
            size: 8
        },
        9: {
            getValue: function(t, e, i) {
                return t.getInt32(e, i)
            },
            size: 4
        },
        10: {
            getValue: function(t, e, i) {
                return t.getInt32(e, i) / t.getInt32(e + 4, i)
            },
            size: 8
        }
    }, i.exifTagTypes[7] = i.exifTagTypes[1], i.getExifValue = function(t, e, n, o, r, s) {
        var a,
            h,
            u,
            l,
            c,
            d,
            p = i.exifTagTypes[o];
        if (p && (a = p.size * r, h = a > 4 ? e + t.getUint32(n + 8, s) : n + 8, !(h + a > t.byteLength))) {
            if (1 === r)
                return p.getValue(t, h, s);
            for (u = [], l = 0; l < r; l += 1)
                u[l] = p.getValue(t, h + l * p.size, s);
            if (p.ascii) {
                for (c = "", l = 0; l < u.length && (d = u[l], "\0" !== d); l += 1)
                    c += d;
                return c
            }
            return u
        }
    }, i.parseExifTag = function(t, e, n, o, r) {
        var s = t.getUint16(n, o);
        r.exif[s] = i.getExifValue(t, e, n, t.getUint16(n + 2, o), t.getUint32(n + 4, o), o)
    }, i.parseExifTags = function(t, e, i, n, o) {
        var r,
            s,
            a;
        if (!(i + 6 > t.byteLength || (r = t.getUint16(i, n), s = i + 2 + 12 * r, s + 4 > t.byteLength))) {
            for (a = 0; a < r; a += 1)
                this.parseExifTag(t, e, i + 2 + 12 * a, n, o);
            return t.getUint32(s, n)
        }
    }, i.parseExifData = function(t, e, n, o, r) {
        if (!r.disableExif) {
            var s,
                a,
                h,
                u = e + 10;
            if (1165519206 === t.getUint32(e + 4) && !(u + 8 > t.byteLength) && 0 === t.getUint16(e + 8)) {
                switch (t.getUint16(u)) {
                case 18761:
                    s = !0;
                    break;
                case 19789:
                    s = !1;
                    break;
                default:
                    return
                }
                42 === t.getUint16(u + 2, s) && (a = t.getUint32(u + 4, s), o.exif = new i.ExifMap, a = i.parseExifTags(t, u, u + a, s, o), a && !r.disableExifThumbnail && (h = {
                    exif: {}
                }, a = i.parseExifTags(t, u, u + a, s, h), h.exif[513] && (o.exif.Thumbnail = i.getExifThumbnail(t, u + h.exif[513], h.exif[514]))), o.exif[34665] && !r.disableExifSub && i.parseExifTags(t, u, u + o.exif[34665], s, o), o.exif[34853] && !r.disableExifGps && i.parseExifTags(t, u, u + o.exif[34853], s, o))
            }
        }
    }, i.metaDataParsers.jpeg[65505].push(i.parseExifData);
    var h = function() {
            var t = [],
                e = [],
                i = [],
                n = "transform",
                o = window.getComputedStyle(document.documentElement, ""),
                r = (Array.prototype.slice.call(o).join("").match(/-(moz|webkit|ms)-/) || "" === o.OLink && ["", "o"])[1];
            "webkit" === r && (n = "webkitTransform");
            var s = function(t, e, i) {
                    var n = t;
                    if (void 0 !== n.length) {
                        for (var o = {
                                chainers: [],
                                then: function(t) {
                                    return this.snabbt(t)
                                },
                                snabbt: function(t) {
                                    var e = this.chainers.length;
                                    return this.chainers.forEach(function(i, n) {
                                        i.snabbt(a(t, n, e))
                                    }), o
                                },
                                setValue: function(t) {
                                    return this.chainers.forEach(function(e) {
                                        e.setValue(t)
                                    }), o
                                },
                                finish: function() {
                                    return this.chainers.forEach(function(t) {
                                        t.finish()
                                    }), o
                                },
                                rollback: function() {
                                    return this.chainers.forEach(function(t) {
                                        t.rollback()
                                    }), o
                                }
                            }, r = 0, s = n.length; r < s; ++r)
                            "string" == typeof e ? o.chainers.push(h(n[r], e, a(i, r, s))) : o.chainers.push(h(n[r], a(e, r, s), i));
                        return o
                    }
                    return "string" == typeof e ? h(n, e, a(i, 0, 1)) : h(n, a(e, 0, 1), i)
                },
                a = function(t, e, i) {
                    if (!t)
                        return t;
                    var n = J(t);
                    Y(t.delay) && (n.delay = t.delay(e, i)), Y(t.callback) && (n.complete = function() {
                        t.callback.call(this, e, i)
                    });
                    var o = Y(t.allDone),
                        r = Y(t.complete);
                    (r || o) && (n.complete = function() {
                        r && t.complete.call(this, e, i), o && e == i - 1 && t.allDone()
                    }), Y(t.valueFeeder) && (n.valueFeeder = function(n, o) {
                        return t.valueFeeder(n, o, e, i)
                    }), Y(t.easing) && (n.easing = function(n) {
                        return t.easing(n, e, i)
                    });
                    var s = ["position", "rotation", "skew", "rotationPost", "scale", "width", "height", "opacity", "fromPosition", "fromRotation", "fromSkew", "fromRotationPost", "fromScale", "fromWidth", "fromHeight", "fromOpacity", "transformOrigin", "duration", "delay"];
                    return s.forEach(function(o) {
                        Y(t[o]) && (n[o] = t[o](e, i))
                    }), n
                },
                h = function(t, i, n) {
                    function o(i) {
                        if (f.tick(i), f.updateElement(t), !f.isStopped())
                            return f.completed() ? void (r.loop > 1 && !f.isStopped() ? (r.loop -= 1, f.restart(), b(o)) : (r.complete && r.complete.call(t), m.length && (r = m.pop(), h = v(r, c, !0), c = v(r, J(c)), r = y(h, c, r), f = k(r), e.push([t, f]), f.tick(i), b(o)))) : b(o)
                    }
                    if ("attention" === i)
                        return u(t, n);
                    if ("stop" === i)
                        return l(t);
                    if ("detach" === i)
                        return d(t);
                    var r = i;
                    _();
                    var s = g(t),
                        h = s;
                    h = v(r, h, !0);
                    var c = J(s);
                    c = v(r, c);
                    var p = y(h, c, r),
                        f = k(p);
                    e.push([t, f]), f.updateElement(t, !0);
                    var m = [],
                        w = {
                            snabbt: function(t) {
                                return m.unshift(a(t, 0, 1)), w
                            },
                            then: function(t) {
                                return this.snabbt(t)
                            }
                        };
                    return b(o), r.manual ? f : w
                },
                u = function(t, i) {
                    function n(e) {
                        r.tick(e), r.updateElement(t), r.completed() ? (i.callback && i.callback(t), i.loop && i.loop > 1 && (i.loop--, r.restart(), b(n))) : b(n)
                    }
                    var o = v(i, q({}));
                    i.movement = o;
                    var r = S(i);
                    e.push([t, r]), b(n)
                },
                l = function(t) {
                    for (var i = 0, n = e.length; i < n; ++i) {
                        var o = e[i],
                            r = o[0],
                            s = o[1];
                        r === t && s.stop()
                    }
                },
                c = function(t, e) {
                    for (var i = 0, n = t.length; i < n; ++i)
                        if (t[i][0] === e)
                            return i;
                    return -1
                },
                d = function(t) {
                    var n,
                        o,
                        r = [],
                        s = e.concat(i),
                        a = s.length;
                    for (o = 0; o < a; ++o)
                        n = s[o][0], (t.contains(n) || t === n) && r.push(n);
                    for (a = r.length, o = 0; o < a; ++o)
                        p(r[o])
                },
                p = function(t) {
                    l(t);
                    var n = c(e, t);
                    n >= 0 && e.splice(n, 1), n = c(i, t), n >= 0 && i.splice(n, 1)
                },
                f = function(t, e) {
                    for (var i = 0, n = t.length; i < n; ++i) {
                        var o = t[i],
                            r = o[0],
                            s = o[1];
                        if (r === e) {
                            var a = s.getCurrentState();
                            return s.stop(), a
                        }
                    }
                },
                _ = function() {
                    i = i.filter(function(t) {
                        return m(t[0]).body
                    })
                },
                m = function(t) {
                    for (var e = t; e.parentNode;)
                        e = e.parentNode;
                    return e
                },
                g = function(t) {
                    var n = f(e, t);
                    return n ? n : f(i, t)
                },
                v = function(t, e, i) {
                    e || (e = q({
                        position: [0, 0, 0],
                        rotation: [0, 0, 0],
                        rotationPost: [0, 0, 0],
                        scale: [1, 1],
                        skew: [0, 0]
                    }));
                    var n = "position",
                        o = "rotation",
                        r = "skew",
                        s = "rotationPost",
                        a = "scale",
                        h = "scalePost",
                        u = "width",
                        l = "height",
                        c = "opacity";
                    return i && (n = "fromPosition", o = "fromRotation", r = "fromSkew", s = "fromRotationPost", a = "fromScale", h = "fromScalePost", u = "fromWidth", l = "fromHeight", c = "fromOpacity"), e.position = V(t[n], e.position), e.rotation = V(t[o], e.rotation), e.rotationPost = V(t[s], e.rotationPost), e.skew = V(t[r], e.skew), e.scale = V(t[a], e.scale), e.scalePost = V(t[h], e.scalePost), e.opacity = t[c], e.width = t[u], e.height = t[l], e
                },
                y = function(t, e, i) {
                    return i.startState = t, i.endState = e, i
                },
                w = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.msRequestAnimationFrame || function(t) {
                    return setTimeout(t, 1e3 / 60)
                },
                b = function(e) {
                    0 === t.length && w(x), t.push(e)
                },
                x = function(n) {
                    for (var o = t.length, r = 0; r < o; ++r)
                        t[r](n);
                    t.splice(0, o);
                    var s = e.filter(function(t) {
                        return t[1].completed()
                    });
                    i = i.filter(function(t) {
                        for (var e = 0, i = s.length; e < i; ++e)
                            if (t[0] === s[e][0])
                                return !1;
                        return !0
                    }), i = i.concat(s), e = e.filter(function(t) {
                        return !t[1].completed()
                    }), 0 !== t.length && w(x)
                },
                k = function(t) {
                    var e = t.startState,
                        i = t.endState,
                        n = V(t.duration, 500),
                        o = V(t.delay, 0),
                        r = t.perspective,
                        s = L(V(t.easing, "linear"), t),
                        a = 0 === n ? i.clone() : e.clone();
                    t.transformOrigin;
                    a.transformOrigin = t.transformOrigin;
                    var h,
                        u,
                        l = 0,
                        c = 0,
                        d = !1,
                        p = !1,
                        f = t.manual,
                        _ = 0,
                        m = o / n;
                    return u = t.valueFeeder ? j(t.valueFeeder, e, i, a) : W(e, i, a), {
                        stop: function() {
                            d = !0
                        },
                        isStopped: function() {
                            return d
                        },
                        finish: function(t) {
                            f = !1;
                            var e = n * _;
                            l = c - e, h = t, s.resetFrom = _
                        },
                        rollback: function(t) {
                            f = !1, u.setReverse();
                            var e = n * (1 - _);
                            l = c - e, h = t, s.resetFrom = _
                        },
                        restart: function() {
                            l = void 0, s.resetFrom(0)
                        },
                        tick: function(t) {
                            if (!d) {
                                if (f)
                                    return c = t, void this.updateCurrentTransform();
                                if (l || (l = t), t - l > o) {
                                    p = !0, c = t - o;
                                    var e = Math.min(Math.max(0, c - l), n);
                                    s.tick(e / n), this.updateCurrentTransform(), this.completed() && h && h()
                                }
                            }
                        },
                        getCurrentState: function() {
                            return a
                        },
                        setValue: function(t) {
                            p = !0, _ = Math.min(Math.max(t, 1e-4), 1 + m)
                        },
                        updateCurrentTransform: function() {
                            var t = s.getValue();
                            if (f) {
                                var e = Math.max(1e-5, _ - m);
                                s.tick(e), t = s.getValue()
                            }
                            u.tween(t)
                        },
                        completed: function() {
                            return !!d || 0 !== l && s.completed()
                        },
                        updateElement: function(t, e) {
                            if (p || e) {
                                var i = u.asMatrix(),
                                    n = u.getProperties();
                                G(t, i, r), X(t, n)
                            }
                        }
                    }
                },
                S = function(t) {
                    var e = t.movement;
                    t.initialVelocity = .1, t.equilibriumPosition = 0;
                    var i = P(t),
                        n = !1,
                        o = e.position,
                        r = e.rotation,
                        s = e.rotationPost,
                        a = e.scale,
                        h = e.skew,
                        u = q({
                            position: o ? [0, 0, 0] : void 0,
                            rotation: r ? [0, 0, 0] : void 0,
                            rotationPost: s ? [0, 0, 0] : void 0,
                            scale: a ? [0, 0] : void 0,
                            skew: h ? [0, 0] : void 0
                        });
                    return {
                        stop: function() {
                            n = !0
                        },
                        isStopped: function(t) {
                            return n
                        },
                        tick: function(t) {
                            n || i.equilibrium || (i.tick(), this.updateMovement())
                        },
                        updateMovement: function() {
                            var t = i.getValue();
                            o && (u.position[0] = e.position[0] * t, u.position[1] = e.position[1] * t, u.position[2] = e.position[2] * t), r && (u.rotation[0] = e.rotation[0] * t, u.rotation[1] = e.rotation[1] * t, u.rotation[2] = e.rotation[2] * t), s && (u.rotationPost[0] = e.rotationPost[0] * t, u.rotationPost[1] = e.rotationPost[1] * t, u.rotationPost[2] = e.rotationPost[2] * t), a && (u.scale[0] = 1 + e.scale[0] * t, u.scale[1] = 1 + e.scale[1] * t), h && (u.skew[0] = e.skew[0] * t, u.skew[1] = e.skew[1] * t)
                        },
                        updateElement: function(t) {
                            G(t, u.asMatrix()), X(t, u.getProperties())
                        },
                        getCurrentState: function() {
                            return u
                        },
                        completed: function() {
                            return i.equilibrium || n
                        },
                        restart: function() {
                            i = P(t)
                        }
                    }
                },
                C = function(t) {
                    return t
                },
                E = function(t) {
                    return (Math.cos(t * Math.PI + Math.PI) + 1) / 2
                },
                M = function(t) {
                    return t * t
                },
                T = function(t) {
                    return -Math.pow(t - 1, 2) + 1
                },
                P = function(t) {
                    var e = V(t.startPosition, 0),
                        i = V(t.equilibriumPosition, 1),
                        n = V(t.initialVelocity, 0),
                        o = V(t.springConstant, .8),
                        r = V(t.springDeceleration, .9),
                        s = V(t.springMass, 10),
                        a = !1;
                    return {
                        tick: function(t) {
                            if (0 !== t && !a) {
                                var h = -(e - i) * o,
                                    u = h / s;
                                n += u, e += n, n *= r, Math.abs(e - i) < .001 && Math.abs(n) < .001 && (a = !0)
                            }
                        },
                        resetFrom: function(t) {
                            e = t, n = 0
                        },
                        getValue: function() {
                            return a ? i : e
                        },
                        completed: function() {
                            return a
                        }
                    }
                },
                A = {
                    linear: C,
                    ease: E,
                    easeIn: M,
                    easeOut: T
                },
                L = function(t, e) {
                    if ("spring" == t)
                        return P(e);
                    var i = t;
                    Y(t) || (i = A[t]);
                    var n,
                        o = i,
                        r = 0;
                    return {
                        tick: function(t) {
                            r = o(t), n = t
                        },
                        resetFrom: function(t) {
                            n = 0
                        },
                        getValue: function() {
                            return r
                        },
                        completed: function() {
                            return n >= 1 && n
                        }
                    }
                },
                O = function(t, e, i, n) {
                    t[0] = 1, t[1] = 0, t[2] = 0, t[3] = 0, t[4] = 0, t[5] = 1, t[6] = 0, t[7] = 0, t[8] = 0, t[9] = 0, t[10] = 1, t[11] = 0, t[12] = e, t[13] = i, t[14] = n, t[15] = 1
                },
                I = function(t, e) {
                    t[0] = 1, t[1] = 0, t[2] = 0, t[3] = 0, t[4] = 0, t[5] = Math.cos(e), t[6] = -Math.sin(e), t[7] = 0, t[8] = 0, t[9] = Math.sin(e), t[10] = Math.cos(e), t[11] = 0, t[12] = 0, t[13] = 0, t[14] = 0, t[15] = 1
                },
                R = function(t, e) {
                    t[0] = Math.cos(e), t[1] = 0, t[2] = Math.sin(e), t[3] = 0, t[4] = 0, t[5] = 1, t[6] = 0, t[7] = 0, t[8] = -Math.sin(e), t[9] = 0, t[10] = Math.cos(e), t[11] = 0, t[12] = 0, t[13] = 0, t[14] = 0, t[15] = 1
                },
                D = function(t, e) {
                    t[0] = Math.cos(e), t[1] = -Math.sin(e), t[2] = 0, t[3] = 0, t[4] = Math.sin(e), t[5] = Math.cos(e), t[6] = 0, t[7] = 0, t[8] = 0, t[9] = 0, t[10] = 1, t[11] = 0, t[12] = 0, t[13] = 0, t[14] = 0, t[15] = 1
                },
                z = function(t, e, i) {
                    t[0] = 1, t[1] = Math.tan(e), t[2] = 0, t[3] = 0, t[4] = Math.tan(i), t[5] = 1, t[6] = 0, t[7] = 0, t[8] = 0, t[9] = 0, t[10] = 1, t[11] = 0, t[12] = 0, t[13] = 0, t[14] = 0, t[15] = 1
                },
                B = function(t, e, i) {
                    t[0] = e, t[1] = 0, t[2] = 0, t[3] = 0, t[4] = 0, t[5] = i, t[6] = 0, t[7] = 0, t[8] = 0, t[9] = 0, t[10] = 1, t[11] = 0, t[12] = 0, t[13] = 0, t[14] = 0, t[15] = 1
                },
                U = function(t) {
                    t[0] = 1, t[1] = 0, t[2] = 0, t[3] = 0, t[4] = 0, t[5] = 1, t[6] = 0, t[7] = 0, t[8] = 0, t[9] = 0, t[10] = 1, t[11] = 0, t[12] = 0, t[13] = 0, t[14] = 0, t[15] = 1
                },
                N = function(t, e) {
                    e[0] = t[0], e[1] = t[1], e[2] = t[2], e[3] = t[3], e[4] = t[4], e[5] = t[5], e[6] = t[6], e[7] = t[7], e[8] = t[8], e[9] = t[9], e[10] = t[10], e[11] = t[11], e[12] = t[12], e[13] = t[13], e[14] = t[14], e[15] = t[15]
                },
                H = function() {
                    var t = new Float32Array(16),
                        e = new Float32Array(16),
                        i = new Float32Array(16);
                    return U(t), {
                        data: t,
                        asCSS: function() {
                            for (var e = "matrix3d(", i = 0; i < 15; ++i)
                                e += Math.abs(t[i]) < 1e-4 ? "0," : t[i].toFixed(10) + ",";
                            return e += Math.abs(t[15]) < 1e-4 ? "0)" : t[15].toFixed(10) + ")"
                        },
                        clear: function() {
                            U(t)
                        },
                        translate: function(n, o, r) {
                            return N(t, e), O(i, n, o, r), F(e, i, t), this
                        },
                        rotateX: function(n) {
                            return N(t, e), I(i, n), F(e, i, t), this
                        },
                        rotateY: function(n) {
                            return N(t, e), R(i, n), F(e, i, t), this
                        },
                        rotateZ: function(n) {
                            return N(t, e), D(i, n), F(e, i, t), this
                        },
                        scale: function(n, o) {
                            return N(t, e), B(i, n, o), F(e, i, t), this
                        },
                        skew: function(n, o) {
                            return N(t, e), z(i, n, o), F(e, i, t), this
                        }
                    }
                },
                F = function(t, e, i) {
                    return i[0] = t[0] * e[0] + t[1] * e[4] + t[2] * e[8] + t[3] * e[12], i[1] = t[0] * e[1] + t[1] * e[5] + t[2] * e[9] + t[3] * e[13], i[2] = t[0] * e[2] + t[1] * e[6] + t[2] * e[10] + t[3] * e[14], i[3] = t[0] * e[3] + t[1] * e[7] + t[2] * e[11] + t[3] * e[15], i[4] = t[4] * e[0] + t[5] * e[4] + t[6] * e[8] + t[7] * e[12], i[5] = t[4] * e[1] + t[5] * e[5] + t[6] * e[9] + t[7] * e[13], i[6] = t[4] * e[2] + t[5] * e[6] + t[6] * e[10] + t[7] * e[14], i[7] = t[4] * e[3] + t[5] * e[7] + t[6] * e[11] + t[7] * e[15], i[8] = t[8] * e[0] + t[9] * e[4] + t[10] * e[8] + t[11] * e[12], i[9] = t[8] * e[1] + t[9] * e[5] + t[10] * e[9] + t[11] * e[13], i[10] = t[8] * e[2] + t[9] * e[6] + t[10] * e[10] + t[11] * e[14], i[11] = t[8] * e[3] + t[9] * e[7] + t[10] * e[11] + t[11] * e[15], i[12] = t[12] * e[0] + t[13] * e[4] + t[14] * e[8] + t[15] * e[12], i[13] = t[12] * e[1] + t[13] * e[5] + t[14] * e[9] + t[15] * e[13], i[14] = t[12] * e[2] + t[13] * e[6] + t[14] * e[10] + t[15] * e[14], i[15] = t[12] * e[3] + t[13] * e[7] + t[14] * e[11] + t[15] * e[15], i
                },
                q = function(t) {
                    var e = H(),
                        i = {
                            opacity: void 0,
                            width: void 0,
                            height: void 0
                        };
                    return {
                        position: t.position,
                        rotation: t.rotation,
                        rotationPost: t.rotationPost,
                        skew: t.skew,
                        scale: t.scale,
                        scalePost: t.scalePost,
                        opacity: t.opacity,
                        width: t.width,
                        height: t.height,
                        clone: function() {
                            return q({
                                position: this.position ? this.position.slice(0) : void 0,
                                rotation: this.rotation ? this.rotation.slice(0) : void 0,
                                rotationPost: this.rotationPost ? this.rotationPost.slice(0) : void 0,
                                skew: this.skew ? this.skew.slice(0) : void 0,
                                scale: this.scale ? this.scale.slice(0) : void 0,
                                scalePost: this.scalePost ? this.scalePost.slice(0) : void 0,
                                height: this.height,
                                width: this.width,
                                opacity: this.opacity
                            })
                        },
                        asMatrix: function() {
                            var t = e;
                            return t.clear(), this.transformOrigin && t.translate(-this.transformOrigin[0], -this.transformOrigin[1], -this.transformOrigin[2]), this.scale && t.scale(this.scale[0], this.scale[1]), this.skew && t.skew(this.skew[0], this.skew[1]), this.rotation && (t.rotateX(this.rotation[0]), t.rotateY(this.rotation[1]), t.rotateZ(this.rotation[2])), this.position && t.translate(this.position[0], this.position[1], this.position[2]), this.rotationPost && (t.rotateX(this.rotationPost[0]), t.rotateY(this.rotationPost[1]), t.rotateZ(this.rotationPost[2])), this.scalePost && t.scale(this.scalePost[0], this.scalePost[1]), this.transformOrigin && t.translate(this.transformOrigin[0], this.transformOrigin[1], this.transformOrigin[2]), t
                        },
                        getProperties: function() {
                            return i.opacity = this.opacity, i.width = this.width + "px", i.height = this.height + "px", i
                        }
                    }
                },
                W = function(t, e, i) {
                    var n = t,
                        o = e,
                        r = i,
                        s = void 0 !== o.position,
                        a = void 0 !== o.rotation,
                        h = void 0 !== o.rotationPost,
                        u = void 0 !== o.scale,
                        l = void 0 !== o.skew,
                        c = void 0 !== o.width,
                        d = void 0 !== o.height,
                        p = void 0 !== o.opacity;
                    return {
                        tween: function(t) {
                            if (s) {
                                var e = o.position[0] - n.position[0],
                                    i = o.position[1] - n.position[1],
                                    f = o.position[2] - n.position[2];
                                r.position[0] = n.position[0] + t * e, r.position[1] = n.position[1] + t * i, r.position[2] = n.position[2] + t * f
                            }
                            if (a) {
                                var _ = o.rotation[0] - n.rotation[0],
                                    m = o.rotation[1] - n.rotation[1],
                                    g = o.rotation[2] - n.rotation[2];
                                r.rotation[0] = n.rotation[0] + t * _, r.rotation[1] = n.rotation[1] + t * m, r.rotation[2] = n.rotation[2] + t * g
                            }
                            if (h) {
                                var v = o.rotationPost[0] - n.rotationPost[0],
                                    y = o.rotationPost[1] - n.rotationPost[1],
                                    w = o.rotationPost[2] - n.rotationPost[2];
                                r.rotationPost[0] = n.rotationPost[0] + t * v, r.rotationPost[1] = n.rotationPost[1] + t * y, r.rotationPost[2] = n.rotationPost[2] + t * w
                            }
                            if (l) {
                                var b = o.scale[0] - n.scale[0],
                                    x = o.scale[1] - n.scale[1];
                                r.scale[0] = n.scale[0] + t * b, r.scale[1] = n.scale[1] + t * x
                            }
                            if (u) {
                                var k = o.skew[0] - n.skew[0],
                                    S = o.skew[1] - n.skew[1];
                                r.skew[0] = n.skew[0] + t * k, r.skew[1] = n.skew[1] + t * S
                            }
                            if (c) {
                                var C = o.width - n.width;
                                r.width = n.width + t * C
                            }
                            if (d) {
                                var E = o.height - n.height;
                                r.height = n.height + t * E
                            }
                            if (p) {
                                var M = o.opacity - n.opacity;
                                r.opacity = n.opacity + t * M
                            }
                        },
                        asMatrix: function() {
                            return r.asMatrix()
                        },
                        getProperties: function() {
                            return r.getProperties()
                        },
                        setReverse: function() {
                            var t = n;
                            n = o, o = t
                        }
                    }
                },
                j = function(t, e, i, n) {
                    var o = t(0, H()),
                        r = e,
                        s = i,
                        a = n,
                        h = !1;
                    return {
                        tween: function(e) {
                            h && (e = 1 - e), o.clear(), o = t(e, o);
                            var i = s.width - r.width,
                                n = s.height - r.height,
                                u = s.opacity - r.opacity;
                            void 0 !== s.width && (a.width = r.width + e * i), void 0 !== s.height && (a.height = r.height + e * n), void 0 !== s.opacity && (a.opacity = r.opacity + e * u)
                        },
                        asMatrix: function() {
                            return o
                        },
                        getProperties: function() {
                            return a.getProperties()
                        },
                        setReverse: function() {
                            h = !0
                        }
                    }
                },
                V = function(t, e) {
                    return "undefined" == typeof t ? e : t
                },
                G = function(t, e, i) {
                    var o = "";
                    i && (o = "perspective(" + i + "px) ");
                    var r = e.asCSS();
                    t.style[n] = o + r
                },
                X = function(t, e) {
                    for (var i in e)
                        t.style[i] = e[i]
                },
                Y = function(t) {
                    return "function" == typeof t
                },
                J = function(t) {
                    if (!t)
                        return t;
                    var e = {};
                    for (var i in t)
                        e[i] = t[i];
                    return e
                };
            return s.createMatrix = H, s.setElementTransform = G, s
        }(),
        u = function() {
            function t(t, e, i, n, o) {
                if ("string" == typeof t)
                    t = document.getElementById(t);
                else if (!t instanceof HTMLCanvasElement)
                    return;
                var r,
                    s = t.getContext("2d");
                try {
                    try {
                        r = s.getImageData(e, i, n, o)
                    } catch (t) {
                        throw new Error("unable to access local image data: " + t)
                    }
                } catch (t) {
                    throw new Error("unable to access image data: " + t)
                }
                return r
            }
            function e(e, n, o, r, s, a) {
                if (!(isNaN(a) || a < 1)) {
                    a |= 0;
                    var h = t(e, n, o, r, s);
                    h = i(h, n, o, r, s, a), e.getContext("2d").putImageData(h, n, o)
                }
            }
            function i(t, e, i, s, a, h) {
                var u,
                    l,
                    c,
                    d,
                    p,
                    f,
                    _,
                    m,
                    g,
                    v,
                    y,
                    w,
                    b,
                    x,
                    k,
                    S,
                    C,
                    E,
                    M,
                    T,
                    P,
                    A,
                    L,
                    O,
                    I = t.data,
                    R = h + h + 1,
                    D = s - 1,
                    z = a - 1,
                    B = h + 1,
                    U = B * (B + 1) / 2,
                    N = new n,
                    H = N;
                for (c = 1; c < R; c++)
                    if (H = H.next = new n, c == B)
                        var F = H;
                H.next = N;
                var q = null,
                    W = null;
                _ = f = 0;
                var j = o[h],
                    V = r[h];
                for (l = 0; l < a; l++) {
                    for (S = C = E = M = m = g = v = y = 0, w = B * (T = I[f]), b = B * (P = I[f + 1]), x = B * (A = I[f + 2]), k = B * (L = I[f + 3]), m += U * T, g += U * P, v += U * A, y += U * L, H = N, c = 0; c < B; c++)
                        H.r = T, H.g = P, H.b = A, H.a = L, H = H.next;
                    for (c = 1; c < B; c++)
                        d = f + ((D < c ? D : c) << 2), m += (H.r = T = I[d]) * (O = B - c), g += (H.g = P = I[d + 1]) * O, v += (H.b = A = I[d + 2]) * O, y += (H.a = L = I[d + 3]) * O, S += T, C += P, E += A, M += L, H = H.next;
                    for (q = N, W = F, u = 0; u < s; u++)
                        I[f + 3] = L = y * j >> V, 0 != L ? (L = 255 / L, I[f] = (m * j >> V) * L, I[f + 1] = (g * j >> V) * L, I[f + 2] = (v * j >> V) * L) : I[f] = I[f + 1] = I[f + 2] = 0, m -= w, g -= b, v -= x, y -= k, w -= q.r, b -= q.g, x -= q.b, k -= q.a, d = _ + ((d = u + h + 1) < D ? d : D) << 2, S += q.r = I[d], C += q.g = I[d + 1], E += q.b = I[d + 2], M += q.a = I[d + 3], m += S, g += C, v += E, y += M, q = q.next, w += T = W.r, b += P = W.g, x += A = W.b, k += L = W.a, S -= T, C -= P, E -= A, M -= L, W = W.next, f += 4;
                    _ += s
                }
                for (u = 0; u < s; u++) {
                    for (C = E = M = S = g = v = y = m = 0, f = u << 2, w = B * (T = I[f]), b = B * (P = I[f + 1]), x = B * (A = I[f + 2]), k = B * (L = I[f + 3]), m += U * T, g += U * P, v += U * A, y += U * L, H = N, c = 0; c < B; c++)
                        H.r = T, H.g = P, H.b = A, H.a = L, H = H.next;
                    for (p = s, c = 1; c <= h; c++)
                        f = p + u << 2, m += (H.r = T = I[f]) * (O = B - c), g += (H.g = P = I[f + 1]) * O, v += (H.b = A = I[f + 2]) * O, y += (H.a = L = I[f + 3]) * O, S += T, C += P, E += A, M += L, H = H.next, c < z && (p += s);
                    for (f = u, q = N, W = F, l = 0; l < a; l++)
                        d = f << 2, I[d + 3] = L = y * j >> V, L > 0 ? (L = 255 / L, I[d] = (m * j >> V) * L, I[d + 1] = (g * j >> V) * L, I[d + 2] = (v * j >> V) * L) : I[d] = I[d + 1] = I[d + 2] = 0, m -= w, g -= b, v -= x, y -= k, w -= q.r, b -= q.g, x -= q.b, k -= q.a, d = u + ((d = l + B) < z ? d : z) * s << 2, m += S += q.r = I[d], g += C += q.g = I[d + 1], v += E += q.b = I[d + 2], y += M += q.a = I[d + 3], q = q.next, w += T = W.r, b += P = W.g, x += A = W.b, k += L = W.a, S -= T, C -= P, E -= A, M -= L, W = W.next, f += s
                }
                return t
            }
            function n() {
                this.r = 0, this.g = 0, this.b = 0, this.a = 0, this.next = null
            }
            var o = [512, 512, 456, 512, 328, 456, 335, 512, 405, 328, 271, 456, 388, 335, 292, 512, 454, 405, 364, 328, 298, 271, 496, 456, 420, 388, 360, 335, 312, 292, 273, 512, 482, 454, 428, 405, 383, 364, 345, 328, 312, 298, 284, 271, 259, 496, 475, 456, 437, 420, 404, 388, 374, 360, 347, 335, 323, 312, 302, 292, 282, 273, 265, 512, 497, 482, 468, 454, 441, 428, 417, 405, 394, 383, 373, 364, 354, 345, 337, 328, 320, 312, 305, 298, 291, 284, 278, 271, 265, 259, 507, 496, 485, 475, 465, 456, 446, 437, 428, 420, 412, 404, 396, 388, 381, 374, 367, 360, 354, 347, 341, 335, 329, 323, 318, 312, 307, 302, 297, 292, 287, 282, 278, 273, 269, 265, 261, 512, 505, 497, 489, 482, 475, 468, 461, 454, 447, 441, 435, 428, 422, 417, 411, 405, 399, 394, 389, 383, 378, 373, 368, 364, 359, 354, 350, 345, 341, 337, 332, 328, 324, 320, 316, 312, 309, 305, 301, 298, 294, 291, 287, 284, 281, 278, 274, 271, 268, 265, 262, 259, 257, 507, 501, 496, 491, 485, 480, 475, 470, 465, 460, 456, 451, 446, 442, 437, 433, 428, 424, 420, 416, 412, 408, 404, 400, 396, 392, 388, 385, 381, 377, 374, 370, 367, 363, 360, 357, 354, 350, 347, 344, 341, 338, 335, 332, 329, 326, 323, 320, 318, 315, 312, 310, 307, 304, 302, 299, 297, 294, 292, 289, 287, 285, 282, 280, 278, 275, 273, 271, 269, 267, 265, 263, 261, 259],
                r = [9, 11, 12, 13, 13, 14, 14, 15, 15, 15, 15, 16, 16, 16, 16, 17, 17, 17, 17, 17, 17, 17, 18, 18, 18, 18, 18, 18, 18, 18, 18, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24];
            return e
        }();
    HTMLCanvasElement.prototype.toBlob || Object.defineProperty(HTMLCanvasElement.prototype, "toBlob", {
        value: function(t, e, i) {
            for (var n = atob(this.toDataURL(e, i).split(",")[1]), o = n.length, r = new Uint8Array(o), s = 0; s < o; s++)
                r[s] = n.charCodeAt(s);
            t(new Blob([r], {
                type: e || "image/png"
            }))
        }
    });
    var l = function() {
            function t(t, e) {
                for (var i = 0; i < e.length; i++) {
                    var n = e[i];
                    n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(t, n.key, n)
                }
            }
            return function(e, i, n) {
                return i && t(e.prototype, i), n && t(e, n), e
            }
        }(),
        c = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
            return typeof t
        } : function(t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        },
        d = function(t) {
            if ("undefined" == typeof t.dataset) {
                var e,
                    i,
                    n = {},
                    o = t.attributes;
                for (e in o)
                    o.hasOwnProperty(e) && o[e].name && /^data-[a-z_\-\d]*$/i.test(o[e].name) && (i = p(o[e].name.substr(5)), n[i] = o[e].value);
                return n
            }
            return t.dataset
        },
        p = function(t) {
            return t.replace(/\-./g, function(t) {
                return t.charAt(1).toUpperCase()
            })
        },
        f = function(t) {
            for (var e = [], i = Array.prototype.slice.call(t.attributes), n = i.length, o = 0; o < n; o++)
                e.push({
                    name: i[o].name,
                    value: i[o].value
                });
            return e
        },
        _ = function(t) {
            return {
                x: "undefined" == typeof t.offsetX ? t.layerX : t.offsetX,
                y: "undefined" == typeof t.offsetY ? t.layerY : t.offsetY
            }
        },
        m = function(t, e) {
            var i,
                n = {},
                o = e || {};
            for (i in t)
                t.hasOwnProperty(i) && (n[i] = "undefined" == typeof o[i] ? t[i] : o[i]);
            return n
        },
        g = {
            ESC: 27,
            RETURN: 13
        },
        v = {
            DOWN: ["touchstart", "pointerdown", "mousedown"],
            MOVE: ["touchmove", "pointermove", "mousemove"],
            UP: ["touchend", "touchcancel", "pointerup", "mouseup"]
        },
        y = {
            jpeg: "image/jpeg",
            jpg: "image/jpeg",
            jpe: "image/jpeg",
            png: "image/png",
            gif: "image/gif",
            bmp: "image/bmp"
        },
        w = /(\.png|\.bmp|\.gif|\.jpg|\.jpe|\.jpg|\.jpeg)$/,
        b = function(t, e) {
            var i = document.createElement(t);
            return e && (i.className = e), i
        },
        x = function(t, e, i) {
            e.forEach(function(e) {
                t.addEventListener(e, i, !1)
            })
        },
        k = function(t, e, i) {
            e.forEach(function(e) {
                t.removeEventListener(e, i, !1)
            })
        },
        S = function(t) {
            var e = t.changedTouches ? t.changedTouches[0] : t;
            if (e)
                return {
                    x: e.pageX,
                    y: e.pageY
                }
        },
        C = function(t, e) {
            var i = .5,
                n = .5,
                o = Math.PI / 180 * e,
                r = Math.cos(o),
                s = Math.sin(o),
                a = t.x,
                h = t.y,
                u = t.x + t.width,
                l = t.y + t.height,
                c = r * (a - i) + s * (h - n) + i,
                d = r * (h - n) - s * (a - i) + n,
                p = r * (u - i) + s * (l - n) + i,
                f = r * (l - n) - s * (u - i) + n;
            c <= p ? (t.x = c, t.width = p - c) : (t.x = p, t.width = c - p), d <= f ? (t.y = d, t.height = f - d) : (t.y = f, t.height = d - f)
        },
        E = function(t) {
            var e = S(t);
            return e.x -= window.pageXOffset || document.documentElement.scrollLeft, e.y -= window.pageYOffset || document.documentElement.scrollTop, e
        },
        M = function(t) {
            return t.charAt(0).toLowerCase() + t.slice(1)
        },
        T = function(t) {
            return t.charAt(0).toUpperCase() + t.slice(1)
        },
        P = function(t) {
            return t[t.length - 1]
        },
        A = function(t, e, i) {
            return Math.max(e, Math.min(i, t))
        },
        L = function(t, e) {
            if (!e)
                return !1;
            for (var i = 0; i < e.length; i++)
                if (e[i] === t)
                    return !0;
            return !1
        },
        O = function(t, e, i, n, o, r) {
            var s = new XMLHttpRequest;
            n && s.upload.addEventListener("progress", function(t) {
                n(t.loaded, t.total)
            }), s.open("POST", t, !0), i && i(s, e), s.onreadystatechange = function() {
                if (4 === s.readyState && s.status >= 200 && s.status < 300) {
                    var t = s.responseText;
                    if (!t.length)
                        return void o();
                    if (t.indexOf("Content-Length") !== -1)
                        return void r("file-too-big");
                    var e = void 0;
                    try {
                        e = JSON.parse(s.responseText)
                    } catch (t) {}
                    if ("object" === ("undefined" == typeof e ? "undefined" : c(e)) && "failure" === e.status)
                        return void r(e.message);
                    o(e || t)
                } else if (4 === s.readyState) {
                    var i = void 0;
                    try {
                        i = JSON.parse(s.responseText)
                    } catch (t) {}
                    if ("object" === ("undefined" == typeof i ? "undefined" : c(i)) && "failure" === i.status)
                        return void r(i.message);
                    r("fail")
                }
            }, s.send(e)
        },
        I = function(t) {
            t && (t.style.webkitTransform = "", t.style.transform = "")
        },
        R = function(t) {
            return t / 1e6
        },
        D = function() {
            var t = [],
                e = void 0,
                i = void 0;
            for (e in y)
                y.hasOwnProperty(e) && (i = y[e], t.indexOf(i) == -1 && t.push(i));
            return t
        },
        z = function(t) {
            return "image/jpeg" === t
        },
        B = function(t) {
            var e = void 0;
            for (e in y)
                if (y.hasOwnProperty(e) && y[e] === t)
                    return e;
            return t
        },
        U = function(t) {
            var e = void 0;
            for (e in y)
                if (y.hasOwnProperty(e) && t.indexOf(y[e]) !== -1)
                    return y[e];
            return null
        },
        N = function(t) {
            return t.split("/").pop().split("?").shift()
        },
        H = function(t) {
            var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "";
            return (e + t).slice(-e.length)
        },
        F = function(t) {
            return t.getFullYear() + "-" + H(t.getMonth() + 1, "00") + "-" + H(t.getDate(), "00") + "_" + H(t.getHours(), "00") + "-" + H(t.getMinutes(), "00") + "-" + H(t.getSeconds(), "00")
        },
        q = function(t) {
            return "undefined" == typeof t.name ? F(new Date) + "." + B(W(t)) : t.name
        },
        W = function(t) {
            return t.type || "image/jpeg"
        },
        j = function(t) {
            if ("string" != typeof t)
                return F(new Date);
            var e = N(t);
            return e.split(".").shift()
        },
        V = function(t, e) {
            return "lastModified" in File.prototype ? t.lastModified = new Date : t.lastModifiedDate = new Date, t.name = e, t
        },
        G = function(t) {
            return /^data:image/.test(t)
        },
        X = function(t, e, i, n, o, r) {
            t = "" + t + (t.indexOf("?") !== -1 ? "&" : "?") + "url=" + n;
            var s = new XMLHttpRequest;
            s.open("GET", t, !0), e(s), s.responseType = "json", s.onload = function() {
                return "failure" === this.response.status ? void o(this.response.message) : void Y(this.response.body, i, r)
            }, s.send()
        },
        Y = function(t, e, i) {
            var n = new XMLHttpRequest;
            n.open("GET", t, !0), e(n), n.responseType = "blob", n.onload = function(e) {
                var n = N(t),
                    o = U(this.response.type);
                w.test(n) || (n += "." + B(o));
                var r = V(this.response, n);
                i(mt(r, o))
            }, n.send()
        },
        J = function(t) {
            var e = t.split(",")[1],
                i = e.replace(/\s/g, "");
            return atob(i)
        },
        $ = function(t, e) {
            for (var i = J(t), n = new ArrayBuffer(i.length), o = new Uint8Array(n), r = 0; r < i.length; r++)
                o[r] = i.charCodeAt(r);
            var s = yt(t);
            return "undefined" == typeof e && (e = F(new Date) + "." + B(s)), V(Z(n, s), e)
        },
        Z = function(t, e) {
            var i = window.BlobBuilder = window.BlobBuilder || window.WebKitBlobBuilder || window.MozBlobBuilder || window.MSBlobBuilder;
            if (i) {
                var n = new i;
                return n.append(t), n.getBlob(e)
            }
            return new Blob([t], {
                type: e
            })
        },
        K = function(t, e, n) {
            var o = "string" != typeof t || 0 !== t.indexOf("data:image");
            i.parseMetaData(t, function(r) {
                var s = {
                    canvas: !0,
                    crossOrigin: o
                };
                e && (s.maxWidth = e.width, s.maxHeight = e.height), r.exif && (s.orientation = r.exif.get("Orientation")), i(t, function(t) {
                    return "error" === t.type ? void n() : void n(t, r)
                }, s)
            })
        },
        Q = function(t, e, i) {
            var n,
                o,
                r,
                s,
                a = e / t;
            return a < i ? (s = e, r = s / i, n = .5 * (t - r), o = 0) : (r = t, s = r * i, n = 0, o = .5 * (e - s)), {
                x: n,
                y: o,
                height: s,
                width: r
            }
        },
        tt = function(t) {
            var i = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
                n = arguments[2],
                o = b("canvas"),
                r = i.rotation,
                s = i.crop,
                a = i.size,
                h = i.filters,
                u = i.minSize;
            if (s) {
                var l = r % 180 !== 0,
                    c = {
                        width: l ? t.height : t.width,
                        height: l ? t.width : t.height
                    };
                s.x < 0 && (s.x = 0), s.y < 0 && (s.y = 0), s.width > c.width && (s.width = c.width), s.height > c.height && (s.height = c.height), s.y + s.height > c.height && (s.y = Math.max(0, c.height - s.height)), s.x + s.width > c.width && (s.x = Math.max(0, c.width - s.width));
                var d = s.x / c.width,
                    p = s.y / c.height,
                    f = s.width / c.width,
                    _ = s.height / c.height;
                o.width = s.width, o.height = s.height;
                var m = o.getContext("2d");
                90 === r ? (m.translate(.5 * o.width, .5 * o.height), m.rotate(-90 * Math.PI / 180), m.drawImage(t, (1 - p) * t.width - t.width * _, s.x, s.height, s.width, .5 * -o.height, .5 * -o.width, o.height, o.width)) : 180 === r ? (m.translate(.5 * o.width, .5 * o.height), m.rotate(-180 * Math.PI / 180), m.drawImage(t, (1 - (d + f)) * c.width, (1 - (p + _)) * c.height, f * c.width, _ * c.height, .5 * -o.width, .5 * -o.height, o.width, o.height)) : 270 === r ? (m.translate(.5 * o.width, .5 * o.height), m.rotate(-270 * Math.PI / 180), m.drawImage(t, s.y, (1 - d) * t.height - t.height * f, s.height, s.width, .5 * -o.height, .5 * -o.width, o.height, o.width)) : m.drawImage(t, s.x, s.y, s.width, s.height, 0, 0, o.width, o.height)
            }
            if (a) {
                var g = a.width / o.width,
                    v = a.height / o.height,
                    y = Math.min(g, v);
                e(o, y, a, u), h.sharpen > 0 && it(o, ot(h.sharpen))
            }
            n(o)
        },
        et = function(t) {
            var e = t.getContext("2d");
            return e.getImageData(0, 0, t.width, t.height)
        },
        it = function(t, e) {
            var i = t.getContext("2d");
            i.putImageData(e(et(t), t.width, t.height), 0, 0)
        },
        nt = function(t, e, i) {
            var n = document.createElement("canvas");
            n.width = t, n.height = e;
            var o = n.getContext("2d"),
                r = o.createImageData(n.width, n.height);
            return i && r.set(i.data), r
        },
        ot = function(t) {
            return function(e, i, n) {
                for (var o = [0, -1, 0, -1, 5, -1, 0, -1, 0], r = Math.round(Math.sqrt(o.length)), s = .5 * r | 0, a = nt(i, n), h = a.data, u = e.data, l = n, c = void 0; l--;)
                    for (c = i; c--;) {
                        for (var d = l, p = c, f = 4 * (l * i + c), _ = 0, m = 0, g = 0, v = 0, y = 0; y < r; y++)
                            for (var w = 0; w < r; w++) {
                                var b = d + y - s,
                                    x = p + w - s;
                                if (b >= 0 && b < n && x >= 0 && x < i) {
                                    var k = 4 * (b * i + x),
                                        S = o[y * r + w];
                                    _ += u[k] * S, m += u[k + 1] * S, g += u[k + 2] * S, v += u[k + 3] * S
                                }
                            }
                        h[f] = _ * t + u[f] * (1 - t), h[f + 1] = m * t + u[f + 1] * (1 - t), h[f + 2] = g * t + u[f + 2] * (1 - t), h[f + 3] = u[f + 3]
                    }
                return a
            }
        },
        rt = function(t, e) {
            var i = Math.abs(t.width - e.width),
                n = Math.abs(t.height - e.height);
            return Math.max(i, n)
        },
        st = function(t) {
            return at(t, 1)
        },
        at = function(t, i) {
            if (!t)
                return null;
            var n = document.createElement("canvas"),
                o = n.getContext("2d");
            return n.width = t.width, n.height = t.height, o.drawImage(t, 0, 0), i > 0 && 1 !== i && e(n, i, {
                width: Math.round(t.width * i),
                height: Math.round(t.height * i)
            }, {
                width: 0,
                height: 0
            }), n
        },
        ht = function(t) {
            return t.width && t.height
        },
        ut = function(t, e) {
            var i = e.getContext("2d");
            ht(e) ? i.drawImage(t, 0, 0, e.width, e.height) : (e.width = t.width, e.height = t.height, i.drawImage(t, 0, 0))
        },
        lt = function(t) {
            u(t, 0, 0, t.width, t.height, 3)
        },
        ct = function(t, e) {
            return parseInt(t.width, 10) >= e.width && parseInt(t.height, 10) >= e.height
        },
        dt = function(t, e, i) {
            return {
                x: t.x * e,
                y: t.y * i,
                width: t.width * e,
                height: t.height * i
            }
        },
        pt = function(t, e, i) {
            return {
                x: t.x / e,
                y: t.y / i,
                width: t.width / e,
                height: t.height / i
            }
        },
        ft = function(t) {
            if (t && "" !== t.value) {
                try {
                    t.value = ""
                } catch (t) {}
                if (t.value) {
                    var e = document.createElement("form"),
                        i = t.parentNode,
                        n = t.nextSibling;
                    e.appendChild(t), e.reset(), n ? i.insertBefore(t, n) : i.appendChild(t)
                }
            }
        },
        _t = function(t) {
            return "object" === ("undefined" == typeof value ? "undefined" : c(value)) && null !== value ? JSON.parse(JSON.stringify(t)) : t
        },
        mt = function(t) {
            var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : null;
            if (!t)
                return null;
            var i = t.slice(0, t.size, e || t.type);
            return i.name = t.name, "lastModified" in File.prototype && t.lastModified ? i.lastModified = new Date(t.lastModified) : t.lastModifiedDate && (i.lastModifiedDate = new Date(t.lastModifiedDate)), i
        },
        gt = function(t) {
            var e = _t(t);
            return e.input.file = mt(t.input.file), e.output.image = st(t.output.image), e
        },
        vt = function(t, e, i) {
            return t && e ? t.toDataURL(e, z(e) && "number" == typeof i ? i / 100 : void 0) : null
        },
        yt = function(t) {
            if (!t)
                return null;
            var e = t.substr(0, 16).match(/^.+;/);
            return e.length ? e[0].substring(5, e[0].length - 1) : null
        },
        wt = function(t) {
            var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : [],
                i = arguments[2],
                n = arguments[3],
                o = arguments[4],
                r = {
                    server: _t(t.server),
                    meta: _t(t.meta),
                    input: {
                        name: t.input.name,
                        type: t.input.type,
                        size: t.input.size,
                        width: t.input.width,
                        height: t.input.height,
                        field: t.input.field
                    }
                };
            return L("input", e) && !o && (r.input.image = vt(t.input.image, t.input.type)), L("output", e) && (r.output = {
                name: n ? j(t.input.name) + "." + n : t.input.name,
                type: y[n] || t.input.type,
                width: t.output.width,
                height: t.output.height
            }, r.output.image = vt(t.output.image, r.output.type, i), r.output.type = yt(r.output.image), "image/png" === r.output.type && (r.output.name = j(r.input.name) + ".png")), L("actions", e) && (r.actions = _t(t.actions)), r
        },
        bt = function(t, e, i) {
            var n = t.output.image,
                o = i ? j(t.input.name) + "." + i : t.input.name,
                r = y[i] || t.input.type;
            "image/png" === r && (o = j(t.input.name) + ".png"), n.toBlob(function(t) {
                if ("msSaveBlob" in window.navigator)
                    return void window.navigator.msSaveBlob(t, o);
                var e = (window.URL || window.webkitURL).createObjectURL(t),
                    i = b("a");
                i.style.display = "none", i.download = o, i.href = e, document.body.appendChild(i), i.click(), setTimeout(function() {
                    document.body.removeChild(i), (window.URL || window.webkitURL).revokeObjectURL(e)
                }, 0)
            }, r, "number" == typeof e ? e / 100 : void 0)
        },
        xt = function(t, e, i) {
            var n = i.querySelector(t);
            n && (n.style.display = e ? "" : "none")
        },
        kt = function(t) {
            return Array.prototype.slice.call(t)
        },
        St = function(t) {
            t.parentNode.removeChild(t)
        },
        Ct = function(t) {
            var e = b("div");
            return t.parentNode && (t.nextSibling ? t.parentNode.insertBefore(e, t.nextSibling) : t.parentNode.appendChild(e)), e.appendChild(t), e
        },
        Et = function(t, e, i, n) {
            var o = (n - 90) * Math.PI / 180;
            return {
                x: t + i * Math.cos(o),
                y: e + i * Math.sin(o)
            }
        },
        Mt = function(t, e, i, n, o) {
            var r = Et(t, e, i, o),
                s = Et(t, e, i, n),
                a = o - n <= 180 ? "0" : "1",
                h = ["M", r.x, r.y, "A", i, i, 0, a, 0, s.x, s.y].join(" ");
            return h
        },
        Tt = function(t, e, i, n) {
            return Mt(t, e, i, 0, 360 * n)
        },
        Pt = function() {
            var e = {
                n: function(t, e, i, n) {
                    var o,
                        r,
                        s,
                        a,
                        h,
                        u,
                        l,
                        c;
                    return s = t.y + t.height, o = A(e.y, 0, s), s - o < i.min.height && (o = s - i.min.height), h = n ? (s - o) / n : t.width, h < i.min.width && (h = i.min.width, o = s - h * n), l = .5 * (h - t.width), a = t.x - l, r = t.x + t.width + l, (a < 0 || r > i.width) && (c = Math.min(t.x, i.width - (t.x + t.width)), a = t.x - c, r = t.x + t.width + c, h = r - a, u = h * n, o = s - u), {
                        x: a,
                        y: o,
                        width: r - a,
                        height: s - o
                    }
                },
                s: function(t, e, i, n) {
                    var o,
                        r,
                        s,
                        a,
                        h,
                        u,
                        l,
                        c;
                    return o = t.y, s = A(e.y, o, i.height), s - o < i.min.height && (s = o + i.min.height), h = n ? (s - o) / n : t.width, h < i.min.width && (h = i.min.width, s = o + h * n), l = .5 * (h - t.width), a = t.x - l, r = t.x + t.width + l, (a < 0 || r > i.width) && (c = Math.min(t.x, i.width - (t.x + t.width)), a = t.x - c, r = t.x + t.width + c, h = r - a, u = h * n, s = o + u), {
                        x: a,
                        y: o,
                        width: r - a,
                        height: s - o
                    }
                },
                e: function(t, e, i, n) {
                    var o,
                        r,
                        s,
                        a,
                        h,
                        u,
                        l,
                        c;
                    return a = t.x, r = A(e.x, a, i.width), r - a < i.min.width && (r = a + i.min.width), u = n ? (r - a) * n : t.height, u < i.min.height && (u = i.min.height, r = a + u / n), l = .5 * (u - t.height), o = t.y - l, s = t.y + t.height + l, (o < 0 || s > i.height) && (c = Math.min(t.y, i.height - (t.y + t.height)), o = t.y - c, s = t.y + t.height + c, u = s - o, h = u / n, r = a + h), {
                        x: a,
                        y: o,
                        width: r - a,
                        height: s - o
                    }
                },
                w: function t(e, i, n, o) {
                    var r,
                        s,
                        a,
                        h,
                        t,
                        u,
                        l,
                        c;
                    return s = e.x + e.width, h = A(i.x, 0, s), s - h < n.min.width && (h = s - n.min.width), u = o ? (s - h) * o : e.height, u < n.min.height && (u = n.min.height, h = s - u / o), l = .5 * (u - e.height), r = e.y - l, a = e.y + e.height + l, (r < 0 || a > n.height) && (c = Math.min(e.y, n.height - (e.y + e.height)), r = e.y - c, a = e.y + e.height + c, u = a - r, t = u / o, h = s - t), {
                        x: h,
                        y: r,
                        width: s - h,
                        height: a - r
                    }
                },
                ne: function(t, e, i, n) {
                    var o,
                        r,
                        s,
                        a,
                        h,
                        u,
                        l;
                    return a = t.x, s = t.y + t.height, r = A(e.x, a, i.width), r - a < i.min.width && (r = a + i.min.width), u = n ? (r - a) * n : A(s - e.y, i.min.height, s), u < i.min.height && (u = i.min.height, r = a + u / n), o = t.y - (u - t.height), (o < 0 || s > i.height) && (l = Math.min(t.y, i.height - (t.y + t.height)), o = t.y - l, u = s - o, h = u / n, r = a + h), {
                        x: a,
                        y: o,
                        width: r - a,
                        height: s - o
                    }
                },
                se: function(t, e, i, n) {
                    var o,
                        r,
                        s,
                        a,
                        h,
                        u,
                        l;
                    return a = t.x, o = t.y, r = A(e.x, a, i.width), r - a < i.min.width && (r = a + i.min.width), u = n ? (r - a) * n : A(e.y - t.y, i.min.height, i.height - o), u < i.min.height && (u = i.min.height, r = a + u / n), s = t.y + t.height + (u - t.height), (o < 0 || s > i.height) && (l = Math.min(t.y, i.height - (t.y + t.height)), s = t.y + t.height + l, u = s - o, h = u / n, r = a + h), {
                        x: a,
                        y: o,
                        width: r - a,
                        height: s - o
                    }
                },
                sw: function(t, e, i, n) {
                    var o,
                        r,
                        s,
                        a,
                        h,
                        u,
                        l;
                    return r = t.x + t.width, o = t.y, a = A(e.x, 0, r), r - a < i.min.width && (a = r - i.min.width), u = n ? (r - a) * n : A(e.y - t.y, i.min.height, i.height - o), u < i.min.height && (u = i.min.height, a = r - u / n), s = t.y + t.height + (u - t.height), (o < 0 || s > i.height) && (l = Math.min(t.y, i.height - (t.y + t.height)), s = t.y + t.height + l, u = s - o, h = u / n, a = r - h), {
                        x: a,
                        y: o,
                        width: r - a,
                        height: s - o
                    }
                },
                nw: function(t, e, i, n) {
                    var o,
                        r,
                        s,
                        a,
                        h,
                        u,
                        l;
                    return r = t.x + t.width, s = t.y + t.height, a = A(e.x, 0, r), r - a < i.min.width && (a = r - i.min.width), u = n ? (r - a) * n : A(s - e.y, i.min.height, s), u < i.min.height && (u = i.min.height,
                    a = r - u / n), o = t.y - (u - t.height), (o < 0 || s > i.height) && (l = Math.min(t.y, i.height - (t.y + t.height)), o = t.y - l, u = s - o, h = u / n, a = r - h), {
                        x: a,
                        y: o,
                        width: r - a,
                        height: s - o
                    }
                }
            };
            return function() {
                function i() {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : document.createElement("div");
                    t(this, i), this._element = e, this._interaction = null, this._minWidth = 0, this._minHeight = 0, this._ratio = null, this._rect = {
                        x: 0,
                        y: 0,
                        width: 0,
                        height: 0
                    }, this._space = {
                        width: 0,
                        height: 0
                    }, this._rectChanged = !1, this._init()
                }
                return l(i, [{
                    key: "_init",
                    value: function() {
                        this._element.className = "slim-crop-area";
                        var t = b("div", "grid");
                        this._element.appendChild(t);
                        for (var i in e)
                            if (e.hasOwnProperty(i)) {
                                var n = b("button", i);
                                this._element.appendChild(n)
                            }
                        var o = b("button", "c");
                        this._element.appendChild(o), x(document, v.DOWN, this)
                    }
                }, {
                    key: "reset",
                    value: function() {
                        this._interaction = null, this._rect = {
                            x: 0,
                            y: 0,
                            width: 0,
                            height: 0
                        }, this._rectChanged = !0, this._redraw(), this._element.dispatchEvent(new CustomEvent("change"))
                    }
                }, {
                    key: "rescale",
                    value: function(t) {
                        1 !== t && (this._interaction = null, this._rectChanged = !0, this._rect.x *= t, this._rect.y *= t, this._rect.width *= t, this._rect.height *= t, this._redraw(), this._element.dispatchEvent(new CustomEvent("change")))
                    }
                }, {
                    key: "limit",
                    value: function(t, e) {
                        this._space.width = t, this._space.height = e
                    }
                }, {
                    key: "offset",
                    value: function(t, e) {
                        this._space.x = t, this._space.y = e
                    }
                }, {
                    key: "resize",
                    value: function(t, e, i, n) {
                        this._interaction = null, this._rect = {
                            x: A(t, 0, this._space.width - this._minWidth),
                            y: A(e, 0, this._space.height - this._minHeight),
                            width: A(i, this._minWidth, this._space.width),
                            height: A(n, this._minHeight, this._space.height)
                        }, this._rectChanged = !0, this._redraw(), this._element.dispatchEvent(new CustomEvent("change"))
                    }
                }, {
                    key: "handleEvent",
                    value: function(t) {
                        switch (t.type) {
                        case "touchstart":
                        case "pointerdown":
                        case "mousedown":
                            this._onStartDrag(t);
                            break;
                        case "touchmove":
                        case "pointermove":
                        case "mousemove":
                            this._onDrag(t);
                            break;
                        case "touchend":
                        case "touchcancel":
                        case "pointerup":
                        case "mouseup":
                            this._onStopDrag(t)
                        }
                    }
                }, {
                    key: "_onStartDrag",
                    value: function(t) {
                        this._element.contains(t.target) && (t.preventDefault(), x(document, v.MOVE, this), x(document, v.UP, this), this._interaction = {
                            type: t.target.className,
                            offset: E(t)
                        }, this._interaction.offset.x -= this._rect.x, this._interaction.offset.y -= this._rect.y, this._element.setAttribute("data-dragging", "true"), this._redraw())
                    }
                }, {
                    key: "_onDrag",
                    value: function(t) {
                        t.preventDefault();
                        var i = E(t),
                            n = this._interaction.type;
                        "c" === n ? (this._rect.x = A(i.x - this._interaction.offset.x, 0, this._space.width - this._rect.width), this._rect.y = A(i.y - this._interaction.offset.y, 0, this._space.height - this._rect.height)) : e[n] && (this._rect = e[n](this._rect, {
                            x: i.x - this._space.x,
                            y: i.y - this._space.y
                        }, {
                            x: 0,
                            y: 0,
                            width: this._space.width,
                            height: this._space.height,
                            min: {
                                width: this._minWidth,
                                height: this._minHeight
                            }
                        }, this._ratio)), this._rectChanged = !0, this._element.dispatchEvent(new CustomEvent("input"))
                    }
                }, {
                    key: "_onStopDrag",
                    value: function(t) {
                        t.preventDefault(), k(document, v.MOVE, this), k(document, v.UP, this), this._interaction = null, this._element.setAttribute("data-dragging", "false"), this._element.dispatchEvent(new CustomEvent("change"))
                    }
                }, {
                    key: "_redraw",
                    value: function() {
                        var t = this;
                        if (this._rectChanged) {
                            var e = "translate(" + this._rect.x + "px," + this._rect.y + "px);";
                            this._element.style.cssText = "\n\t\t\t\t\t-webkit-transform: " + e + ";\n\t\t\t\t\ttransform: " + e + ";\n\t\t\t\t\twidth:" + this._rect.width + "px;\n\t\t\t\t\theight:" + this._rect.height + "px;\n\t\t\t\t", this._rectChanged = !1
                        }
                        this._interaction && requestAnimationFrame(function() {
                            return t._redraw()
                        })
                    }
                }, {
                    key: "destroy",
                    value: function() {
                        this._interaction = !1, this._rectChanged = !1, k(document, v.DOWN, this), k(document, v.MOVE, this), k(document, v.UP, this), St(this._element)
                    }
                }, {
                    key: "element",
                    get: function() {
                        return this._element
                    }
                }, {
                    key: "space",
                    get: function() {
                        return this._space
                    }
                }, {
                    key: "area",
                    get: function() {
                        var t = this._rect.x / this._space.width,
                            e = this._rect.y / this._space.height,
                            i = this._rect.width / this._space.width,
                            n = this._rect.height / this._space.height;
                        return {
                            x: t,
                            y: e,
                            width: i,
                            height: n
                        }
                    }
                }, {
                    key: "dirty",
                    get: function() {
                        return 0 !== this._rect.x || 0 !== this._rect.y || 0 !== this._rect.width || 0 !== this._rect.height
                    }
                }, {
                    key: "minWidth",
                    set: function(t) {
                        this._minWidth = t
                    }
                }, {
                    key: "minHeight",
                    set: function(t) {
                        this._minHeight = t
                    }
                }, {
                    key: "ratio",
                    set: function(t) {
                        this._ratio = t
                    }
                }]), i
            }()
        }(),
        At = function() {
            var e = ["input", "change"],
                i = function() {
                    function i() {
                        var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : document.createElement("div"),
                            n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
                        t(this, i), this._element = e, this._options = m(i.options(), n), this._ratio = null, this._output = null, this._input = null, this._preview = null, this._previewBlurred = null, this._blurredPreview = !1, this._cropper = null, this._straightCrop = null, this._previewWrapper = null, this._currentWindowSize = {}, this._btnGroup = null, this._maskFrame = null, this._dirty = !1, this._wrapperRotation = 0, this._wrapperScale = 1, this._init()
                    }
                    return l(i, [{
                        key: "_init",
                        value: function() {
                            var t = this;
                            this._element.className = "slim-image-editor", this._container = b("div", "slim-container"), this._wrapper = b("div", "slim-wrapper"), this._stage = b("div", "slim-stage"), this._container.appendChild(this._stage), this._cropper = new Pt, e.forEach(function(e) {
                                t._cropper.element.addEventListener(e, t)
                            }), this._stage.appendChild(this._cropper.element), this._previewWrapper = b("div", "slim-image-editor-preview slim-crop-preview"), this._previewBlurred = b("canvas", "slim-crop-blur"), this._previewWrapper.appendChild(this._previewBlurred), this._wrapper.appendChild(this._previewWrapper), this._previewMask = b("div", "slim-crop-mask"), this._preview = b("img"), this._previewMask.appendChild(this._preview), this._cropper.element.appendChild(this._previewMask), this._btnGroup = b("div", "slim-editor-btn-group"), i.Buttons.forEach(function(e) {
                                var i = T(e),
                                    n = t._options["button" + i + "Label"],
                                    o = t._options["button" + i + "Title"],
                                    r = t._options["button" + i + "ClassName"],
                                    s = b("button", "slim-editor-btn slim-btn-" + e + (r ? " " + r : ""));
                                s.innerHTML = n, s.title = o || n, s.type = "button", s.setAttribute("data-action", e), s.addEventListener("click", t), t._btnGroup.appendChild(s)
                            }), this._utilsGroup = b("div", "slim-editor-utils-group");
                            var n = b("button", "slim-editor-utils-btn slim-btn-rotate" + (this._options.buttonRotateClassName ? " " + this._options.buttonRotateClassName : ""));
                            n.setAttribute("data-action", "rotate"), n.addEventListener("click", this), n.title = this._options.buttonRotateTitle, this._utilsGroup.appendChild(n), this._container.appendChild(this._wrapper), this._element.appendChild(this._container), this._element.appendChild(this._utilsGroup), this._element.appendChild(this._btnGroup)
                        }
                    }, {
                        key: "dirty",
                        value: function() {
                            this._dirty = !0
                        }
                    }, {
                        key: "handleEvent",
                        value: function(t) {
                            switch (t.type) {
                            case "click":
                                this._onClick(t);
                                break;
                            case "change":
                                this._onGridChange(t);
                                break;
                            case "input":
                                this._onGridInput(t);
                                break;
                            case "keydown":
                                this._onKeyDown(t);
                                break;
                            case "resize":
                                this._onResize(t)
                            }
                        }
                    }, {
                        key: "_onKeyDown",
                        value: function(t) {
                            switch (t.keyCode) {
                            case g.RETURN:
                                this._confirm();
                                break;
                            case g.ESC:
                                this._cancel()
                            }
                        }
                    }, {
                        key: "_onClick",
                        value: function(t) {
                            t.target.classList.contains("slim-btn-cancel") && this._cancel(), t.target.classList.contains("slim-btn-confirm") && this._confirm(), t.target.classList.contains("slim-btn-rotate") && this._rotate()
                        }
                    }, {
                        key: "_onResize",
                        value: function() {
                            this._currentWindowSize = {
                                width: window.innerWidth,
                                height: window.innerHeight
                            }, this._redraw(), this._redrawCropper(this._cropper.area), this._updateWrapperScale(), this._redrawWrapper()
                        }
                    }, {
                        key: "_redrawWrapper",
                        value: function() {
                            var t = h.createMatrix();
                            t.scale(this._wrapperScale, this._wrapperScale), t.rotateZ(this._wrapperRotation * (Math.PI / 180)), h.setElementTransform(this._previewWrapper, t)
                        }
                    }, {
                        key: "_onGridInput",
                        value: function() {
                            this._redrawCropMask()
                        }
                    }, {
                        key: "_onGridChange",
                        value: function() {
                            this._redrawCropMask()
                        }
                    }, {
                        key: "_updateWrapperRotation",
                        value: function() {
                            this._options.minSize.width > this._input.height || this._options.minSize.height > this._input.width ? this._wrapperRotation += 180 : this._wrapperRotation += 90
                        }
                    }, {
                        key: "_updateWrapperScale",
                        value: function() {
                            var t = this._wrapperRotation % 180 !== 0;
                            if (t) {
                                var e = this._container.offsetWidth,
                                    i = this._container.offsetHeight,
                                    n = this._wrapper.offsetHeight,
                                    o = this._wrapper.offsetWidth,
                                    r = e / n;
                                r * o > i && (r = i / o), this._wrapperScale = r
                            } else
                                this._wrapperScale = 1
                        }
                    }, {
                        key: "_cancel",
                        value: function() {
                            this._element.dispatchEvent(new CustomEvent("cancel"))
                        }
                    }, {
                        key: "_confirm",
                        value: function() {
                            var t = this._wrapperRotation % 180 !== 0,
                                e = this._cropper.area,
                                i = dt(e, t ? this._input.height : this._input.width, t ? this._input.width : this._input.height);
                            this._element.dispatchEvent(new CustomEvent("confirm", {
                                detail: {
                                    rotation: this._wrapperRotation % 360,
                                    crop: i
                                }
                            }))
                        }
                    }, {
                        key: "_rotate",
                        value: function() {
                            var t = this;
                            this._updateWrapperRotation();
                            var e = 1 === this.ratio || null === this._ratio ? this._cropper.area : null;
                            e && C(e, 90), this._updateWrapperScale(), this._hideCropper(), h(this._previewWrapper, {
                                rotation: [0, 0, this._wrapperRotation * (Math.PI / 180)],
                                scale: [this._wrapperScale, this._wrapperScale],
                                easing: "spring",
                                springConstant: .8,
                                springDeceleration: .65,
                                complete: function() {
                                    t._redrawCropper(e), t._showCropper()
                                }
                            })
                        }
                    }, {
                        key: "_showCropper",
                        value: function() {
                            h(this._stage, {
                                easing: "ease",
                                duration: 250,
                                fromOpacity: 0,
                                opacity: 1
                            })
                        }
                    }, {
                        key: "_hideCropper",
                        value: function() {
                            h(this._stage, {
                                duration: 0,
                                fromOpacity: 0,
                                opacity: 0
                            })
                        }
                    }, {
                        key: "_redrawCropMask",
                        value: function() {
                            var t = this,
                                e = this._wrapperRotation % 360,
                                i = this._wrapperScale,
                                n = {
                                    width: this._wrapper.offsetWidth,
                                    height: this._wrapper.offsetHeight
                                },
                                o = this._cropper.area,
                                r = {
                                    x: 0,
                                    y: 0
                                };
                            0 === e ? (r.x = -o.x, r.y = -o.y) : 90 === e ? (r.x = -(1 - o.y), r.y = -o.x) : 180 === e ? (r.x = -(1 - o.x), r.y = -(1 - o.y)) : 270 === e && (r.x = -o.y, r.y = -(1 - o.x)), r.x *= n.width, r.y *= n.height, cancelAnimationFrame(this._maskFrame), this._maskFrame = requestAnimationFrame(function() {
                                var n = "scale(" + i + ") rotate(" + -e + "deg) translate(" + r.x + "px, " + r.y + "px);";
                                t._preview.style.cssText = "\n\t\t\t\t\twidth: " + t._previewSize.width + "px;\n\t\t\t\t\theight: " + t._previewSize.height + "px;\n\t\t\t\t\t-webkit-transform: " + n + ";\n\t\t\t\t\ttransform: " + n + ";\n\t\t\t\t"
                            })
                        }
                    }, {
                        key: "open",
                        value: function(t, e, i, n, o) {
                            var r = this;
                            if (this._input && !this._dirty && this._ratio === e)
                                return void o();
                            this._currentWindowSize = {
                                width: window.innerWidth,
                                height: window.innerHeight
                            }, this._dirty = !1, this._wrapperRotation = n || 0, this._blurredPreview = !1, this._ratio = e, this._previewSize = null, this._element.style.opacity = "0", this._input = t;
                            var s = n % 180 !== 0,
                                a = pt(i, s ? t.height : t.width, s ? t.width : t.height);
                            this._preview.onload = function() {
                                r._preview.onload = null, r._cropper.ratio = r.ratio, r._redraw(), r._redrawCropper(a), o(), r._element.style.opacity = ""
                            }, this._preview.src = at(this._input, Math.min(this._container.offsetWidth / this._input.width, this._container.offsetHeight / this._input.height)).toDataURL()
                        }
                    }, {
                        key: "_redrawCropper",
                        value: function(t) {
                            var e = this._wrapperRotation % 180 !== 0,
                                i = e ? this._input.height / this._input.width : this._input.width / this._input.height,
                                n = this._wrapper.offsetWidth,
                                o = this._wrapper.offsetHeight,
                                r = this._container.offsetWidth,
                                s = this._container.offsetHeight;
                            this._updateWrapperScale();
                            var a = this._wrapperScale * (e ? o : n),
                                h = this._wrapperScale * (e ? n : o),
                                u = e ? .5 * (r - a) : this._wrapper.offsetLeft,
                                l = e ? .5 * (s - h) : this._wrapper.offsetTop;
                            this._stage.style.cssText = "\n\t\t\t\tleft:" + u + "px;\n\t\t\t\ttop:" + l + "px;\n\t\t\t\twidth:" + a + "px;\n\t\t\t\theight:" + h + "px;\n\t\t\t", this._cropper.limit(a, a / i), this._cropper.offset(u + this._element.offsetLeft, l + this._element.offsetTop), this._cropper.minWidth = this._wrapperScale * this._options.minSize.width * this.scalar, this._cropper.minHeight = this._wrapperScale * this._options.minSize.height * this.scalar;
                            var c = null;
                            c = t ? {
                                x: t.x * a,
                                y: t.y * h,
                                width: t.width * a,
                                height: t.height * h
                            } : Q(a, h, this._ratio || h / a), this._cropper.resize(c.x, c.y, c.width, c.height)
                        }
                    }, {
                        key: "_redraw",
                        value: function() {
                            var t = this._input.height / this._input.width,
                                e = this._container.clientWidth,
                                i = this._container.clientHeight,
                                n = e,
                                o = n * t;
                            o > i && (o = i, n = o / t), n = Math.round(n), o = Math.round(o);
                            var r = (e - n) / 2,
                                s = (i - o) / 2;
                            this._wrapper.style.cssText = "\n\t\t\t\tleft:" + r + "px;\n\t\t\t\ttop:" + s + "px;\n\t\t\t\twidth:" + n + "px;\n\t\t\t\theight:" + o + "px;\n\t\t\t", this._previewBlurred.style.cssText = "\n\t\t\t\twidth:" + n + "px;\n\t\t\t\theight:" + o + "px;\n\t\t\t", this._preview.style.cssText = "\n\t\t\t\twidth:" + n + "px;\n\t\t\t\theight:" + o + "px;\n\t\t\t", this._previewSize = {
                                width: n,
                                height: o
                            }, this._blurredPreview || (this._previewBlurred.width = 300, this._previewBlurred.height = this._previewBlurred.width * t, ut(this._input, this._previewBlurred), lt(this._previewBlurred, 3), this._blurredPreview = !0)
                        }
                    }, {
                        key: "show",
                        value: function() {
                            var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : function() {};
                            this._currentWindowSize.width === window.innerWidth && this._currentWindowSize.height === window.innerHeight || (this._redraw(), this._redrawCropper(this._cropper.area)), document.addEventListener("keydown", this), window.addEventListener("resize", this);
                            var e = this._wrapperRotation * (Math.PI / 180);
                            h(this._previewWrapper, {
                                fromRotation: [0, 0, e],
                                rotation: [0, 0, e],
                                fromPosition: [0, 0, 0],
                                position: [0, 0, 0],
                                fromOpacity: 0,
                                opacity: 1,
                                fromScale: [this._wrapperScale - .02, this._wrapperScale - .02],
                                scale: [this._wrapperScale, this._wrapperScale],
                                easing: "spring",
                                springConstant: .3,
                                springDeceleration: .85,
                                delay: 450,
                                complete: function() {}
                            }), this._cropper.dirty ? h(this._stage, {
                                fromPosition: [0, 0, 0],
                                position: [0, 0, 0],
                                fromOpacity: 0,
                                opacity: 1,
                                duration: 250,
                                delay: 850,
                                complete: function() {
                                    I(this), t()
                                }
                            }) : h(this._stage, {
                                fromPosition: [0, 0, 0],
                                position: [0, 0, 0],
                                fromOpacity: 0,
                                opacity: 1,
                                duration: 250,
                                delay: 1e3,
                                complete: function() {
                                    I(this)
                                }
                            }), h(this._btnGroup.childNodes, {
                                fromScale: [.9, .9],
                                scale: [1, 1],
                                fromOpacity: 0,
                                opacity: 1,
                                delay: function(t) {
                                    return 1e3 + 100 * t
                                },
                                easing: "spring",
                                springConstant: .3,
                                springDeceleration: .85,
                                complete: function() {
                                    I(this)
                                }
                            }), h(this._utilsGroup.childNodes, {
                                fromScale: [.9, .9],
                                scale: [1, 1],
                                fromOpacity: 0,
                                opacity: 1,
                                easing: "spring",
                                springConstant: .3,
                                springDeceleration: .85,
                                delay: 1250,
                                complete: function() {
                                    I(this)
                                }
                            })
                        }
                    }, {
                        key: "hide",
                        value: function() {
                            var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : function() {};
                            document.removeEventListener("keydown", this), window.removeEventListener("resize", this), h(this._utilsGroup.childNodes, {
                                fromOpacity: 1,
                                opacity: 0,
                                duration: 250
                            }), h(this._btnGroup.childNodes, {
                                fromOpacity: 1,
                                opacity: 0,
                                delay: 200,
                                duration: 350
                            }), h([this._stage, this._previewWrapper], {
                                fromPosition: [0, 0, 0],
                                position: [0, -250, 0],
                                fromOpacity: 1,
                                opacity: 0,
                                easing: "spring",
                                springConstant: .3,
                                springDeceleration: .75,
                                delay: 250,
                                allDone: function() {
                                    t()
                                }
                            })
                        }
                    }, {
                        key: "destroy",
                        value: function() {
                            var t = this;
                            kt(this._btnGroup.children).forEach(function(e) {
                                e.removeEventListener("click", t)
                            }), e.forEach(function(e) {
                                t._cropper.element.removeEventListener(e, t)
                            }), this._cropper.destroy(), this._element.parentNode && St(this._element)
                        }
                    }, {
                        key: "showRotateButton",
                        set: function(t) {
                            t ? this._element.classList.remove("slim-rotation-disabled") : this._element.classList.add("slim-rotation-disabled")
                        }
                    }, {
                        key: "element",
                        get: function() {
                            return this._element
                        }
                    }, {
                        key: "ratio",
                        get: function() {
                            return "input" === this._ratio ? this._input.height / this._input.width : this._ratio
                        }
                    }, {
                        key: "offset",
                        get: function() {
                            return this._element.getBoundingClientRect()
                        }
                    }, {
                        key: "original",
                        get: function() {
                            return this._input
                        }
                    }, {
                        key: "scalar",
                        get: function() {
                            return this._previewSize.width / this._input.width
                        }
                    }], [{
                        key: "options",
                        value: function() {
                            return {
                                buttonCancelClassName: null,
                                buttonConfirmClassName: null,
                                buttonCancelLabel: "Cancel",
                                buttonConfirmLabel: "Confirm",
                                buttonCancelTitle: null,
                                buttonConfirmTitle: null,
                                buttonRotateTitle: "Rotate",
                                buttonRotateClassName: null,
                                minSize: {
                                    width: 0,
                                    height: 0
                                }
                            }
                        }
                    }]), i
                }();
            return i.Buttons = ["cancel", "confirm"], i
        }(Pt),
        Lt = function() {
            var e = ["dragenter", "dragover", "dragleave", "drop"];
            return function() {
                function i() {
                    var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : document.createElement("div");
                    t(this, i), this._element = e, this._accept = [], this._allowURLs = !1, this._dragPath = null, this._init()
                }
                return l(i, [{
                    key: "isValidDataTransfer",
                    value: function(t) {
                        return t.files && t.files.length ? this.areValidDataTransferFiles(t.files) : t.items && t.items.length ? this.areValidDataTransferItems(t.items) : null
                    }
                }, {
                    key: "areValidDataTransferFiles",
                    value: function(t) {
                        return !this._accept.length || !t || this._accept.indexOf(t[0].type) !== -1
                    }
                }, {
                    key: "areValidDataTransferItems",
                    value: function(t) {
                        return !this._accept.length || !t || (this._allowURLs && "string" === t[0].kind ? null : t[0].type && 0 === t[0].type.indexOf("application") ? null : this._accept.indexOf(t[0].type) !== -1)
                    }
                }, {
                    key: "reset",
                    value: function() {
                        this._element.files = null
                    }
                }, {
                    key: "_init",
                    value: function() {
                        var t = this;
                        this._element.className = "slim-file-hopper", e.forEach(function(e) {
                            t._element.addEventListener(e, t)
                        })
                    }
                }, {
                    key: "handleEvent",
                    value: function(t) {
                        switch (t.type) {
                        case "dragenter":
                        case "dragover":
                            this._onDragOver(t);
                            break;
                        case "dragleave":
                            this._onDragLeave(t);
                            break;
                        case "drop":
                            this._onDrop(t)
                        }
                    }
                }, {
                    key: "_onDrop",
                    value: function(t) {
                        t.preventDefault();
                        var e = null;
                        if (this._allowURLs) {
                            var i = void 0,
                                n = void 0;
                            try {
                                i = t.dataTransfer.getData("url"), n = t.dataTransfer.getData("text/html")
                            } catch (t) {}
                            if (n && n.length) {
                                var o = n.match(/src\s*=\s*"(.+?)"/);
                                o && (e = o[1])
                            } else
                                i && i.length && (e = i)
                        }
                        if (e)
                            this._element.files = [{
                                remote: e
                            }];
                        else {
                            var r = this.isValidDataTransfer(t.dataTransfer);
                            if (!r)
                                return this._element.dispatchEvent(new CustomEvent("file-invalid-drop")), void (this._dragPath = null);
                            this._element.files = t.dataTransfer.files
                        }
                        this._element.dispatchEvent(new CustomEvent("file-drop", {
                            detail: _(t)
                        })), this._element.dispatchEvent(new CustomEvent("change")), this._dragPath = null
                    }
                }, {
                    key: "_onDragOver",
                    value: function(t) {
                        t.preventDefault(), t.dataTransfer.dropEffect = "copy";
                        var e = this.isValidDataTransfer(t.dataTransfer);
                        console.log(e);
                        return null === e || e ? (this._dragPath || (this._dragPath = []), this._dragPath.push(_(t)), void this._element.dispatchEvent(new CustomEvent("file-over", {
                            detail: {
                                x: P(this._dragPath).x,
                                y: P(this._dragPath).y
                            }
                        }))) : (t.dataTransfer.dropEffect = "none", void this._element.dispatchEvent(new CustomEvent("file-invalid")))
                    }
                }, {
                    key: "_onDragLeave",
                    value: function(t) {
                        this._element.dispatchEvent(new CustomEvent("file-out", {
                            detail: _(t)
                        })), this._dragPath = null
                    }
                }, {
                    key: "destroy",
                    value: function() {
                        var t = this;
                        e.forEach(function(e) {
                            t._element.removeEventListener(e, t)
                        }), St(this._element), this._element = null, this._dragPath = null, this._accept = null
                    }
                }, {
                    key: "element",
                    get: function() {
                        return this._element
                    }
                }, {
                    key: "dragPath",
                    get: function() {
                        return this._dragPath
                    }
                }, {
                    key: "enabled",
                    get: function() {
                        return "" === this._element.style.display
                    },
                    set: function(t) {
                        this._element.style.display = t ? "" : "none"
                    }
                }, {
                    key: "allowURLs",
                    set: function(t) {
                        this._allowURLs = t
                    }
                }, {
                    key: "accept",
                    set: function(t) {
                        this._accept = t
                    },
                    get: function() {
                        return this._accept
                    }
                }]), i
            }()
        }(),
        Ot = function() {
            return function() {
                function e() {
                    t(this, e), this._element = null, this._inner = null, this._init()
                }
                return l(e, [{
                    key: "_init",
                    value: function() {
                        this._element = b("div", "slim-popover"), this._element.setAttribute("data-state", "off"), document.body.appendChild(this._element)
                    }
                }, {
                    key: "show",
                    value: function() {
                        var t = this,
                            e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : function() {};
                        this._element.setAttribute("data-state", "on"), h(this._element, {
                            fromOpacity: 0,
                            opacity: 1,
                            duration: 350,
                            complete: function() {
                                I(t._element), e()
                            }
                        })
                    }
                }, {
                    key: "hide",
                    value: function() {
                        var t = this,
                            e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : function() {};
                        h(this._element, {
                            fromOpacity: 1,
                            opacity: 0,
                            duration: 500,
                            complete: function() {
                                I(t._element), t._element.setAttribute("data-state", "off"), e()
                            }
                        })
                    }
                }, {
                    key: "destroy",
                    value: function() {
                        this._element.parentNode && (this._element.parentNode.removeChild(this._element), this._element = null, this._inner = null)
                    }
                }, {
                    key: "inner",
                    set: function(t) {
                        this._inner = t, this._element.firstChild && this._element.removeChild(this._element.firstChild), this._element.appendChild(this._inner)
                    }
                }, {
                    key: "className",
                    set: function(t) {
                        this._element.className = "slim-popover" + (null === t ? "" : " " + t)
                    }
                }]), e
            }()
        }(),
        It = function(t, e) {
            return t.split(e).map(function(t) {
                return parseInt(t, 10)
            })
        },
        Rt = function(t) {
            return "DIV" === t.nodeName || "SPAN" === t.nodeName
        },
        Dt = {
            AUTO: "auto",
            INITIAL: "initial",
            MANUAL: "manual"
        },
        zt = ["x", "y", "width", "height"],
        Bt = ["file-invalid-drop", "file-invalid", "file-drop", "file-over", "file-out", "click"],
        Ut = ["cancel", "confirm"],
        Nt = ["remove", "edit", "download", "upload"],
        Ht = null,
        Ft = 0,
        qt = '\n<div class="slim-loader">\n\t<svg>\n\t\t<path class="slim-loader-background" fill="none" stroke-width="3" />\n\t\t<path class="slim-loader-foreground" fill="none" stroke-width="3" />\n\t</svg>\n</div>\n',
        Wt = '\n<div class="slim-upload-status"></div>\n',
        jt = function(t) {
            var e = t.split(",");
            return {
                width: parseInt(e[0], 10),
                height: parseInt(e[1], 10)
            }
        },
        Vt = function() {
            function e(i) {
                var n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
                t(this, e), Ht || (Ht = new Ot), this._uid = Ft++, this._options = m(e.options(), n), this._options.forceSize && ("string" == typeof this._options.forceSize && (this._options.forceSize = jt(this._options.forceSize)), this._options.ratio = this._options.forceSize.width + ":" + this._options.forceSize.height, this._options.size = _t(this._options.forceSize)), "string" == typeof this._options.size && (this._options.size = jt(this._options.size)), "string" == typeof this._options.minSize && (this._options.minSize = jt(this._options.minSize)), this._originalElement = i, this._originalElementInner = i.innerHTML, this._originalElementAttributes = f(i), Rt(i) ? this._element = i : (this._element = Ct(i), this._element.className = i.className, i.className = "", this._element.setAttribute("data-ratio", this._options.ratio)), this._element.classList.add("slim"), this._element.setAttribute("data-state", "init"), this._state = [], this._timers = [], this._input = null, this._inputReference = null, this._output = null, this._ratio = null, this._isRequired = !1, this._imageHopper = null, this._imageEditor = null, this._progressEnabled = !0, this._data = {}, this._resetData(), this._drip = null, this._hasInitialImage = !1, this._initialCrop = this._options.crop, this._initialRotation = this._options.rotation && this._options.rotation % 90 === 0 ? this._options.rotation : null, this._isBeingDestroyed = !1, e.supported ? this._init() : this._fallback()
            }
            return l(e, [{
                key: "setRotation",
                value: function(t, e) {
                    if ("number" == typeof t || t % 90 === 0) {
                        this._data.actions.rotation = t;
                        var i = this._data.actions.rotation % 180 !== 0;
                        if (this._data.input.image) {
                            var n = i ? this._data.input.image.height : this._data.input.image.width,
                                o = i ? this._data.input.image.width : this._data.input.image.height;
                            this._data.actions.crop = Q(n, o, this._ratio), this._data.actions.crop.type = Dt.AUTO
                        }
                        this._data.input.image && e && this._manualTransform(e)
                    }
                }
            }, {
                key: "setSize",
                value: function(t, e) {
                    "string" == typeof t && (t = jt(t)), t && t.width && t.height && (this._options.size = _t(t), this._data.actions.size = _t(t), this._data.input.image && e && this._manualTransform(e))
                }
            }, {
                key: "setRatio",
                value: function(t, e) {
                    var i = this;
                    if (t && "string" == typeof t && (this._options.ratio = t, this._isFixedRatio())) {
                        var n = It(this._options.ratio, ":");
                        this._ratio = n[1] / n[0], this._data.input.image && e ? this._cropAuto(function(t) {
                            i._scaleDropArea(i._ratio), e && e(t)
                        }) : (this._data.input.image && (this._data.actions.crop = Q(this._data.input.image.width, this._data.input.image.height, this._ratio), this._data.actions.crop.type = Dt.AUTO), this._scaleDropArea(this._ratio), e && e(null))
                    }
                }
            }, {
                key: "isAttachedTo",
                value: function(t) {
                    return this._element === t || this._originalElement === t
                }
            }, {
                key: "isDetached",
                value: function() {
                    return null === this._element.parentNode
                }
            }, {
                key: "load",
                value: function(t) {
                    var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
                        i = arguments[2];
                    "function" == typeof e ? i = e : (this._options.crop = e.crop, this._options.rotation = e.rotation, this._initialRotation = e.rotation && e.rotation % 90 === 0 ? e.rotation : null, this._initialCrop = this._options.crop), this._load(t, i, {
                        blockPush: e.blockPush
                    })
                }
            }, {
                key: "upload",
                value: function(t) {
                    this._doUpload(t)
                }
            }, {
                key: "download",
                value: function() {
                    this._doDownload()
                }
            }, {
                key: "remove",
                value: function() {
                    return this._doRemove()
                }
            }, {
                key: "destroy",
                value: function() {
                    this._doDestroy()
                }
            }, {
                key: "edit",
                value: function() {
                    this._doEdit()
                }
            }, {
                key: "crop",
                value: function(t, e) {
                    this._crop(t.x, t.y, t.width, t.height, e)
                }
            }, {
                key: "containsImage",
                value: function() {
                    return null !== this._data.input.name
                }
            }, {
                key: "_canInstantEdit",
                value: function() {
                    return this._options.instantEdit && !this._isInitialising
                }
            }, {
                key: "_getFileInput",
                value: function() {
                    return this._element.querySelector("input[type=file]")
                }
            }, {
                key: "_getInitialImage",
                value: function() {
                    return this._element.querySelector("img")
                }
            }, {
                key: "_getInputElement",
                value: function() {
                    return this._getFileInput() || this._getInitialImage()
                }
            }, {
                key: "_getRatioSpacerElement",
                value: function() {
                    return this._element.children[0]
                }
            }, {
                key: "_isImageOnly",
                value: function() {
                    return "INPUT" !== this._input.nodeName
                }
            }, {
                key: "_isFixedRatio",
                value: function() {
                    return this._options.ratio.indexOf(":") !== -1
                }
            }, {
                key: "_isAutoCrop",
                value: function() {
                    return this._data.actions.crop.type === Dt.AUTO
                }
            }, {
                key: "_toggleButton",
                value: function(t, e) {
                    xt('.slim-btn[data-action="' + t + '"]', e, this._element)
                }
            }, {
                key: "_clearState",
                value: function() {
                    this._state = [], this._updateState()
                }
            }, {
                key: "_removeState",
                value: function(t) {
                    this._state = this._state.filter(function(e) {
                        return e !== t
                    }), this._updateState()
                }
            }, {
                key: "_addState",
                value: function(t) {
                    L(t, this._state) || (this._state.push(t), this._updateState())
                }
            }, {
                key: "_updateState",
                value: function() {
                    this._element && this._element.setAttribute("data-state", this._state.join(","))
                }
            }, {
                key: "_resetData",
                value: function() {
                    this._data = {
                        server: null,
                        meta: _t(this._options.meta),
                        input: {
                            field: this._inputReference,
                            name: null,
                            type: null,
                            width: 0,
                            height: 0,
                            file: null
                        },
                        output: {
                            image: null,
                            width: 0,
                            height: 0
                        },
                        actions: {
                            rotation: null,
                            crop: null,
                            size: null
                        }
                    }, this._output && (this._output.value = ""), ft(this._getFileInput())
                }
            }, {
                key: "_init",
                value: function() {
                    var t = this;
                    if (this._isInitialising = !0, this._addState("empty"), L("input", this._options.post) && (this._inputReference = "slim_input_" + this._uid), this._input = this._getInputElement(), this._input || (this._input = b("input"), this._input.type = "file", this._element.appendChild(this._input)), this._isRequired = this._input.required === !0, this._output = this._element.querySelector("input[type=hidden]"), this._output) {
                        var e = null;
                        try {
                            e = JSON.parse(this._output.value)
                        } catch (t) {}
                        if (e) {
                            var i = new Image;
                            i.src = e.output.image, i.setAttribute("data-filename", e.output.name), this._element.insertBefore(i, this._element.firstChild)
                        }
                    } else
                        this._output = b("input"), this._output.type = "hidden", this._output.name = this._input.name || this._options.defaultInputName, this._element.appendChild(this._output);
                    this._input.removeAttribute("name");
                    var n = b("div", "slim-area"),
                        o = this._getInitialImage(),
                        r = (o || {}).src,
                        s = o ? o.getAttribute("data-filename") : null;
                    r ? this._hasInitialImage = !0 : (this._initialCrop = null, this._initialRotation = null);
                    var a = '\n\t\t<div class="slim-result">\n\t\t\t<img class="in" style="opacity:0" ' + (r ? 'src="' + r + '"' : "") + '><img><img style="opacity:0">\n\t\t</div>';
                    if (this._isImageOnly())
                        n.innerHTML = "\n\t\t\t\t" + qt + "\n\t\t\t\t" + Wt + "\n\t\t\t\t" + a + '\n\t\t\t\t<div class="slim-status"><div class="slim-label-loading">' + (this._options.labelLoading || "") + "</div></div>\n\t\t\t";
                    else {
                        L("input", this._options.post) && (this._data.input.field = this._inputReference, this._options.service || (this._input.name = this._inputReference));
                        var h = void 0;
                        this._input.hasAttribute("accept") && "image/*" !== this._input.getAttribute("accept") ? h = this._input.accept.split(",").map(function(t) {
                            return t.trim()
                        }).filter(function(t) {
                            return t.length > 0
                        }) : (h = D(), this._input.setAttribute("accept", h.join(","))), this._imageHopper = new Lt, this._imageHopper.accept = h, this._imageHopper.allowURLs = "string" == typeof this._options.fetcher, this._element.appendChild(this._imageHopper.element), Bt.forEach(function(e) {
                            t._imageHopper.element.addEventListener(e, t)
                        }), n.innerHTML = "\n\t\t\t\t" + qt + "\n\t\t\t\t" + Wt + '\n\t\t\t\t<div class="slim-drip"><span><span></span></span></div>\n\t\t\t\t<div class="slim-status"><div class="slim-label">' + (this._options.label || "") + '</div><div class="slim-label-loading">' + (this._options.labelLoading || "") + "</div></div>\n\t\t\t\t" + a + "\n\t\t\t", this._input.addEventListener("change", this)
                    }
                    if (this._element.appendChild(n), this._btnGroup = b("div", "slim-btn-group"), this._btnGroup.style.display = "none", this._element.appendChild(this._btnGroup), Nt.filter(function(e) {
                        return t._isButtonAllowed(e)
                    }).forEach(function(e) {
                        var i = T(e),
                            n = t._options["button" + i + "Label"],
                            o = t._options["button" + i + "Title"] || n,
                            r = t._options["button" + i + "ClassName"],
                            s = b("button", "slim-btn slim-btn-" + e + (r ? " " + r : ""));
                        s.innerHTML = n, s.title = o, s.type = "button", s.addEventListener("click", t), s.setAttribute("data-action", e), t._btnGroup.appendChild(s)
                    }), this._isFixedRatio()) {
                        var u = It(this._options.ratio, ":");
                        this._ratio = u[1] / u[0], this._scaleDropArea(this._ratio)
                    }
                    this._updateProgress(.5), r ? this._load(r, function() {
                        t._onInit()
                    }, {
                        name: s
                    }) : this._onInit()
                }
            }, {
                key: "_onInit",
                value: function() {
                    var t = this;
                    this._isInitialising = !1;
                    var e = function() {
                        var e = setTimeout(function() {
                            t._options.didInit.apply(t, [t.data, t])
                        }, 0);
                        t._timers.push(e)
                    };
                    this._options.saveInitialImage && this.containsImage() ? this._options.service || this._save(function() {
                        e()
                    }, !1) : (this._options.service && this.containsImage() && this._toggleButton("upload", !1), e())
                }
            }, {
                key: "_updateProgress",
                value: function(t) {
                    if (t = Math.min(.99999, t), this._element && this._progressEnabled) {
                        var e = this._element.querySelector(".slim-loader");
                        if (e) {
                            var i = e.offsetWidth,
                                n = e.querySelectorAll("path"),
                                o = parseInt(n[0].getAttribute("stroke-width"), 10);
                            n[0].setAttribute("d", Tt(.5 * i, .5 * i, .5 * i - o, .9999)), n[1].setAttribute("d", Tt(.5 * i, .5 * i, .5 * i - o, t))
                        }
                    }
                }
            }, {
                key: "_startProgress",
                value: function(t) {
                    var e = this;
                    if (this._element) {
                        this._progressEnabled = !1;
                        var i = this._element.querySelector(".slim-loader");
                        if (i) {
                            var n = i.children[0];
                            this._stopProgressLoop(function() {
                                i.removeAttribute("style"), n.removeAttribute("style"), e._progressEnabled = !0, e._updateProgress(0), e._progressEnabled = !1, h(n, {
                                    fromOpacity: 0,
                                    opacity: 1,
                                    duration: 250,
                                    complete: function() {
                                        e._progressEnabled = !0, t && t()
                                    }
                                })
                            })
                        }
                    }
                }
            }, {
                key: "_stopProgress",
                value: function() {
                    var t = this;
                    if (this._element) {
                        var e = this._element.querySelector(".slim-loader");
                        if (e) {
                            var i = e.children[0];
                            this._updateProgress(1), h(i, {
                                fromOpacity: 1,
                                opacity: 0,
                                duration: 250,
                                complete: function() {
                                    e.removeAttribute("style"), i.removeAttribute("style"), t._updateProgress(.5), t._progressEnabled = !1
                                }
                            })
                        }
                    }
                }
            }, {
                key: "_startProgressLoop",
                value: function() {
                    if (this._element) {
                        var t = this._element.querySelector(".slim-loader");
                        if (t) {
                            var e = t.children[0];
                            t.removeAttribute("style"), e.removeAttribute("style"), this._updateProgress(.5);
                            var i = 1e3;
                            h(t, "stop"), h(t, {
                                rotation: [0, 0, -(2 * Math.PI) * i],
                                easing: "linear",
                                duration: 1e3 * i
                            }), h(e, {
                                fromOpacity: 0,
                                opacity: 1,
                                duration: 250
                            })
                        }
                    }
                }
            }, {
                key: "_stopProgressLoop",
                value: function(t) {
                    if (this._element) {
                        var e = this._element.querySelector(".slim-loader");
                        if (e) {
                            var i = e.children[0];
                            h(i, {
                                fromOpacity: parseFloat(i.style.opacity),
                                opacity: 0,
                                duration: 250,
                                complete: function() {
                                    h(e, "stop"), e.removeAttribute("style"), i.removeAttribute("style"), t && t()
                                }
                            })
                        }
                    }
                }
            }, {
                key: "_isButtonAllowed",
                value: function(t) {
                    return "edit" === t ? this._options.edit : "download" === t ? this._options.download : "upload" === t ? !!this._options.service && !this._options.push : "remove" !== t || !this._isImageOnly()
                }
            }, {
                key: "_fallback",
                value: function() {
                    var t = b("div", "slim-area");
                    t.innerHTML = '\n\t\t\t<div class="slim-status"><div class="slim-label">' + (this._options.label || "") + "</div></div>\n\t\t", this._element.appendChild(t), this._throwError(this._options.statusNoSupport)
                }
            }, {
                key: "handleEvent",
                value: function(t) {
                    switch (t.type) {
                    case "click":
                        this._onClick(t);
                        break;
                    case "change":
                        this._onChange(t);
                        break;
                    case "cancel":
                        this._onCancel(t);
                        break;
                    case "confirm":
                        this._onConfirm(t);
                        break;
                    case "file-over":
                        this._onFileOver(t);
                        break;
                    case "file-out":
                        this._onFileOut(t);
                        break;
                    case "file-drop":
                        this._onDropFile(t);
                        break;
                    case "file-invalid":
                        this._onInvalidFile(t);
                        break;
                    case "file-invalid-drop":
                        this._onInvalidFileDrop(t)
                    }
                }
            }, {
                key: "_getIntro",
                value: function() {
                    return this._element.querySelector(".slim-result .in")
                }
            }, {
                key: "_getOutro",
                value: function() {
                    return this._element.querySelector(".slim-result .out")
                }
            }, {
                key: "_getInOut",
                value: function() {
                    return this._element.querySelectorAll(".slim-result img")
                }
            }, {
                key: "_getDrip",
                value: function() {
                    return this._drip || (this._drip = this._element.querySelector(".slim-drip > span")), this._drip
                }
            }, {
                key: "_throwError",
                value: function(t) {
                    this._addState("error"), this._element.querySelector(".slim-label").style.display = "none";
                    var e = this._element.querySelector(".slim-error");
                    e || (e = b("div", "slim-error"),
                    this._element.querySelector(".slim-status").appendChild(e)), e.innerHTML = t
                }
            }, {
                key: "_removeError",
                value: function() {
                    this._removeState("error"), this._element.querySelector(".slim-label").style.display = "";
                    var t = this._element.querySelector(".slim-error");
                    t && t.parentNode.removeChild(t)
                }
            }, {
                key: "_openFileDialog",
                value: function() {
                    this._removeError(), this._input.click()
                }
            }, {
                key: "_onClick",
                value: function(t) {
                    var e = this,
                        i = t.target.classList,
                        n = t.target;
                    if (i.contains("slim-file-hopper"))
                        return t.preventDefault(), void this._openFileDialog();
                    switch (n.getAttribute("data-action")) {
                    case "remove":
                        this._options.willRemove.apply(this, [this.data, function() {
                            e._doRemove()
                        }]);
                        break;
                    case "edit":
                        this._doEdit();
                        break;
                    case "download":
                        this._doDownload();
                        break;
                    case "upload":
                        this._doUpload()
                    }
                }
            }, {
                key: "_onInvalidFileDrop",
                value: function() {
                    this._onInvalidFile(), this._removeState("file-over");
                    var t = this._getDrip();
                    h(t.firstChild, {
                        fromScale: [.5, .5],
                        scale: [0, 0],
                        fromOpacity: .5,
                        opacity: 0,
                        duration: 150,
                        complete: function() {
                            I(t.firstChild)
                        }
                    })
                }
            }, {
                key: "_onInvalidFile",
                value: function() {
                    var t = this._imageHopper.accept.map(B),
                        e = this._options.statusFileType.replace("$0", t.join(", "));
                    this._throwError(e)
                }
            }, {
                key: "_onImageTooSmall",
                value: function() {
                    var t = this._options.statusImageTooSmall.replace("$0", this._options.minSize.width + " × " + this._options.minSize.height);
                    this._throwError(t)
                }
            }, {
                key: "_onOverWeightFile",
                value: function() {
                    var t = this._options.statusFileSize.replace("$0", this._options.maxFileSize);
                    this._throwError(t)
                }
            }, {
                key: "_onRemoteURLProblem",
                value: function(t) {
                    this._throwError(t)
                }
            }, {
                key: "_onFileOver",
                value: function(t) {
                    this._addState("file-over"), this._removeError();
                    var e = this._getDrip(),
                        i = h.createMatrix();
                    i.translate(t.detail.x, t.detail.y, 0), h.setElementTransform(e, i), 1 == this._imageHopper.dragPath.length && (e.style.opacity = 1, h(e.firstChild, {
                        fromOpacity: 0,
                        opacity: .5,
                        fromScale: [0, 0],
                        scale: [.5, .5],
                        duration: 150
                    }))
                }
            }, {
                key: "_onFileOut",
                value: function(t) {
                    this._removeState("file-over"), this._removeState("file-invalid"), this._removeError();
                    var e = this._getDrip(),
                        i = h.createMatrix();
                    i.translate(t.detail.x, t.detail.y, 0), h.setElementTransform(e, i), h(e.firstChild, {
                        fromScale: [.5, .5],
                        scale: [0, 0],
                        fromOpacity: .5,
                        opacity: 0,
                        duration: 150,
                        complete: function() {
                            I(e.firstChild)
                        }
                    })
                }
            }, {
                key: "_onDropFile",
                value: function(t) {
                    var e = this;
                    this._removeState("file-over");
                    var i = this._getDrip(),
                        n = h.createMatrix();
                    n.translate(t.detail.x, t.detail.y, 0), h.setElementTransform(i, n);
                    var o = this._imageHopper.dragPath.length,
                        r = this._imageHopper.dragPath[o - Math.min(10, o)],
                        s = t.detail.x - r.x,
                        a = t.detail.y - r.y;
                    h(i, {
                        fromPosition: [t.detail.x, t.detail.y, 0],
                        position: [t.detail.x + s, t.detail.y + a, 0],
                        duration: 200
                    }), h(i.firstChild, {
                        fromScale: [.5, .5],
                        scale: [2, 2],
                        fromOpacity: 1,
                        opacity: 0,
                        duration: 200,
                        complete: function() {
                            I(i.firstChild), e._load(t.target.files[0])
                        }
                    })
                }
            }, {
                key: "_onChange",
                value: function(t) {
                    t.target.files.length && this._load(t.target.files[0])
                }
            }, {
                key: "_load",
                value: function(t, e) {
                    var i = this,
                        n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {};
                    if (!this._isBeingDestroyed) {
                        if (this.containsImage())
                            return clearTimeout(this._replaceTimeout), void this._doRemove(function() {
                                i._replaceTimeout = setTimeout(function() {
                                    i._load(t, e, n)
                                }, 100)
                            });
                        this._removeState("empty"), this._addState("busy"), this._startProgressLoop(), this._imageHopper && (this._imageHopper.enabled = !1), clearTimeout(this._loadTimeout);
                        var o = function() {
                                clearTimeout(i._loadTimeout), i._loadTimeout = setTimeout(function() {
                                    i._isBeingDestroyed || (i._addState("loading"), h(i._element.querySelector(".slim-label-loading"), {
                                        fromOpacity: 0,
                                        opacity: 1,
                                        duration: 250
                                    }))
                                }, 500)
                            },
                            r = function() {
                                i._imageHopper && (i._imageHopper.enabled = !0), i._removeState("loading"), i._removeState("busy"), i._addState("empty"), i._stopProgressLoop()
                            };
                        if ("string" == typeof t)
                            return void (G(t) ? this._load($(t), e, n) : (o(), Y(t, this._options.willLoad, function(t) {
                                i._load(t, e, n)
                            })));
                        if ("undefined" != typeof t.remote && this._options.fetcher)
                            return void X(this._options.fetcher, this._options.willFetch, this._options.willLoad, t.remote, function(t) {
                                r(), i._onRemoteURLProblem("<p>" + t + "</p>"), e && e.apply(i, ["remote-url-problem"])
                            }, function(t) {
                                i._load(t, e, n)
                            });
                        var s = t;
                        if (this._imageHopper && this._imageHopper.accept.indexOf(s.type) === -1)
                            return r(), this._onInvalidFile(), void (e && e.apply(this, ["file-invalid"]));
                        if (s.size && this._options.maxFileSize && R(s.size) > this._options.maxFileSize)
                            return r(), this._onOverWeightFile(), void (e && e.apply(this, ["file-too-big"]));
                        this._imageEditor && this._imageEditor.dirty(), this._data.input.name = n && n.name ? n.name : q(s), this._data.input.type = W(s), this._data.input.size = s.size, this._data.input.file = s, K(s, this._options.internalCanvasSize, function(t, o) {
                            var r = function() {
                                i._imageHopper && (i._imageHopper.enabled = !0), i._removeState("loading"), i._removeState("busy"), i._addState("empty"), i._stopProgressLoop(), i._resetData()
                            };
                            if (!t)
                                return r(), void (e && e.apply(i, ["file-not-found"]));
                            if (!ct(t, i._options.minSize))
                                return r(), i._onImageTooSmall(), void (e && e.apply(i, ["image-too-small"]));
                            var a = i._options.didLoad.apply(i, [s, t, o, i]);
                            if (a !== !0)
                                return r(), a !== !1 && i._throwError(a), void (e && e.apply(i, [a]));
                            i._removeState("loading");
                            var u = function(t) {
                                i._imageHopper && i._options.dropReplace && (i._imageHopper.enabled = !0);
                                var e = i._getIntro(),
                                    n = {
                                        fromScale: [1.25, 1.25],
                                        scale: [1, 1],
                                        fromOpacity: 0,
                                        opacity: 1,
                                        complete: function() {
                                            I(e), e.style.opacity = 1, t()
                                        }
                                    };
                                i.isDetached() ? n.duration = 1 : (n.easing = "spring", n.springConstant = .3, n.springDeceleration = .7), i._canInstantEdit() && (n.delay = 500, n.duration = 1, i._doEdit()), h(e, n)
                            };
                            i._loadCanvas(t, function(t) {
                                i._addState("preview"), u(function() {
                                    i._canInstantEdit() || t || i._showButtons(), t || (i._stopProgressLoop(), i._removeState("busy")), e && e.apply(i, [null, i.data])
                                })
                            }, function() {
                                i._canInstantEdit() || i._showButtons(), i._removeState("busy")
                            }, {
                                blockPush: n.blockPush
                            })
                        })
                    }
                }
            }, {
                key: "_loadCanvas",
                value: function(t, e, i, n) {
                    var o = this;
                    if (n || (n = {}), !this._isBeingDestroyed) {
                        this._data.input.image = t, this._data.input.width = t.width, this._data.input.height = t.height, this._initialRotation && (this._data.actions.rotation = this._initialRotation, this._initialRotation = null);
                        var r = this._data.actions.rotation % 180 !== 0;
                        this._isFixedRatio() || (this._initialCrop ? this._ratio = this._initialCrop.height / this._initialCrop.width : this._ratio = r ? t.width / t.height : t.height / t.width, this._scaleDropArea(this._ratio)), this._initialCrop ? (this._data.actions.crop = _t(this._initialCrop), this._data.actions.crop.type = Dt.INITIAL, this._initialCrop = null) : (this._data.actions.crop = Q(r ? t.height : t.width, r ? t.width : t.height, this._ratio), this._data.actions.crop.type = Dt.AUTO), this._options.size && (this._data.actions.size = {
                            width: this._options.size.width,
                            height: this._options.size.height
                        }), this._applyTransforms(t, function(t) {
                            var r = o._getIntro(),
                                s = r.offsetWidth / t.width,
                                a = !1;
                            o._options.service && o._options.push && !n.blockPush && (o._hasInitialImage || o._canInstantEdit() || (a = !0, o._stopProgressLoop(function() {
                                o._startProgress(function() {
                                    o._updateProgress(.1)
                                })
                            }))), o._canInstantEdit() || o._save(function() {
                                o._isBeingDestroyed || a && (o._stopProgress(), i())
                            }, a), r.src = "", r.src = at(t, s).toDataURL(), r.onload = function() {
                                r.onload = null, o._isBeingDestroyed || e && e(a)
                            }
                        })
                    }
                }
            }, {
                key: "_applyTransforms",
                value: function(t, e) {
                    var i = this,
                        n = _t(this._data.actions);
                    n.filters = {
                        sharpen: this._options.filterSharpen / 100
                    }, this._options.forceMinSize ? n.minSize = this._options.minSize : n.minSize = {
                        width: 0,
                        height: 0
                    }, tt(t, n, function(t) {
                        var n = t;
                        if (i._options.forceSize || i._options.size && 1 == rt(i._options.size, t)) {
                            n = b("canvas"), n.width = i._options.size.width, n.height = i._options.size.height;
                            var o = n.getContext("2d");
                            o.drawImage(t, 0, 0, i._options.size.width, i._options.size.height)
                        }
                        if (i._options.forceMinSize && i._options.size && i._options.minSize.width === i._options.size.width && i._options.minSize.height === i._options.size.height && (n.width < i._options.minSize.width || n.height < i._options.minSize.height)) {
                            var r = Math.max(n.width, i._options.minSize.width),
                                s = Math.max(n.height, i._options.minSize.height);
                            n = b("canvas"), n.width = r, n.height = s;
                            var a = n.getContext("2d");
                            a.drawImage(t, 0, 0, r, s)
                        }
                        i._data.output.width = n.width, i._data.output.height = n.height, i._data.output.image = n, i._onTransformCanvas(function(t) {
                            i._data = t, i._options.didTransform.apply(i, [i.data, i]), e(i._data.output.image)
                        })
                    })
                }
            }, {
                key: "_onTransformCanvas",
                value: function(t) {
                    this._options.willTransform.apply(this, [this.data, t, this])
                }
            }, {
                key: "_appendEditor",
                value: function() {
                    var t = this;
                    this._imageEditor || (this._imageEditor = new At(b("div"), {
                        minSize: this._options.minSize,
                        buttonConfirmClassName: this._options.buttonConfirmClassName,
                        buttonCancelClassName: this._options.buttonCancelClassName,
                        buttonRotateClassName: this._options.buttonRotateClassName,
                        buttonConfirmLabel: this._options.buttonConfirmLabel,
                        buttonCancelLabel: this._options.buttonCancelLabel,
                        buttonRotateLabel: this._options.buttonRotateLabel,
                        buttonConfirmTitle: this._options.buttonConfirmTitle,
                        buttonCancelTitle: this._options.buttonCancelTitle,
                        buttonRotateTitle: this._options.buttonRotateTitle
                    }), Ut.forEach(function(e) {
                        t._imageEditor.element.addEventListener(e, t)
                    }))
                }
            }, {
                key: "_scaleDropArea",
                value: function(t) {
                    var e = this._getRatioSpacerElement();
                    e && this._element && (e.style.marginBottom = 100 * t + "%", this._element.setAttribute("data-ratio", "1:" + t))
                }
            }, {
                key: "_onCancel",
                value: function(t) {
                    this._removeState("editor"), this._options.didCancel.apply(this, [this]), this._showButtons(), this._hideEditor(), this._options.instantEdit && !this._hasInitialImage && this._isAutoCrop() && this._doRemove()
                }
            }, {
                key: "_onConfirm",
                value: function(t) {
                    var e = this,
                        i = this._options.service && this._options.push;
                    i ? this._startProgress(function() {
                        e._updateProgress(.1)
                    }) : this._startProgressLoop(), this._removeState("editor"), this._addState("busy"), this._output.value = "", this._data.actions.rotation = t.detail.rotation, this._data.actions.crop = t.detail.crop, this._data.actions.crop.type = Dt.MANUAL, this._applyTransforms(this._data.input.image, function(t) {
                        e._options.didConfirm.apply(e, [e.data, e]);
                        var n = e._getInOut(),
                            o = "out" === n[0].className ? n[0] : n[1],
                            r = o === n[0] ? n[1] : n[0];
                        o.className = "in", o.style.opacity = "0", o.style.zIndex = "2", r.className = "out", r.style.zIndex = "1", o.src = "", o.src = at(t, o.offsetWidth / t.width).toDataURL(), o.onload = function() {
                            o.onload = null, "free" === e._options.ratio && (e._ratio = o.naturalHeight / o.naturalWidth, e._scaleDropArea(e._ratio)), e._hideEditor();
                            var t = setTimeout(function() {
                                e._showPreview(o, function() {
                                    e._save(function(t, n, o) {
                                        e._toggleButton("upload", !0), i ? e._stopProgress() : e._stopProgressLoop(), e._removeState("busy"), e._showButtons()
                                    }, i)
                                })
                            }, 250);
                            e._timers.push(t)
                        }
                    })
                }
            }, {
                key: "_cropAuto",
                value: function() {
                    var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : function(t) {},
                        e = this._data.actions.rotation % 180 !== 0,
                        i = Q(e ? this._data.input.image.width : this._data.input.image.height, e ? this._data.input.image.height : this._data.input.image.width, this._ratio);
                    this._crop(i.x, i.y, i.width, i.height, t, Dt.AUTO)
                }
            }, {
                key: "_crop",
                value: function(t, e, i, n) {
                    var o = arguments.length > 4 && void 0 !== arguments[4] ? arguments[4] : function(t) {},
                        r = arguments.length > 5 && void 0 !== arguments[5] ? arguments[5] : Dt.MANUAL;
                    this._output.value = "", this._data.actions.crop = {
                        x: t,
                        y: e,
                        width: i,
                        height: n
                    }, this._data.actions.crop.type = r, this._manualTransform(o)
                }
            }, {
                key: "_manualTransform",
                value: function(t) {
                    var e = this;
                    this._startProgressLoop(), this._addState("busy"), this._applyTransforms(this._data.input.image, function(i) {
                        var n = e._getInOut(),
                            o = "out" === n[0].className ? n[0] : n[1],
                            r = o === n[0] ? n[1] : n[0];
                        o.className = "in", o.style.opacity = "1", o.style.zIndex = "2", r.className = "out", r.style.zIndex = "0", o.src = "", o.src = at(i, o.offsetWidth / i.width).toDataURL(), o.onload = function() {
                            o.onload = null, "free" === e._options.ratio && (e._ratio = o.naturalHeight / o.naturalWidth, e._scaleDropArea(e._ratio));
                            var i = e._options.service && e._options.push,
                                n = function() {
                                    e._save(function(n, o, r) {
                                        i || e._stopProgressLoop(), e._removeState("busy"), t.apply(e, [e.data])
                                    }, i)
                                };
                            i ? e._startProgress(n) : n()
                        }
                    })
                }
            }, {
                key: "_save",
                value: function() {
                    var t = this,
                        e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : function() {},
                        i = !(arguments.length > 1 && void 0 !== arguments[1]) || arguments[1];
                    if (!this._isBeingDestroyed) {
                        var n = this.dataBase64;
                        this._options.service || this._isInitialising && !this._isImageOnly() || this._options.willSave.apply(this, [n, function(e) {
                            t._store(e), t._options.didSave.apply(t, [e, t])
                        }, this]), this._isBeingDestroyed || (this._options.service && i && this._options.willSave.apply(this, [n, function(i) {
                            t._addState("upload"), t._imageHopper && t._options.dropReplace && (t._imageHopper.enabled = !1), t._upload(i, function(n, o) {
                                t._imageHopper && t._options.dropReplace && (t._imageHopper.enabled = !0), n || t._storeServerResponse(o), t._options.didUpload.apply(t, [n, i, o, t]), t._removeState("upload"), e(n, i, o)
                            })
                        }, this]), this._options.service && i || e())
                    }
                }
            }, {
                key: "_storeServerResponse",
                value: function(t) {
                    this._isRequired && (this._input.required = !1), this._data.server = t, this._output.value = "object" === ("undefined" == typeof t ? "undefined" : c(t)) ? JSON.stringify(this._data.server) : t
                }
            }, {
                key: "_store",
                value: function(t) {
                    this._isRequired && (this._input.required = !1), this._output.value = JSON.stringify(t)
                }
            }, {
                key: "_upload",
                value: function(t, e) {
                    var i = this;
                    this.requestOutput(function(t, n) {
                        var o = i._element.querySelector(".slim-upload-status"),
                            r = i._options.willRequest,
                            s = function(t, e) {
                                i._updateProgress(Math.max(.1, t / e))
                            },
                            a = function(t) {
                                var n = setTimeout(function() {
                                    if (!i._isBeingDestroyed) {
                                        o.innerHTML = i._options.statusUploadSuccess, o.setAttribute("data-state", "success"), o.style.opacity = 1;
                                        var t = setTimeout(function() {
                                            o.style.opacity = 0
                                        }, 2e3);
                                        i._timers.push(t)
                                    }
                                }, 250);
                                i._timers.push(n), e(null, t)
                            },
                            h = function(t) {
                                var n = "";
                                n = "file-too-big" === t ? i._options.statusContentLength : i._options.didReceiveServerError.apply(i, [t, i._options.statusUnknownResponse, i]);
                                var r = setTimeout(function() {
                                    o.innerHTML = n, o.setAttribute("data-state", "error"), o.style.opacity = 1
                                }, 250);
                                i._timers.push(r), e(t)
                            };
                        "string" == typeof i._options.service ? O(i._options.service, n, r, s, a, h) : "function" == typeof i._options.service && i._options.service.apply(i, ["file" === i._options.serviceFormat ? t : n, s, a, h])
                    }, t)
                }
            }, {
                key: "requestOutput",
                value: function(t, e) {
                    var n = this;
                    return this._data.input.file ? (e || (e = this.dataBase64), void i.parseMetaData(this._data.input.file, function(o) {
                        var r = [],
                            s = new FormData;
                        if (L("input", n._options.post) && (r.push(n._data.input.file), s.append(n._inputReference, n._data.input.file, n._data.input.file.name)), L("output", n._options.post) && null !== n._data.output.image && n._options.uploadBase64 === !1) {
                            var a = $(e.output.image, e.output.name);
                            if (o.imageHead && n._options.copyImageHead)
                                try {
                                    a = new Blob([o.imageHead, i.blobSlice.call(a, 20)], {
                                        type: yt(e.output.image)
                                    }), a = V(a, e.output.name)
                                } catch (t) {}
                            r.push(a);
                            var h = "slim_output_" + n._uid;
                            e.output.image = null, e.output.field = h, s.append(h, a, e.output.name)
                        }
                        s.append(n._output.name, JSON.stringify(e)), t(r, s)
                    }, {
                        maxMetaDataSize: 262144,
                        disableImageHead: !1
                    })) : void t(null, null)
                }
            }, {
                key: "_showEditor",
                value: function() {
                    Ht.className = this._options.popoverClassName, Ht.show(), this._imageEditor.show()
                }
            }, {
                key: "_hideEditor",
                value: function() {
                    this._imageEditor.hide();
                    var t = setTimeout(function() {
                        Ht.hide()
                    }, 250);
                    this._timers.push(t)
                }
            }, {
                key: "_showPreview",
                value: function(t, e) {
                    h(t, {
                        fromPosition: [0, 50, 0],
                        position: [0, 0, 0],
                        fromScale: [1.5, 1.5],
                        scale: [1, 1],
                        fromOpacity: 0,
                        opacity: 1,
                        easing: "spring",
                        springConstant: .3,
                        springDeceleration: .7,
                        complete: function() {
                            I(t), e && e()
                        }
                    })
                }
            }, {
                key: "_hideResult",
                value: function(t) {
                    var e = this._getIntro();
                    e && h(e, {
                        fromScale: [1, 1],
                        scale: [.5, .5],
                        fromOpacity: 1,
                        opacity: 0,
                        easing: "spring",
                        springConstant: .3,
                        springDeceleration: .75,
                        complete: function() {
                            I(e), t && t()
                        }
                    })
                }
            }, {
                key: "_showButtons",
                value: function(t) {
                    if (this._btnGroup) {
                        this._btnGroup.style.display = "";
                        var e = {
                            fromScale: [.5, .5],
                            scale: [1, 1],
                            fromPosition: [0, 10, 0],
                            position: [0, 0, 0],
                            fromOpacity: 0,
                            opacity: 1,
                            complete: function() {
                                I(this)
                            },
                            allDone: function() {
                                t && t()
                            }
                        };
                        this.isDetached() ? e.duration = 1 : (e.delay = function(t) {
                            return 250 + 50 * t
                        }, e.easing = "spring", e.springConstant = .3, e.springDeceleration = .85), h(this._btnGroup.childNodes, e)
                    }
                }
            }, {
                key: "_hideButtons",
                value: function(t) {
                    var e = this;
                    if (this._btnGroup) {
                        var i = {
                            fromScale: [1, 1],
                            scale: [.85, .85],
                            fromOpacity: 1,
                            opacity: 0,
                            allDone: function() {
                                e._btnGroup.style.display = "none", t && t()
                            }
                        };
                        this.isDetached() ? i.duration = 1 : (i.easing = "spring", i.springConstant = .3, i.springDeceleration = .75), h(this._btnGroup.childNodes, i)
                    }
                }
            }, {
                key: "_hideStatus",
                value: function() {
                    var t = this._element.querySelector(".slim-upload-status");
                    t.style.opacity = 0
                }
            }, {
                key: "_doEdit",
                value: function() {
                    var t = this;
                    this._data.input.image && (this._addState("editor"), this._imageEditor || this._appendEditor(), this._imageEditor.showRotateButton = this._options.rotateButton, Ht.inner = this._imageEditor.element, this._imageEditor.open(st(this._data.input.image), "free" === this._options.ratio ? null : this._ratio, this._data.actions.crop, this._data.actions.rotation, function() {
                        t._showEditor(), t._hideButtons(), t._hideStatus()
                    }))
                }
            }, {
                key: "_doRemove",
                value: function(t) {
                    var e = this;
                    if (!this._isImageOnly()) {
                        this._clearState(), this._addState("empty"), this._hasInitialImage = !1, this._imageHopper && (this._imageHopper.enabled = !0), this._isRequired && (this._input.required = !0);
                        var i = this._getOutro();
                        i && (i.style.opacity = "0");
                        var n = this.data;
                        this._resetData();
                        var o = setTimeout(function() {
                            e._isBeingDestroyed || (e._hideButtons(function() {
                                e._toggleButton("upload", !0)
                            }), e._hideStatus(), e._hideResult(), e._options.didRemove.apply(e, [n, e]), t && t())
                        }, this.isDetached() ? 0 : 250);
                        return this._timers.push(o), n
                    }
                }
            }, {
                key: "_doUpload",
                value: function(t) {
                    var e = this;
                    this._data.input.image && (this._addState("upload"), this._startProgress(), this._hideButtons(function() {
                        e._toggleButton("upload", !1), e._save(function(i, n, o) {
                            e._removeState("upload"), e._stopProgress(), t && t.apply(e, [i, n, o]), i && e._toggleButton("upload", !0), e._showButtons()
                        })
                    }))
                }
            }, {
                key: "_doDownload",
                value: function() {
                    var t = this._data.output.image;
                    t && bt(this._data, this._options.jpegCompression, this._options.forceType)
                }
            }, {
                key: "_doDestroy",
                value: function() {
                    function t(t, e) {
                        return 0 !== e.filter(function(e) {
                            return t.name === e.name && t.value === e.value
                        }).length
                    }
                    var e = this;
                    this._isBeingDestroyed = !0, this._timers.forEach(function(t) {
                        clearTimeout(t)
                    }), this._timers = [], h(this._element, "detach"), this._imageHopper && (Bt.forEach(function(t) {
                        e._imageHopper.element.removeEventListener(t, e)
                    }), this._imageHopper.destroy(), this._imageHopper = null), this._imageEditor && (Ut.forEach(function(t) {
                        e._imageEditor.element.removeEventListener(t, e)
                    }), this._imageEditor.destroy(), this._imageEditor = null), kt(this._btnGroup.children).forEach(function(t) {
                        t.removeEventListener("click", e)
                    }), this._input.removeEventListener("change", this), this._element !== this._originalElement && this._element.parentNode && this._element.parentNode.replaceChild(this._originalElement, this._element), this._originalElement.innerHTML = this._originalElementInner;
                    var i = f(this._originalElement);
                    i.forEach(function(i) {
                        t(i, e._originalElementAttributes) || e._originalElement.removeAttribute(i.name)
                    }), this._originalElementAttributes.forEach(function(n) {
                        t(n, i) || e._originalElement.setAttribute(n.name, n.value)
                    }), Ft = Math.max(0, Ft - 1), Ht && 0 === Ft && (Ht.destroy(), Ht = null), this._originalElement = null, this._element = null, this._input = null, this._output = null, this._btnGroup = null, this._options = null
                }
            }, {
                key: "dataBase64",
                get: function() {
                    return wt(this._data, this._options.post, this._options.jpegCompression, this._options.forceType, null !== this._options.service)
                }
            }, {
                key: "data",
                get: function() {
                    return gt(this._data)
                }
            }, {
                key: "element",
                get: function() {
                    return this._element
                }
            }, {
                key: "service",
                set: function(t) {
                    this._options.service = t
                }
            }, {
                key: "size",
                set: function(t) {
                    this.setSize(t, null)
                }
            }, {
                key: "rotation",
                set: function(t) {
                    this.setRotation(t, null)
                }
            }, {
                key: "ratio",
                set: function(t) {
                    this.setRatio(t, null)
                }
            }], [{
                key: "options",
                value: function() {
                    var t = {
                        edit: !0,
                        instantEdit: !1,
                        uploadBase64: !1,
                        meta: {},
                        ratio: "free",
                        size: null,
                        rotation: null,
                        crop: null,
                        post: ["output", "actions"],
                        service: null,
                        serviceFormat: null,
                        filterSharpen: 0,
                        push: !1,
                        defaultInputName: "slim[]",
                        minSize: {
                            width: 0,
                            height: 0
                        },
                        maxFileSize: null,
                        jpegCompression: null,
                        download: !1,
                        saveInitialImage: !1,
                        forceType: !1,
                        forceSize: null,
                        forceMinSize: !0,
                        dropReplace: !0,
                        fetcher: null,
                        internalCanvasSize: {
                            width: 4096,
                            height: 4096
                        },
                        copyImageHead: !1,
                        rotateButton: !0,
                        popoverClassName: null,
                        label: "<p>Drop your image here</p>",
                        labelLoading: "<p>Loading image...</p>",
                        statusFileType: "<p>Invalid file type, expects: $0.</p>",
                        statusFileSize: "<p>File is too big, maximum file size: $0 MB.</p>",
                        statusNoSupport: "<p>Your browser does not support image cropping.</p>",
                        statusImageTooSmall: "<p>Image is too small, minimum size is: $0 pixels.</p>",
                        statusContentLength: '<span class="slim-upload-status-icon"></span> The file is probably too big',
                        statusUnknownResponse: '<span class="slim-upload-status-icon"></span> An unknown error occurred',
                        statusUploadSuccess: '<span class="slim-upload-status-icon"></span> Saved',
                        didInit: function(t) {},
                        didLoad: function(t, e, i) {
                            return !0
                        },
                        didSave: function(t) {},
                        didUpload: function(t, e, i) {},
                        didReceiveServerError: function(t, e) {
                            return e
                        },
                        didRemove: function(t) {},
                        didTransform: function(t) {},
                        didConfirm: function(t) {},
                        didCancel: function() {},
                        willTransform: function(t, e) {
                            e(t)
                        },
                        willSave: function(t, e) {
                            e(t)
                        },
                        willRemove: function(t, e) {
                            e()
                        },
                        willRequest: function(t, e) {},
                        willFetch: function(t) {},
                        willLoad: function(t) {}
                    };
                    return Nt.concat(At.Buttons).concat("rotate").forEach(function(e) {
                        var i = T(e);
                        t["button" + i + "ClassName"] = null, t["button" + i + "Label"] = i, t["button" + i + "Title"] = i
                    }), t
                }
            }]), e
        }();
    return function() {
        function t(t) {
            return t ? "<p>" + t + "</p>" : null
        }
        function e(t) {
            var e = window,
                i = t.split(".");
            return i.forEach(function(t, n) {
                e[i[n]] && (e = e[i[n]])
            }), e !== window ? e : null
        }
        var i = [],
            n = function(t) {
                for (var e = 0, n = i.length; e < n; e++)
                    if (i[e].isAttachedTo(t))
                        return e;
                return -1
            },
            o = function(t) {
                return t
            },
            r = function(t) {
                return "true" === t
            },
            s = function(t) {
                return !t || "true" === t
            },
            a = function(e) {
                return t(e)
            },
            h = function(t) {
                return t ? e(t) : null
            },
            u = function(t) {
                if (!t)
                    return null;
                var e = It(t, ",");
                return {
                    width: e[0],
                    height: e[1]
                }
            },
            l = function(t) {
                return t ? parseFloat(t) : null
            },
            c = function(t) {
                return t ? parseInt(t, 10) : null
            },
            p = function(t) {
                if (!t)
                    return null;
                var e = {};
                return t.split(",").map(function(t) {
                    return parseInt(t, 10)
                }).forEach(function(t, i) {
                    e[zt[i]] = t
                }), e
            },
            f = {
                download: r,
                edit: s,
                instantEdit: r,
                minSize: u,
                size: u,
                forceSize: u,
                forceMinSize: s,
                internalCanvasSize: u,
                service: function(t) {
                    if ("undefined" == typeof t)
                        return null;
                    var i = e(t);
                    return i ? i : t
                },
                serviceFormat: function(t) {
                    return "undefined" == typeof t ? null : t
                },
                fetcher: function(t) {
                    return "undefined" == typeof t ? null : t
                },
                push: r,
                rotation: function(t) {
                    return "undefined" == typeof t ? null : parseInt(t, 10)
                },
                crop: p,
                post: function(t) {
                    return t ? t.split(",").map(function(t) {
                        return t.trim()
                    }) : null
                },
                defaultInputName: o,
                ratio: function(t) {
                    return t ? t : null
                },
                maxFileSize: l,
                filterSharpen: c,
                jpegCompression: c,
                uploadBase64: r,
                forceType: o,
                dropReplace: s,
                saveInitialImage: r,
                copyImageHead: r,
                rotateButton: s,
                label: a,
                labelLoading: a,
                popoverClassName: o
            };
        ["FileSize", "FileType", "NoSupport", "ImageTooSmall"].forEach(function(t) {
            f["status" + t] = a
        }), ["ContentLength", "UnknownResponse", "UploadSuccess"].forEach(function(t) {
            f["status" + t] = o
        }), ["Init", "Load", "Save", "Upload", "Remove", "Transform", "ReceiveServerError", "Confirm", "Cancel"].forEach(function(t) {
            f["did" + t] = h
        }), ["Transform", "Save", "Remove", "Request", "Load", "Fetch"].forEach(function(t) {
            f["will" + t] = h
        });
        var _ = ["ClassName", "Label", "Title"];
        Nt.concat(At.Buttons).concat("rotate").forEach(function(t) {
            var e = T(t);
            _.forEach(function(t) {
                f["button" + e + t] = o
            })
        }), Vt.supported = function() {
            return !("[object OperaMini]" === Object.prototype.toString.call(window.operamini) || "undefined" == typeof window.addEventListener || "undefined" == typeof window.FileReader || !("slice" in Blob.prototype) || "undefined" == typeof window.URL || "undefined" == typeof window.URL.createObjectURL)
        }(), Vt.parse = function(t) {
            var e,
                i,
                n,
                o = [];
            for (e = t.querySelectorAll(".slim:not([data-state])"), n = e.length; n--;)
                i = e[n], o.push(Vt.create(i, Vt.getOptionsFromAttributes(i)));
            return o
        }, Vt.getOptionsFromAttributes = function(t) {
            var e = d(t),
                i = {
                    meta: {}
                };
            for (var n in e) {
                var o = f[n],
                    r = e[n];
                o ? (r = o(r), r = null === r ? _t(Vt.options()[n]) : r, i[n] = r) : 0 === n.indexOf("meta") && (i.meta[M(n.substr(4))] = r)
            }
            return i
        }, Vt.find = function(t) {
            var e = i.filter(function(e) {
                return e.isAttachedTo(t)
            });
            return e ? e[0] : null
        }, Vt.create = function(t, e) {
            if (!Vt.find(t)) {
                e || (e = Vt.getOptionsFromAttributes(t));
                var n = new Vt(t, e);
                return i.push(n), n
            }
        }, Vt.destroy = function(t) {
            var e = n(t);
            return !(e < 0) && (i[e].destroy(), i.splice(e, 1), !0)
        }
    }(), Vt
}), define("ui/Demo", ["slim"], function(t) {
    "use strict";
    var e = function(e) {
        if (!t.supported) {
            var i = e.querySelector(".slim");
            return i.classList.remove("slim"), void (i.innerHTML = '<p class="slim-not-supported">This browser does not have the required feature set to run Slim.</p>')
        }
        t.parse(e)
    };
    return e
}), define("ui/Switcher", [], function() {
    "use strict";
    var t = function(t, e) {
        this._element = t, this._options = e, this._init()
    };
    return t.options = {
        toggleClass: null
    }, t.prototype = {
        _init: function() {
            this._options.toggleClass && this._element.classList.add(this._options.toggleClass)
        },
        unload: function() {
            this._element.classList.remove(this._options.toggleClass)
        }
    }, t
}), define("ui/Tabs", [], function() {
    "use strict";
    var t = function(t, e) {
        this._element = t, this._options = e, this._init()
    };
    return t.options = {
        defaultTab: null
    }, t.prototype = {
        _init: function() {
            this._element.setAttribute("role", "tablist"), this._getTabPanels().forEach(function(t) {
                t.setAttribute("role", "tabpanel")
            }), this._getTabs().forEach(function(t) {
                t.setAttribute("role", "tab")
            }), this._update(location.hash.substr(1)), this._element.addEventListener("click", function(t) {
                var e = t.target.getAttribute("href");
                e && (window.location.hash = e, t.preventDefault())
            }), window.addEventListener("hashchange", this)
        },
        handleEvent: function(t) {
            t.preventDefault(), this._update(location.hash.substr(1))
        },
        _getTabs: function() {
            return [].slice.call(this._element.getElementsByTagName("a"))
        },
        _getTabPanels: function() {
            var t = this._getTabs();
            return t.map(function(t) {
                return t.getAttribute("href")
            }).map(function(t) {
                return document.getElementById(t.substr(1))
            })
        },
        _update: function(t) {
            if (t) {
                this._getTabs().forEach(function(t) {
                    t.setAttribute("aria-selected", "false")
                }), this._getTabPanels().forEach(function(t) {
                    t.style.display = "none"
                });
                var e = this._element.querySelector('a[href="#' + t + '"]');
                if (!e && this._options.defaultTab)
                    return void this._update(this._options.defaultTab);
                e.setAttribute("aria-selected", "true"), document.getElementById(t).style.display = "block"
            } else if (this._options.defaultTab)
                return void this._update(this._options.defaultTab)
        }
    }, t
}), define("ui/ScrollReveal", [], function() {
    var t = function(t, e) {
        this._element = t, this._element.dataset.state = "hidden", this._init()
    };
    return t.prototype = {
        _init: function() {
            var t = this;
            window.addEventListener("scroll", function() {
                t._test()
            }), this._test()
        },
        _test: function() {
            window.scrollY > window.innerHeight ? this._reveal() : this._conceal()
        },
        _conceal: function() {
            this._element.dataset.state = "concealed"
        },
        _reveal: function() {
            this._element.dataset.state = "revealed"
        }
    }, t
});

