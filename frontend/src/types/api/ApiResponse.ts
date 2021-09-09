type ApiResponse = {
  success: boolean;
  errors: { [key: string]: string };
  exception: string;
  data: any;
  code?: number;
};
export default ApiResponse;
