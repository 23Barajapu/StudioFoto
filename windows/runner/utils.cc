#include "utils.h"

#include <flutter_windows.h>
#include <io.h>
#include <stdio.h>
#include <windows.h>

#include <filesystem>

std::string GetExecutableName() {
  wchar_t path[MAX_PATH];
  GetModuleFileName(nullptr, path, MAX_PATH);
  std::filesystem::path exe_path(path);
  return exe_path.filename().string();
}

std::string GetExecutableDirectory() {
  wchar_t path[MAX_PATH];
  GetModuleFileName(nullptr, path, MAX_PATH);
  std::filesystem::path exe_path(path);
  return exe_path.parent_path().string();
}

std::vector<std::string> GetCommandLineArguments() {
  // Convert the UTF-16 command line arguments to UTF-8 for the engine to
  // consume.
  int argc;
  wchar_t** argv = CommandLineToArgvW(GetCommandLineW(), &argc);
  if (argv == nullptr) {
    return {};
  }

  std::vector<std::string> command_line_arguments;

  // Skip the first argument as it's the binary name.
  for (int i = 1; i < argc; i++) {
    // Convert the UTF-16 string to UTF-8.
    int size = WideCharToMultiByte(CP_UTF8, 0, argv[i], -1, nullptr, 0, nullptr,
                                   nullptr);
    if (size == 0) {
      continue;
    }
    std::string utf8_string(size - 1, 0);
    WideCharToMultiByte(CP_UTF8, 0, argv[i], -1, utf8_string.data(),
                        utf8_string.size(), nullptr, nullptr);
    command_line_arguments.push_back(utf8_string);
  }

  LocalFree(argv);
  return command_line_arguments;
}
