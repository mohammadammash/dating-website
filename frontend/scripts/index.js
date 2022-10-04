// instance to hold main functions we will always reuse in different pages:
const main_object = {};

main_object.baseURL = "http://127.0.0.1:8000/api";

main_object.getAPI = async (api_url, jwt_token = "") => {
  try {
    return await axios(api_url, {
      headers: {
        Authorization: "Bearer " + jwt_token,
        Accept: "application/json",
      },
    });
  } catch (error) {
    console.log("/GET API:", error);
  }
};

main_object.postAPI = async (api_url, api_data, jwt_token = "") => {
  try {
    return await axios.post(api_url, api_data, {
      headers: {
        Authorization: "Bearer " + jwt_token,
        Accept: "application/json",
      },
    });
  } catch (error) {
    console.log("/POST API:", error);
  }
};
