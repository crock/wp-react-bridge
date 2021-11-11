import axios from 'axios'

export default function useWordpress() {

    const http = axios.create({
        baseURL: window.wpApiSettings.root,
        timeout: 1000,
        headers: {'X-WP-Nonce': window.wpApiSettings.nonce}
    });

    const coreApiVersion = 'v2'

    const core = async (resourcePath, method, params = null, data = null) => {
        const url = `/wp/${coreApiVersion}/${resourcePath.startsWith('/') ? resourcePath.slice(1) : resourcePath}`

        const response = await http.request({
            method,
            url,
            params,
            data
        })

        return response.data
    }

    const vendor = async (vendorApiPrefix, vendorApiVersion, resourcePath, method, params = null, data = null) => {
        const url = `/${vendorApiPrefix}/${vendorApiVersion}/${resourcePath.startsWith('/') ? resourcePath.slice(1) : resourcePath}`

        const response = await http.request({
            method,
            url,
            params,
            data
        })

        return response.data
    }

    return {
        http,
        resources : {
            core,
            vendor
        }
    }
}