import type { Meta, UploadResult, UppyFile } from "@uppy/core";
import { ofetch } from "ofetch";

type FileOption = {
  path: string
  disk?: string
}

type PostFileOptions = {
  from: FileOption
  to: FileOption
}

export async function copyFile(result: UploadResult<Meta, Record<string, never>>, getOptions: (file: UppyFile<Meta, Record<string, never>>) => PostFileOptions) {
  return postFile('/files/copy', result, getOptions)
}

export async function moveFile(result: UploadResult<Meta, Record<string, never>>, getOptions: (file: UppyFile<Meta, Record<string, never>>) => PostFileOptions) {
  return postFile('/files/move', result, getOptions)
}

export async function postFile(url: string, result: UploadResult<Meta, Record<string, never>>, getOptions: (file: UppyFile<Meta, Record<string, never>>) => PostFileOptions) {
  if (!result.successful) {
    throw new Error("There no successful uploaded files to move");
  }

  const files = result.successful.map(file => {
    return getOptions(file)
  })

  const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
  const response = await ofetch(url, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
    },
    body: { files }
  })

  return response
}