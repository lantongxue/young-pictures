<template>
  <div>
    <div class="row">
      <div class="col-lg-12">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-bullhorn"></i>
              拖拽图片（或点击）下面区域进行上传，单次最多上传30张图片，且单张图片大小不超过<span class="badge badge-danger">2MB</span>
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-danger btn-sm" @click="cleanUploadQueue">
                <i class="far fa-trash-alt"></i> 清空
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="drag-area" :class="{center: uploadFiles.length === 0,pointer: uploadFiles.length === 0}" @dragenter="dragenter" @dragover="dragenter" @drop="ondrop" @dragleave="dragleave" @click="openFileDialog">
              <h2 v-show="uploadFiles.length === 0">请将图片拖拽或点击此区域进行上传</h2>
              <div class="row">
                <div class="col-2" v-for="(file, index) of uploadFiles" :key="index">
                  <div class="info-box">
                  <span class="info-box-icon">
                    <img :src="file.preview">
                  </span>
                    <div class="info-box-content">
                      <span class="info-box-text">{{ file.file.name }}</span>
                      <span class="info-box-number">{{ file.sizeFormat }}</span>
                      <div class="progress">
                        <div class="progress-bar bg-success" :style="{width: file.progress+'%'}"></div>
                      </div>
                      <span class="progress-description">{{ file.progressState }}</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                </div>
              </div>
              <input type="file" v-show="false" ref="fileInput" multiple accept="image/*" @change="choiceFile">
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <button type="button" class="btn btn-success" @click="startUpload">确认上传</button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">上传结果</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <table class="table table-bordered">
              <tbody>
                <tr v-for="(result, index) in uploadResult" :key="index">
                  <td>{{ result.name }}</td>
                  <td>
                    <a :href="result.url" target="_blank">{{ result.url }}</a>
                  </td>
                  <td>
                    <button type="button" v-clipboard:copy="result.url" v-clipboard:success="onCopy" class="btn btn-success btn-xs">复制</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import UploadFile from '../adapter/upload-file'
import config from '../config'
import axios from 'axios'

export default {
  name: 'index',
  data () {
    return {
      uploadFiles: [],
      uploadResult: []
    }
  },
  methods: {
    /**
     * 遍历文件夹
     * @param item: FileSystemFileEntry | FileSystemDirectoryEntry
     */
    scanFiles (entry) {
      if (entry.isDirectory) {
        let directoryReader = entry.createReader()
        directoryReader.readEntries((entries) => {
          entries.forEach((_entry) => {
            // 递归当前实例，因为当前实例有可能也是一个文件夹
            this.scanFiles(_entry)
          })
        })
      } else if (entry.isFile) {
        entry.file((file) => {
          this.addFile(file)
        })
      }
    },
    dragenter ($event) {
      $event.stopPropagation()
      $event.preventDefault()
      $event.target.classList.add('dragover')
    },
    ondrop ($event) {
      $event.stopPropagation()
      $event.preventDefault()
      let dt = $event.dataTransfer
      let items = dt.items
      // 计算差值
      let diff = config.MAX_UPLOAD_FILES - this.uploadFiles.length
      if (diff === 0) {
        window.toastr.error(`单次上传最多支持${config.MAX_UPLOAD_FILES}张图片`, '已超过最大数量')
        return
      }
      // 截取最大数量
      items = [...items].slice(0, diff)
      for (let item of items) {
        if (item.kind !== 'string' && item.webkitGetAsEntry) {
          let entry = item.webkitGetAsEntry()
          if (entry && entry.isFile) {
            this.addFile(item.getAsFile())
          } else {
            this.scanFiles(entry)
          }
        }
      }
      $event.target.classList.remove('dragover')
    },
    dragleave ($event) {
      $event.stopPropagation()
      $event.preventDefault()
      $event.target.classList.remove('dragover')
    },
    openFileDialog ($event) {
      this.$refs.fileInput.dispatchEvent(new MouseEvent('click'))
    },
    choiceFile ($event) {
      for (let file of $event.target.files) {
        this.addFile(file)
      }
      $event.target.value = ''
    },
    async addFile (file) {
      if (this.uploadFiles.length >= config.MAX_UPLOAD_FILES) {
        window.toastr.error(`单次上传最多支持${config.MAX_UPLOAD_FILES}张图片`, '已超过最大数量')
        return
      }
      if (file.size > config.MAX_FILE_SIZE) {
        window.toastr.error(file.name, '单文件最大支持2M大小')
        return
      }
      let uploadFile = new UploadFile(file)
      await uploadFile.init()
      if (uploadFile.type !== '') {
        this.uploadFiles.push(uploadFile)
      } else {
        window.toastr.error(file.name, '不支持该类型的文件')
      }
    },
    cleanUploadQueue () {
      if (this.uploadFiles.length > 0) {
        window.Swal.fire({
          title: '提示',
          text: '确认清空当前待上传的图片？',
          icon: 'warning',
          showCancelButton: true,
          cancelButtonColor: '#ee3131',
          cancelButtonText: '取消操作',
          confirmButtonColor: '#3085d6',
          confirmButtonText: '是的，确认清空'
        }).then((result) => {
          if (result.value) {
            this.uploadFiles.splice(0, this.uploadFiles.length)
            window.toastr.success('操作成功')
          }
        })
      } else {
        window.toastr.error('还没有添加图片哦')
      }
    },
    startUpload () {
      if (this.uploadFiles.length < 1) {
        window.toastr.error('还没有添加图片哦')
        return
      }
      window.Swal.fire({
        title: '提示',
        text: '本站启用自动检测技术, 禁止上传色情/反动/博彩/黑产等违法图片\n' +
          '一经发现, 立即永久封IP!确认上传图片？',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#ee3131',
        cancelButtonText: '取消操作',
        confirmButtonColor: '#3085d6',
        confirmButtonText: '我明白，确认上传'
      }).then((result) => {
        if (result.value) {
          // 上传优化点：
          // (delete) 1. 控制并发上传数量，单次5个上传请求。PS：暂时不考虑这个，顺便测试一下服务端的性能
          // 2. 控制上传，只允许未上传过的文件上传，其余状态一律不允许
          for (let file of this.uploadFiles) {
            if (file.state !== 'wait') {
              continue
            }
            let data = new FormData()
            data.append('hash', file.hash)
            data.append('image', file.file)
            axios.post('/upload', data, {
              onUploadProgress: progressEvent => {
                file.state = 'uploading'
                file.progressState = '上传中'
                file.progress = (progressEvent.loaded / progressEvent.total * 100) | 0
              }
            }).then(value => {
              file.state = 'success'
              file.progressState = '完成'
              this.uploadResult.push(value.data.data)
            }).catch(reason => {
              file.state = 'error'
              file.progressState = '失败'
            })
          }
        }
      })
    },
    onCopy () {
      window.toastr.success('复制成功')
    }
  }
}
</script>

<style scoped>
.drag-area{
  height: 520px;
  border: dashed 2px #0090ff;
  border-radius: 5px;
  padding: 10px;
  transition: all .2s;
}
.drag-area.pointer{
  cursor: pointer;
}
.drag-area.center{
  display: flex;
  align-items: center;
  justify-content: center;
}
.drag-area.dragover{
  border: solid 2px #0090ff;
}
.info-box .progress-description {
  font-size: 14px;
}
</style>
