import type { Alpine } from "alpinejs";

// Actual fetch function
async function xfetch(url: URL, method = 'GET') {
  return fetch(url, { method: method })
    .catch((error) => {
      console.log(error)
      return null
    });
}

export function AlpineFetch(alpine: Alpine) {
  alpine.magic('fetchjson', () => {
    return async (
      url: URL,
      jsonItem = null,
      method = "GET"
    ) => {
      const response = await xfetch(url, method)
      const json = await response?.json()

      if (jsonItem) {
        return json[jsonItem]
      }

      return json
    }
  })

  alpine.magic('fetch', () => {
    return async (
      url: URL,
      method = "GET"
    ) => {
      const response = await xfetch(url, method)
      return response?.text();
    }
  })

}