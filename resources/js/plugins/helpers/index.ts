import type { Alpine } from "alpinejs";
import resize from '@alpinejs/resize'

export function dom(domId?: string | null) {
  if (!domId) return
  return document.getElementById(domId)
}

export function bool(something: unknown) {
  return Boolean(something).toString()
}

export function text(something: unknown) {
  return String(something)
    .replace(/"/g, "")  // Double quote
    .replace(/'/g, "")   // Single quote
    .replace(/`/g, "");  // Backtick
}

export function slug(text: string): string {
  return text
    .toLowerCase() // Convert to lowercase
    .trim() // Remove whitespace from both ends
    .normalize('NFD') // Normalize to remove diacritics (accents, etc.)
    // .replace(/[\u0300-\u036f]/g, '') // Remove diacritics
    .replace(/[^a-z0-9\s-]/g, '') // Remove non-alphanumeric characters except spaces and hyphens
    .replace(/\s+/g, '-') // Replace spaces with hyphens
    .replace(/-+/g, '-') // Merge multiple hyphens into one
    .replace(/^-|-$/g, ''); // Remove leading and trailing hyphens
}


export function parseFormData(formData: FormData) {
  const result: Record<string, unknown> = {};

  // Iterate through each key-value pair in the FormData
  for (const [key, value] of formData.entries()) {
    const keys = key.match(/[^\[\]]+/g); // Extract the keys from the formData key

    if (keys) {
      keys.reduce((acc: Record<string, unknown>, cur: string, index: number) => {
        // If it's the last key, assign the value
        if (index === keys.length - 1) {
          acc[cur] = value;
        } else {
          // Otherwise, create a nested object if it doesn't exist
          acc[cur] = acc[cur] || {};
        }
        return acc[cur] as Record<string, unknown>;
      }, result);
    }
  }

  return result;
}

export function dashToCamel(dashCase: string): string {
  return dashCase.replace(/-([a-z])/g, (match, letter) => letter.toUpperCase());
}

export function camelToDash(camelCase: string): string {
  return camelCase.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
}

export function dashToPascal(dashCase: string): string {
  return dashCase
    // Remove dash and replace following letter with uppercase letter
    .replace(/-([a-z])/g, (match, letter) => letter.toUpperCase())
    // Capitalize the first letter of the entire string
    .replace(/^./, (firstLetter) => firstLetter.toUpperCase());
}

export default function AlpineHelpers(alpine: Alpine) {
  alpine.magic("dom", () => {
    return (domId: string) => document.getElementById(domId);
  });

  alpine.magic("timeout", () => {
    return (timeout: number) => new Promise(resolve => setTimeout(resolve, timeout));
  });

  alpine.magic("bool", () => {
    return bool
  });

  alpine.magic("text", () => {
    return text
  });

  alpine.magic("slug", () => {
    return slug
  });

  alpine.magic("quote", () => {
    return (text: string, data: { [x: string]: unknown }) => {
      const templateRegex = /\[\{([^}]+)\}\]/g;
      console.log("🚀 ~ templateRegex:", data)
      return text.replace(
        templateRegex,
        (match: unknown, key: string | number) => {
          return `"${data[key]}"`;
        },
      );
    };
  });

  const locationStore = {
    href: window.location.href,
    url: `${window.location.origin}${window.location.pathname}`,
    params: new URLSearchParams(window.location.search),
    init() {
      document.addEventListener('livewire:navigated', () => {
        this.refresh()
      })

      document.addEventListener('urlchange', () => {
        this.refresh()
      })
    },
    refresh() {
      this.href = window.location.href
      this.url = `${window.location.origin}${window.location.pathname}`
      this.params = new URLSearchParams(window.location.search)
    }
  }

  alpine.store('location', locationStore)

  alpine.magic('parseFormData', () => {
    return parseFormData
  })

  alpine.plugin(resize)
}