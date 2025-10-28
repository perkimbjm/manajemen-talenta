import Uppy, { type Body, type Meta, type UppyOptions } from '@uppy/core';
import Dashboard from '@uppy/dashboard';
import Tus, { type TusOptions } from '@uppy/tus';
import type { Alpine } from 'alpinejs';

import '@uppy/core/dist/style.min.css';
import '@uppy/dashboard/dist/style.min.css';
import { copyFile, moveFile } from './helpers';

export default function useUppyCore(alpine: Alpine) {
  alpine.directive('uppy', (el, { expression, value }, { evaluate, cleanup }) => {
    const cleanups: Array<() => void> = []

    // biome-ignore lint/suspicious/noExplicitAny: <explanation>
    const options = expression ? evaluate(expression) as Record<string, any> : undefined
    if (options && typeof options !== 'object') {
      throw new Error('Uppy directive requires an uppy options (object) as an expression')
    }

    let data = alpine.closestDataStack(el).at(0) as {
      $uppy?: Uppy
      $moveFile?: typeof moveFile
      $copyFile?: typeof copyFile
    }

    if (!data) {
      data = {
        $uppy: new Uppy(options as UppyOptions<Meta, Record<string, never>>),
      }

      alpine.addScopeToNode(el, data, el.parentElement || undefined)
    } else if (!value) {
      data.$uppy?.destroy()
      data.$uppy = new Uppy(options as UppyOptions<Meta, Record<string, never>>)
    }

    if (!data.$uppy) {
      throw new Error('Uppy:plugin directive requires an uppy instance')
    }

    data.$moveFile = moveFile
    data.$copyFile = copyFile

    cleanups.unshift(() => {
      data.$uppy?.destroy()
    })

    const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    const headers = {
      'X-CSRF-TOKEN': csrfToken,
      ...(options?.headers || {})
    }

    switch (value) {
      case 'dashboard':
        if (!data.$uppy.getPlugin('Dashboard')) {
          data.$uppy.use(Dashboard, options)
        }
        break;

      case 'tus':
        if (!data.$uppy.getPlugin('Tus')) {
          data.$uppy.use(Tus, {
            endpoint: import.meta.env.VITE_TUS_ENDPOINT,
            chunkSize: 1024 * 1024 * 8,
            ...options,
            headers,
          } as TusOptions<Meta, Body>);
        }
        break;

      default:
        break;
    }

    cleanup(() => {
      cleanups.map(fn => fn())
    })

  })
}