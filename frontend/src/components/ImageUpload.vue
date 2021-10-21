<template>
  <div :id="id" class="image-upload">
    <span class="label" v-if="label">
      {{ label }}
    </span>
    <div v-if="modelValue.id" class="uploaded-image-wrapper">
      <img class="value" :src="modelValue.url" />
      <corner-icon v-if="showDeleteButton" class="danger" icon="far fa-trash-alt" @click="() => remove()" />
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
import { Options, Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";
import Image from "@/types/image/Image";
import config from "@/config";
import CornerIcon from "./CornerIcon.vue";

@Options({
  components: { CornerIcon },
})
export default class ImageUpload extends Vue {
  @Prop(Object) readonly modelValue!: Image;
  @Prop({ type: String, required: false }) readonly label!: string;
  @Prop({ type: Boolean, default: false }) readonly showDeleteButton!: boolean;

  private id = "ImageUploader_" + Date.now();
  get actualImageInput(): HTMLInputElement {
    return document.getElementById(this.id)!.querySelector(".actual-input")!;
  }

  upload(toUpload: File) {
    const formData = new FormData();
    formData.append("image", toUpload);

    Api.multimedia.image.upload(formData).then((response) => {
      if (!response.success) {
        return;
      }

      this.$emit("update:modelValue", {
        id: response.data.id,
        url: response.data.url,
      });
      this.$emit("change");
    });
  }
  remove() {
    this.$emit("update:modelValue", {
      id: null,
      url: config.hosts.service + "/images/default.svg",
    });
    this.$emit("change");
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
