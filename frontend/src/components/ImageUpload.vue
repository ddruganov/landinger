<template>
  <div :id="id" class="image-upload">
    <div v-if="modelValue.id" class="uploaded-image-wrapper">
      <img class="value" :src="modelValue.url" />
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
      <i class="upload-icon fas fa-cloud-upload-alt" />
      <span class="caption">Перетащите фото сюда или нажмите, чтобы выбрать</span>
    </div>
  </div>
</template>

<script lang="ts">
import Api from "@/common/api/index";
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";
import Image from "@/types/image/Image";
import config from "@/config";

export default class ImageUpload extends Vue {
  @Prop(Object) readonly modelValue!: Image;
  id = "ImageUploader_" + Date.now();
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
      url: config.hosts.service + "/images/default.svg",
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
