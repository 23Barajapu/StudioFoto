# Project-level configuration.
cmake_minimum_required(VERSION 3.14)
project(runner LANGUAGES CXX)

# The name of the executable created for the application. Change this to change
# the on-disk name of your application.
set(BINARY_NAME "prime_foto_studio")

# The unique GTK application identifier for this application. See:
# https://wiki.gnome.org/HowDoI/GTKApplication
set(APPLICATION_ID "com.primefotostudio.app")

# Explicitly opt in to modern CMake behaviors to avoid warnings with recent
# versions of CMake.
cmake_policy(SET CMP0063 NEW)

# Define the application target. To change its name, change BINARY_NAME above,
# not the value here, or `flutter create` will regenerate the files with your
# change.
#
# Any new source files that you add to the application should be added here.
add_executable(${BINARY_NAME}
  "main.cpp"
  "flutter_window.cpp"
  "win32_window.cpp"
  "utils.cpp"
  "runner.rc"
  "resource.h"
  "flutter/generated_plugin_registrant.cc"
  "flutter/generated_plugin_registrant.h"
)

# Apply a standard set of build settings. This can be removed for applications
# that need different build settings.
apply_standard_settings(${BINARY_NAME})

# Add dependency libraries and include directories. Add any application-specific
# dependencies here.
target_link_libraries(${BINARY_NAME} PRIVATE flutter)
target_link_libraries(${BINARY_NAME} PRIVATE "dwmapi.lib")
target_link_libraries(${BINARY_NAME} PRIVATE "d2d1.lib")
target_link_libraries(${BINARY_NAME} PRIVATE "dxgi.lib")
target_link_libraries(${BINARY_NAME} PRIVATE "d3d11.lib")
target_link_libraries(${BINARY_NAME} PRIVATE "uxtheme.lib")

# Run the Flutter tool portions of the build. This must not be removed.
_apply_standard_settings(${BINARY_NAME})
