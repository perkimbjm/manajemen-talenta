import type { Alpine } from 'alpinejs';

import useUppyCore from './core';
import useUppyEventListener from './event-listener';

export default function useUppy(alpine: Alpine) {
  useUppyCore(alpine)
  useUppyEventListener(alpine)
}