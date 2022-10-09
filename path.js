function find(object, path) {

    path_parts = path.split('.');
    parts_count = path_parts.length;
    working_object = object  
  
    for (var i=0; i<parts_count; i++){
        if (working_object.hasOwnProperty(path_parts[i])) {
              working_object = working_object[path_parts[i]];    
            } else {
              return undefined;
            }          
    };
  
    return working_object;  
}