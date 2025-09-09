#include "win32_window.h"

#include <flutter_windows.h>

#include "resource.h"

namespace {

constexpr const wchar_t kWindowClassName[] = L"FLUTTER_RUNNER_WIN32_WINDOW";

// The number of Win32Window objects that currently exist.
static int g_active_window_count = 0;

using EnableNonClientDpiScaling = BOOL __stdcall(HWND hwnd);

// Scale helper to convert logical scaler values to physical using passed in
// scale factor
int Scale(int source, double scale_factor) {
  return static_cast<int>(source * scale_factor);
}

// Dynamically loads the |EnableNonClientDpiScaling| from *user32.dll*.
// This API is only available on Windows 10 version 1607 and beyond.
// Available on Windows 10 version 1607 and beyond.
void EnableFullDpiSupportIfAvailable(HWND hwnd) {
  HMODULE user32_module = LoadLibraryA("user32.dll");
  if (!user32_module) {
    return;
  }

  auto enable_non_client_dpi_scaling =
      reinterpret_cast<EnableNonClientDpiScaling*>(
          GetProcAddress(user32_module, "EnableNonClientDpiScaling"));
  if (enable_non_client_dpi_scaling != nullptr) {
    enable_non_client_dpi_scaling(hwnd);
  }
  FreeLibrary(user32_module);
}

}  // namespace

// Manages the Win32Window's class registration.
class WindowClassRegistrar {
 public:
  ~WindowClassRegistrar() = default;

  // Returns the singleton registar's instance.
  static WindowClassRegistrar* GetInstance() {
    if (!instance_) {
      instance_ = new WindowClassRegistrar();
    }
    return instance_;
  }

  // Registers the Win32Window class.
  void Register();

  // Unregisters the Win32Window class;
  void Unregister();

  // Returns the name of the window class.
  const wchar_t* GetWindowClass();

  // Returns the default window icon.
  HICON GetIcon();

 private:
  WindowClassRegistrar() = default;

  static WindowClassRegistrar* instance_;

  bool class_registered_ = false;
};

WindowClassRegistrar* WindowClassRegistrar::instance_ = nullptr;

void WindowClassRegistrar::Register() {
  if (class_registered_) {
    return;
  }

  WNDCLASS window_class{};
  window_class.hCursor = LoadCursor(nullptr, IDC_ARROW);
  window_class.lpszClassName = kWindowClassName;
  window_class.style = CS_HREDRAW | CS_VREDRAW;
  window_class.cbClsExtra = 0;
  window_class.cbWndExtra = 0;
  window_class.hIcon = LoadIcon(nullptr, IDI_APPLICATION);
  window_class.hbrBackground = (HBRUSH)(COLOR_WINDOW + 1);
  window_class.lpszMenuName = nullptr;
  window_class.hInstance = GetModuleHandle(nullptr);
  window_class.lpfnWndProc = Win32Window::WndProc;
  RegisterClass(&window_class);
  class_registered_ = true;
}

void WindowClassRegistrar::Unregister() {
  if (!class_registered_) {
    return;
  }

  UnregisterClass(kWindowClassName, GetModuleHandle(nullptr));
  class_registered_ = false;
}

const wchar_t* WindowClassRegistrar::GetWindowClass() {
  return kWindowClassName;
}

HICON WindowClassRegistrar::GetIcon() {
  return LoadIcon(nullptr, IDI_APPLICATION);
}

Win32Window::Win32Window() {
  ++g_active_window_count;
}

Win32Window::~Win32Window() {
  --g_active_window_count;
  Destroy();
}

bool Win32Window::CreateAndShow(const std::wstring& title, const Point& origin,
                                const Size& size) {
  Destroy();

  WindowClassRegistrar::GetInstance()->Register();

  const POINT target_point = {static_cast<LONG>(origin.x),
                              static_cast<LONG>(origin.y)};
  HMONITOR monitor = MonitorFromPoint(target_point, MONITOR_DEFAULTTONEAREST);
  UINT dpi = FlutterDesktopGetDpiForMonitor(monitor);
  double scale_factor = dpi / 96.0;

  HWND window = CreateWindow(
      kWindowClassName, title.c_str(), WS_OVERLAPPEDWINDOW,
      Scale(origin.x, scale_factor), Scale(origin.y, scale_factor),
      Scale(size.width, scale_factor), Scale(size.height, scale_factor),
      nullptr, nullptr, GetModuleHandle(nullptr), this);

  if (!window) {
    return false;
  }

  return OnCreate();
}

void Win32Window::Destroy() {
  if (window_handle_) {
    DestroyWindow(window_handle_);
    window_handle_ = nullptr;
  }
}

void Win32Window::SetChildContent(HWND content) {
  child_content_ = content;
  SetParent(content, window_handle_);
  RECT frame;
  GetClientRect(window_handle_, &frame);

  SetWindowPos(content, nullptr, frame.left, frame.top, frame.right,
               frame.bottom, SWIM_NOZORDER);

  EnableFullDpiSupportIfAvailable(window_handle_);
}

HWND Win32Window::GetHandle() {
  return window_handle_;
}

void Win32Window::SetQuitOnClose(bool quit_on_close) {
  quit_on_close_ = quit_on_close;
}

RECT Win32Window::GetClientArea() {
  RECT frame;
  GetClientRect(window_handle_, &frame);
  return frame;
}

LRESULT Win32Window::MessageHandler(HWND hwnd, UINT const message,
                                    WPARAM const wparam,
                                    LPARAM const lparam) noexcept {
  switch (message) {
    case WM_DESTROY:
      window_handle_ = nullptr;
      if (quit_on_close_) {
        PostQuitMessage(0);
      }
      return 0;

    case WM_PAINT:
      PAINTSTRUCT ps;
      BeginPaint(hwnd, &ps);
      EndPaint(hwnd, &ps);
      return 0;

    case WM_SIZE:
      if (child_content_ != nullptr) {
        RECT frame;
        GetClientRect(hwnd, &frame);
        SetWindowPos(child_content_, nullptr, frame.left, frame.top, frame.right,
                     frame.bottom, SWIM_NOZORDER);
      }
      return 0;

    case WM_ACTIVATE:
      if (child_content_ != nullptr) {
        SetFocus(child_content_);
      }
      return 0;

    case WM_DWMCOMPOSITIONCHANGED:
      if (child_content_ != nullptr) {
        // Force the window to recreate itself with the new composition mode.
        SetWindowPos(child_content_, nullptr, 0, 0, 0, 0,
                     SWIM_NOZORDER | SWIM_NOMOVE | SWIM_NOSIZE);
      }
      return 0;
  }

  return DefWindowProc(hwnd, message, wparam, lparam);
}

LRESULT Win32Window::WndProc(HWND const window, UINT const message,
                             WPARAM const wparam, LPARAM const lparam) noexcept {
  if (message == WM_NCCREATE) {
    auto window_struct = reinterpret_cast<CREATESTRUCT*>(lparam);
    SetWindowLongPtr(window, GWLP_USERDATA,
                     reinterpret_cast<LONG_PTR>(window_struct->lpCreateParams));

    auto that = static_cast<Win32Window*>(window_struct->lpCreateParams);
    that->window_handle_ = window;
  } else if (Win32Window* that = GetThisFromHandle(window)) {
    return that->MessageHandler(window, message, wparam, lparam);
  }

  return DefWindowProc(window, message, wparam, lparam);
}

Win32Window* Win32Window::GetThisFromHandle(HWND const window) noexcept {
  return reinterpret_cast<Win32Window*>(
      GetWindowLongPtr(window, GWLP_USERDATA));
}

bool Win32Window::OnCreate() {
  return true;
}

void Win32Window::OnDestroy() {
  // No-op; provided for subclasses.
}
