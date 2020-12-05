class Client {
    baseUrl = 'https://your-public-host.com';

    getProviders() {
        return this.callMethod('getProviders');
    }

    setProviderStatus(providerId, status) {
        return this.callMethod('setProviderStatus', {providerId: providerId, status: status});
    }

    getCall(sid) {
        return this.callMethod('getCall', {sid: sid});
    }

    callMethod(method, params) {
        let url = new URL(this.baseUrl + '/api/' + method);
        let encodedParams = this.joinQueryString(params || {});

        return fetch(
            url.href,
            {
                method:     'POST',
                headers:    {'Content-Type': 'application/x-www-form-urlencoded'},
                body:       encodedParams
            }
        ).then(response => response.json());
    }

    joinQueryString(params) {
        return Object.keys(params).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(params[key])).join('&');
    }
}

export default Client;
