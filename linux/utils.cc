#include "utils.h"

#include <flutter_linux/flutter_linux.h>
#include <sys/utsname.h>
#include <cstring>

#include "flutter/generated_plugin_registrant.h"

std::string GetExecutableName() {
  char path[1024];
  ssize_t length = readlink("/proc/self/exe", path, sizeof(path) - 1);
  if (length == -1) {
    return "";
  }
  path[length] = '\0';
  char* basename = strrchr(path, '/');
  if (basename == nullptr) {
    return "";
  }
  return basename + 1;
}

std::string GetExecutableDirectory() {
  char path[1024];
  ssize_t length = readlink("/proc/self/exe", path, sizeof(path) - 1);
  if (length == -1) {
    return "";
  }
  path[length] = '\0';
  char* dirname = strrchr(path, '/');
  if (dirname == nullptr) {
    return "";
  }
  *dirname = '\0';
  return path;
}
