function find(object, path) {
  
  return  path.split('.').reduce ( (obj, prop) => (typeof obj != "undefined") ? ( obj.hasOwnProperty(prop) ? obj[prop] : undefined) : undefined , object );
  
}