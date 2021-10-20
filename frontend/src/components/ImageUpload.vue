<template>
  <div :id="id" class="image-uploader">
    <div v-if="modelValue.id" class="position-relative">
      <img class="logo border" :src="modelValue.url" />
      <div class="delete bg-danger text-white border" @click="remove()" role="button">
        <i class="far fa-trash-alt m-auto text-white" />
      </div>
    </div>
    <div
      v-else
      class="dropzone"
      role="button"
      @click="actualImageInput.click()"
      @dragover.prevent="(e) => onDragover(e)"
      @dragleave.prevent="(e) => onDragLeave(e)"
      @drop.prevent="(e) => uploadDroppedImage(e)"
    >
      <input type="file" class="actual-input d-none" @input="(e) => uploadSelectedImage(e)" />
      <i class="upload-icon fas fa-cloud-upload-alt text-muted" />
      <span class="text-muted text-center">Перетащите фото сюда или нажмите, чтобы выбрать</span>
    </div>
  </div>
</template>

<style lang="scss">
.image-uploader {
  .logo {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 1rem;
  }
  .delete {
    position: absolute;
    top: 0;
    left: 0;
    width: 20px;
    height: 20px;
    border-radius: 4px 0;
    display: flex;
  }
  .dropzone {
    width: 200px;
    height: 200px;
    border: 3px solid #eee;
    border-style: dashed;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    .upload-icon {
      font-size: 2rem;
    }
    * {
      pointer-events: none;
    }
    &.drag-over {
      background: #eee;
    }
  }
}
</style>

<script lang="ts">
import Api from "@/common/api/index";
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";
import Image from "@/types/image/Image";

export default class ImageUpload extends Vue {
  @Prop(Object) readonly modelValue!: Image;
  id = "ImageUploader_" + Math.random();
  get actualImageInput(): HTMLInputElement {
    return document.getElementById(this.id)!.querySelector(".actual-input")!;
  }

  upload(toUpload: File) {
    let formData = new FormData();
    formData.append("image", toUpload);

    Api.multimedia.image.upload(formData).then((response) => {
      if (!response.success) {
        return;
      }

      this.$emit("update:modelValue", {
        id: response.data.id,
        url: response.data.url,
      });
    });
  }
  remove() {
    this.$emit("update:modelValue", {
      id: null,
      url: "http://localhost:8007/images/default.svg",
    });
  }

  onDragover(e: DragEvent) {
    if (!e.dataTransfer?.items || !e.dataTransfer?.items.length) {
      return;
    }

    (e.target as HTMLElement)?.classList.add("drag-over");
  }
  onDragLeave(e: DragEvent) {
    if (!e.dataTransfer?.items || !e.dataTransfer?.items.length) {
      return;
    }

    (e.target as HTMLElement)?.classList.remove("drag-over");
  }
  uploadDroppedImage(e: DragEvent) {
    this.onDragLeave(e);

    const items = e.dataTransfer?.items;
    if (!items || !items.length) {
      return;
    }

    const file = items[0].getAsFile();
    if (!file) {
      return;
    }

    this.upload(file);
  }
  uploadSelectedImage(e: InputEvent) {
    const files = (e.target as HTMLInputElement).files;
    if (!files?.length) {
      return;
    }
    this.upload(files[0]);
  }
}
</script>
