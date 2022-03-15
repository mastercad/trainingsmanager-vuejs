function upload(formData) {
  const photos = formData.getAll('photos');
  const promises = photos.map((x) => generateImage(x)
    .then(img => ({
      id: img,
      originalName: x.name,
      fileName: x.name,
      url: img
    })));
  return Promise.all(promises);
}

function generateImage(file) {
  return new Promise((resolve, reject) => {
    const fReader = new FileReader();
    const img = document.createElement('img');

    fReader.onload = () => {
      img.src = fReader.result;
      resolve(generateBase64Image(img));
    }

    fReader.readAsDataURL(file);
  });
}

function generateBase64Image(img) {
  console.log(img);
  return img.src;
  const canvas = document.createElement('canvas');
  canvas.width = img.width;
  canvas.height = img.height;

  const ctx = canvas.getContext('2d');
  ctx.drawImage(img, 0, 0);

  const dataURL = canvas.toDataURL('image/png');
  console.log(dataURL);

  return dataURL;
}

export { upload }
