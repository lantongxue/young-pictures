import FileType from './file-type'
import BMF from 'browser-md5-file'

const bmf = new BMF()

export default class {
  constructor (file) {
    this.file = file
    this.type = ''
    this.preview = ''
    this.progress = 0
    this.progressState = '等待上传'
    this.hash = '' // 计算出当前文件的 hash 值
    this.state = 'wait' // 当前状态：wait、uploading、success、error
  }
  get sizeFormat () {
    if (this.file.size < 1024) {
      return `${this.file.size}字节`
    }
    let size = (this.file.size / 1024).toFixed(2)
    if (size < 1024) {
      return `${size}KB`
    }
    size = (this.file.size / 1024 / 1024).toFixed(2)
    return `${size}MB`
  }
  async init () {
    try {
      let ft = new FileType(this.file)
      this.type = await ft.getType()
      this.readPicture()
      await this.calcMD5()
    } catch (e) {
      console.log(e)
    }
  }
  readPicture () {
    let reader = new FileReader()
    reader.readAsDataURL(this.file)
    reader.onload = (r, p) => {
      // 对于gif图片，不对其进行压缩
      if (this.type === 'gif') {
        this.preview = r.currentTarget.result
      } else {
        this.compressImage(r.currentTarget.result)
      }
      reader = undefined
    }
  }

  /**
   * 生成压缩后的缩略图
   * @param data
   */
  compressImage (data) {
    let canvas = document.createElement('canvas')
    let context = canvas.getContext('2d')
    canvas.width = 70
    canvas.height = 70
    let img = new Image()
    img.src = data
    img.onload = () => {
      context.drawImage(img, 0, 0, 70, 70)
      this.preview = canvas.toDataURL('image/png', 0.8)
      context.closePath()
      context = undefined
      canvas = undefined
    }
  }
  calcMD5 () {
    return new Promise((resolve, reject) => {
      bmf.md5(
        this.file, (_, md5) => {
          this.hash = md5
          resolve(md5)
        },
        progress => {}
      )
    })
  }
}
