import {offset} from './offset'
export default class {
  /**
   * 构造函数
   * @param file File 对象
   */
  constructor (file) {
    this.file = file
  }
  getType () {
    return new Promise((resolve, reject) => {
      let reader = new FileReader()
      reader.onload = (r, e) => {
        let buffer = r.currentTarget.result
        let offsetLength = offset.length
        for (let i = 0; i < offsetLength; i++) {
          let item = offset[i]
          let binaryData = buffer.slice(item.offset[0], item.offset[1])
          let binaryString = this.arrayBuffer2HexString(binaryData)
          let fingerprint = item.fingerprint.toUpperCase()
          if (fingerprint.indexOf('?') === -1) {
            if (binaryString === fingerprint) resolve(item.type)
          } else {
            if (this.hexCompare(binaryString, fingerprint)) resolve(item.type)
          }
        }
        reject(new Error('unknown type'))
      }
      reader.readAsArrayBuffer(this.file)
    })
  }

  /**
   * 将十进制转换为十六进制，同时向前补0
   * @param number
   * @returns {string}
   */
  int2Hex (number) {
    let hex = (Array(2).join('0') + Number(number).toString(16)).slice(-2)
    return hex
  }

  /**
   * 将文件流（Arraybuffer）转换为十六进制字符串
   * 例如：A1 FF BB CC AA 05
   * @param buffer ArrayBuffer
   * @returns {string}
   */
  arrayBuffer2HexString (buffer) {
    let array = [...new Uint8Array(buffer)]
    let string = array.map((v, index, arr) => {
      return this.int2Hex(v)
    }).toString().replace(/,/g, ' ').toUpperCase()
    return string
  }

  /**
   * 十六进制对比。支持模糊匹配
   * 模糊格式：52 49 46 46 ?? ?? ?? ?? 57 45 42 50
   * @param hex1 不支持模糊
   * @param hex2 支持模糊格式
   * @returns {boolean}
   */
  hexCompare (hex1, hex2) {
    let pattern = hex2.replace(/\?\?/g, '(.*)')
    let regExp = new RegExp(pattern)
    return regExp.test(hex1)
  }
}
