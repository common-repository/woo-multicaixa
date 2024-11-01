(() => {
    "use strict";
    const e = window.wc.wcBlocksRegistry,
        t = window.wp.i18n,
        n = window.wc.wcSettings,
        o = window.wp.htmlEntities,
        a = (0, n.getSetting)("multicaixa_proxypay_data", {}),
        c = (0, t.__)("Pagamento por Referência no Multicaixa (ProxyPay)", "woo-multicaixa"),
        l = (0, o.decodeEntities)(a.title) || c,
        i = (e) => React.createElement("div", null, (0, o.decodeEntities)(a.description || "")),
        r = {
            name: "multicaixa_proxypay",
            label: React.createElement((e) => {
                var t = React.createElement("img", { src: a.icon, width: 24, height: 24, style: { display: "inline" } });
                return React.createElement("span", { className: "wc-block-components-payment-method-label wc-block-components-payment-method-label--with-icon" }, t, (0, o.decodeEntities)(a.title) || c);
            }, null),
            content: React.createElement(i, null),
            edit: React.createElement(i, null),
            icons: null,
            canMakePayment: (e) => {
                if ("AOA" != e.cartTotals.currency_code) return !1;
                if (a.only_angola && "AO" != e.billingData.country && "AO" != e.shippingAddress.country) return !1;
                var t = e.cartTotals.total_price / 100;
                return !((a.only_above && t < a.only_above) || (a.only_bellow && t > a.only_bellow));
            },
            ariaLabel: l,
            supports: { features: a.supports },
        };
    (0, e.registerPaymentMethod)(r);
})();
